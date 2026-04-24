<?php

namespace App\Services\Payments;

use App\Notifications\WithdrawalStatusUpdated;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class WithdrawalProcessingService
{
    public function __construct(protected PayPalPayoutService $payPalPayoutService)
    {
    }

    public function submit(User $user, float $amount, string $method): WithdrawalRequest
    {
        $withdrawalRequest = WithdrawalRequest::create([
            'user_id' => $user->id,
            'amount' => round($amount, 2),
            'method' => $method,
            'status' => 'pending',
            'destination_details' => $method === 'paypal'
                ? $this->paypalDestinationDetails($user)
                : $this->bankDestinationDetails($user),
            'requested_at' => now(),
        ]);

        if ($method === 'paypal' && $this->payPalPayoutService->isConfigured()) {
            $this->sendPayPalWithdrawal($withdrawalRequest);
        }

        return $withdrawalRequest->fresh();
    }

    public function confirm(WithdrawalRequest $withdrawalRequest, ?User $admin = null): WithdrawalRequest
    {
        if ($withdrawalRequest->status === 'processed') {
            throw ValidationException::withMessages([
                'withdrawal' => 'This withdrawal request has already been processed.',
            ]);
        }

        $previousStatus = $withdrawalRequest->status;

        if ($withdrawalRequest->method === 'paypal') {
            return $this->sendPayPalWithdrawal($withdrawalRequest, $admin, $previousStatus);
        }

        $withdrawalRequest->status = 'confirmed';
        $withdrawalRequest->confirmed_at = $withdrawalRequest->confirmed_at ?? now();
        $withdrawalRequest->reviewed_by_admin_id = $admin?->id ?? $withdrawalRequest->reviewed_by_admin_id;
        $withdrawalRequest->failure_reason = null;
        $withdrawalRequest->save();

        $this->notifyStatusChange($withdrawalRequest, $previousStatus);

        return $withdrawalRequest->fresh();
    }

    public function markProcessed(WithdrawalRequest $withdrawalRequest, ?User $admin = null): WithdrawalRequest
    {
        if ($withdrawalRequest->method === 'paypal') {
            if (! filled($withdrawalRequest->paypal_batch_id)) {
                throw ValidationException::withMessages([
                    'withdrawal' => 'PayPal withdrawals cannot be marked processed until a real PayPal batch ID exists.',
                ]);
            }

            if ($withdrawalRequest->status !== 'confirmed') {
                throw ValidationException::withMessages([
                    'withdrawal' => 'Use PayPal sync or confirm the request first before overriding it as processed.',
                ]);
            }
        }

        if ($withdrawalRequest->method === 'bank' && $withdrawalRequest->status !== 'confirmed') {
            throw ValidationException::withMessages([
                'withdrawal' => 'Bank withdrawals must be confirmed before they can be marked as processed.',
            ]);
        }

        if ($withdrawalRequest->status === 'failed') {
            throw ValidationException::withMessages([
                'withdrawal' => 'Failed withdrawal requests must be retried or recreated before processing.',
            ]);
        }

        $previousStatus = $withdrawalRequest->status;
        $withdrawalRequest->status = 'processed';
        $withdrawalRequest->confirmed_at = $withdrawalRequest->confirmed_at ?? now();
        $withdrawalRequest->processed_at = now();
        $withdrawalRequest->reviewed_by_admin_id = $admin?->id ?? $withdrawalRequest->reviewed_by_admin_id;
        $withdrawalRequest->failure_reason = null;
        $withdrawalRequest->save();

        $this->notifyStatusChange($withdrawalRequest, $previousStatus);

        return $withdrawalRequest->fresh();
    }

    public function markFailed(WithdrawalRequest $withdrawalRequest, ?User $admin = null, ?string $reason = null): WithdrawalRequest
    {
        $previousStatus = $withdrawalRequest->status;
        $withdrawalRequest->status = 'failed';
        $withdrawalRequest->processed_at = null;
        $withdrawalRequest->reviewed_by_admin_id = $admin?->id ?? $withdrawalRequest->reviewed_by_admin_id;
        $withdrawalRequest->failure_reason = $reason ?: 'Withdrawal request was marked as failed.';
        $withdrawalRequest->save();

        $this->notifyStatusChange($withdrawalRequest, $previousStatus);

        return $withdrawalRequest->fresh();
    }

    public function syncPayPalRequestsForUser(int $userId): void
    {
        $requests = WithdrawalRequest::where('user_id', $userId)
            ->where('method', 'paypal')
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereNotNull('paypal_batch_id')
            ->get();

        $this->syncPayPalRequests($requests);
    }

    public function syncPayPalRequests(Collection $requests): void
    {
        if (! $this->payPalPayoutService->isConfigured()) {
            return;
        }

        foreach ($requests as $withdrawalRequest) {
            try {
                $previousStatus = $withdrawalRequest->status;
                $paypalPayout = $this->payPalPayoutService->getPayout($withdrawalRequest->paypal_batch_id);
                $status = $this->mapPayPalStatus(
                    $paypalPayout['batch_status'] ?? null,
                    $paypalPayout['transaction_status'] ?? null
                );

                $withdrawalRequest->status = $status;
                $withdrawalRequest->confirmed_at = $withdrawalRequest->confirmed_at ?? now();
                $withdrawalRequest->processed_at = $status === 'processed'
                    ? ($withdrawalRequest->processed_at ?? now())
                    : null;
                $withdrawalRequest->paypal_payout_item_id = $paypalPayout['payout_item_id'] ?? $withdrawalRequest->paypal_payout_item_id;
                $withdrawalRequest->gateway_response = $paypalPayout['raw'] ?? $withdrawalRequest->gateway_response;
                $withdrawalRequest->failure_reason = $status === 'failed'
                    ? 'PayPal reported that the withdrawal could not be completed.'
                    : null;
                $withdrawalRequest->save();

                $this->notifyStatusChange($withdrawalRequest, $previousStatus);
            } catch (Throwable $e) {
                Log::warning('Unable to sync PayPal withdrawal request.', [
                    'withdrawal_request_id' => $withdrawalRequest->id,
                    'paypal_batch_id' => $withdrawalRequest->paypal_batch_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    protected function sendPayPalWithdrawal(WithdrawalRequest $withdrawalRequest, ?User $admin = null, ?string $previousStatus = null): WithdrawalRequest
    {
        if (! $this->payPalPayoutService->isConfigured()) {
            throw ValidationException::withMessages([
                'method' => 'PayPal withdrawals are not configured right now.',
            ]);
        }

        $previousStatus = $previousStatus ?? $withdrawalRequest->status;

        $recipientEmail = $withdrawalRequest->paypal_recipient_email
            ?: data_get($withdrawalRequest->destination_details, 'email')
            ?: $withdrawalRequest->user?->email;

        if (! $recipientEmail) {
            throw ValidationException::withMessages([
                'method' => 'A PayPal withdrawal requires a freelancer email address.',
            ]);
        }

        $paypalResponse = $this->payPalPayoutService->sendPayout(
            $recipientEmail,
            (float) $withdrawalRequest->amount,
            config('services.paypal.currency', 'USD'),
            'Withdrawal from BansTech',
            'Your withdrawal request has been submitted from BansTech.',
            'withdrawal-' . $withdrawalRequest->id
        );

        $status = $this->mapPayPalStatus(
            $paypalResponse['batch_status'] ?? null,
            data_get($paypalResponse, 'raw.items.0.transaction_status')
        );

        $withdrawalRequest->status = $status;
        $withdrawalRequest->paypal_recipient_email = $recipientEmail;
        $withdrawalRequest->paypal_batch_id = $paypalResponse['batch_id'] ?? $withdrawalRequest->paypal_batch_id;
        $withdrawalRequest->paypal_payout_item_id = $paypalResponse['payout_item_id'] ?? $withdrawalRequest->paypal_payout_item_id;
        $withdrawalRequest->gateway_response = $paypalResponse['raw'] ?? $withdrawalRequest->gateway_response;
        $withdrawalRequest->confirmed_at = $withdrawalRequest->confirmed_at ?? now();
        $withdrawalRequest->processed_at = $status === 'processed' ? now() : null;
        $withdrawalRequest->reviewed_by_admin_id = $admin?->id ?? $withdrawalRequest->reviewed_by_admin_id;
        $withdrawalRequest->failure_reason = null;
        $withdrawalRequest->save();

        $this->notifyStatusChange($withdrawalRequest, $previousStatus);

        return $withdrawalRequest->fresh();
    }

    protected function mapPayPalStatus(?string $batchStatus, ?string $transactionStatus): string
    {
        $normalizedBatchStatus = strtoupper((string) $batchStatus);
        $normalizedTransactionStatus = strtoupper((string) $transactionStatus);

        if (in_array($normalizedTransactionStatus, ['SUCCESS', 'UNCLAIMED'], true)
            || $normalizedBatchStatus === 'SUCCESS') {
            return 'processed';
        }

        if (in_array($normalizedTransactionStatus, ['DENIED', 'FAILED', 'BLOCKED', 'RETURNED', 'REFUNDED', 'REVERSED'], true)
            || in_array($normalizedBatchStatus, ['DENIED', 'FAILED'], true)) {
            return 'failed';
        }

        return 'confirmed';
    }

    protected function paypalDestinationDetails(User $user): array
    {
        if (! filled($user->email)) {
            throw ValidationException::withMessages([
                'method' => 'A PayPal withdrawal requires an email address on your account.',
            ]);
        }

        return [
            'email' => $user->email,
        ];
    }

    protected function bankDestinationDetails(User $user): array
    {
        if (! $user->bankDetail) {
            throw ValidationException::withMessages([
                'method' => 'Please add your bank details in profile management before requesting a bank withdrawal.',
            ]);
        }

        return [
            'account_holder_name' => $user->bankDetail->account_holder_name,
            'bank_name' => $user->bankDetail->bank_name,
            'account_number' => $user->bankDetail->account_number,
            'account_type' => $user->bankDetail->account_type,
            'branch_code' => $user->bankDetail->branch_code,
            'swift_code' => $user->bankDetail->swift_code,
        ];
    }

    protected function notifyStatusChange(WithdrawalRequest $withdrawalRequest, ?string $previousStatus): void
    {
        if ($withdrawalRequest->status === $previousStatus) {
            return;
        }

        if (! in_array($withdrawalRequest->status, ['confirmed', 'processed', 'failed'], true)) {
            return;
        }

        $withdrawalRequest->loadMissing('user');
        $withdrawalRequest->user?->notify(new WithdrawalStatusUpdated($withdrawalRequest->id));
    }
}
