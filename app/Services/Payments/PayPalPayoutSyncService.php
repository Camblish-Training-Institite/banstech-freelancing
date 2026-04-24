<?php

namespace App\Services\Payments;

use App\Models\Payout;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class PayPalPayoutSyncService
{
    public function __construct(protected PayPalPayoutService $payPalPayoutService)
    {
    }

    public function syncPendingPayoutsForUser(int $userId): void
    {
        $this->syncPendingPayouts(
            Payout::where('freelancer_id', $userId)
                ->where('method', 'paypal')
                ->where('status', 'pending')
                ->whereNotNull('paypal_batch_id')
                ->get()
        );
    }

    public function syncPendingPayouts(?Collection $payouts = null): void
    {
        if (! $this->payPalPayoutService->isConfigured()) {
            return;
        }

        $payouts = $payouts ?? Payout::where('method', 'paypal')
            ->where('status', 'pending')
            ->whereNotNull('paypal_batch_id')
            ->get();

        foreach ($payouts as $payout) {
            try {
                $paypalPayout = $this->payPalPayoutService->getPayout($payout->paypal_batch_id);
                $syncedStatus = $this->mapPayPalSyncStatus(
                    $paypalPayout['batch_status'] ?? null,
                    $paypalPayout['transaction_status'] ?? null
                );

                $payout->status = $syncedStatus;
                $payout->processed_at = $syncedStatus === 'processed'
                    ? ($payout->processed_at ?? now())
                    : null;
                $payout->paypal_payout_item_id = $paypalPayout['payout_item_id'] ?? $payout->paypal_payout_item_id;
                $payout->gateway_response = $paypalPayout['raw'] ?? $payout->gateway_response;
                $payout->save();
            } catch (Throwable $e) {
                Log::warning('Unable to sync PayPal payout status.', [
                    'payout_id' => $payout->id,
                    'paypal_batch_id' => $payout->paypal_batch_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    protected function mapPayPalSyncStatus(?string $batchStatus, ?string $transactionStatus): string
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

        return 'pending';
    }
}
