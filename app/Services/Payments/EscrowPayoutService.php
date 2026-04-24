<?php

namespace App\Services\Payments;

use App\Models\Contract;
use App\Models\Job;
use App\Models\Milestone;
use App\Models\Payout;
use App\Models\ProjectFunding;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class EscrowPayoutService
{
    public function __construct(
        protected PayPalPayoutService $payPalPayoutService,
        protected CurrencyConversionService $currencyConversionService
    )
    {
    }

    public function getAvailableBalanceForClient(int $clientId): float
    {
        $deposited = (float) ProjectFunding::where('client_id', $clientId)
            ->where('status', 'deposited')
            ->sum('amount');

        $escrowed = (float) ProjectFunding::where('client_id', $clientId)
            ->where('status', 'pending')
            ->sum('amount');

        $spent = (float) Payout::whereHas('contract.job', function ($query) use ($clientId) {
            $query->where('user_id', $clientId);
        })
            ->whereIn('status', ['pending', 'processed'])
            ->sum('amount');

        return round(max($deposited - $escrowed - $spent, 0), 2);
    }

    public function fundJob(Job $job, ?float $amount = null): ProjectFunding
    {
        $contract = $job->contract;

        if (! $contract) {
            throw ValidationException::withMessages([
                'job' => 'This job does not yet have an active contract to fund.',
            ]);
        }

        $remainingRequired = $this->getRemainingAmountToFund($job);

        if ($remainingRequired <= 0) {
            throw ValidationException::withMessages([
                'job' => 'This job is already fully funded.',
            ]);
        }

        $amountToEscrow = round($amount ?? $remainingRequired, 2);

        if ($amountToEscrow <= 0) {
            throw ValidationException::withMessages([
                'amount' => 'Funding amount must be greater than zero.',
            ]);
        }

        if ($amountToEscrow > $remainingRequired) {
            throw ValidationException::withMessages([
                'amount' => 'Funding amount cannot exceed the remaining contract balance.',
            ]);
        }

        $availableBalance = $this->getAvailableBalanceForClient($job->user_id);

        if ($availableBalance < $amountToEscrow) {
            throw ValidationException::withMessages([
                'amount' => 'Insufficient wallet balance to fund this job.',
            ]);
        }

        return DB::transaction(function () use ($job, $contract, $amountToEscrow) {
            $escrow = ProjectFunding::firstOrNew([
                'client_id' => $job->user_id,
                'job_id' => $job->id,
                'status' => 'pending',
            ]);

            $escrow->amount = round(((float) $escrow->amount) + $amountToEscrow, 2);
            $escrow->save();

            $job->job_funded = $escrow->amount >= (float) $contract->agreed_amount;
            $job->save();

            return $escrow;
        });
    }

    public function releaseMilestone(Milestone $milestone): Payout
    {
        return DB::transaction(function () use ($milestone) {
            $milestone = Milestone::query()
                ->with(['project.job.user', 'project.freelancer'])
                ->lockForUpdate()
                ->findOrFail($milestone->id);

            if ($milestone->status === 'released') {
                throw ValidationException::withMessages([
                    'milestone' => 'This milestone has already been released.',
                ]);
            }

            $project = $milestone->project;
            $escrow = $this->getEscrowRecordForJob($project->job_id);

            if (Payout::where('milestone_id', $milestone->id)
                ->whereIn('status', ['pending', 'processed'])
                ->lockForUpdate()
                ->exists()) {
                throw ValidationException::withMessages([
                    'milestone' => 'A payout already exists for this milestone.',
                ]);
            }

            if (! $escrow || (float) $escrow->amount < (float) $milestone->amount) {
                throw ValidationException::withMessages([
                    'milestone' => 'There are not enough escrowed funds to release this milestone.',
                ]);
            }

            $recipientEmail = $project->freelancer?->email;

            if (! $recipientEmail) {
                throw ValidationException::withMessages([
                    'freelancer' => 'The freelancer does not have an email address for PayPal payout.',
                ]);
            }

            $currencyContext = $this->currencyConversionService->currencyContextForUser($project->freelancer);
            $conversion = $this->currencyConversionService->convert(
                (float) $milestone->amount,
                config('services.paypal.default_local_currency', 'USD'),
                $currencyContext['paypal_currency']
            );

            $paypalResponse = $this->payPalPayoutService->sendPayout(
                $recipientEmail,
                (float) $conversion['target_amount'],
                $conversion['target_currency'],
                'Milestone payout from BansTech',
                'Milestone "' . $milestone->title . '" has been released.',
                'milestone-' . $milestone->id
            );

            $milestone->status = 'released';
            $milestone->save();

            $this->decreaseEscrow($escrow, (float) $milestone->amount);

            return Payout::create([
                'freelancer_id' => $project->freelancer->id,
                'contract_id' => $project->id,
                'milestone_id' => $milestone->id,
                'amount' => $milestone->amount,
                'source_currency' => $conversion['source_currency'],
                'paypal_currency' => $conversion['target_currency'],
                'paypal_amount' => $conversion['target_amount'],
                'exchange_rate' => $conversion['exchange_rate'],
                'method' => 'paypal',
                'status' => $this->mapPayPalStatusToPayoutStatus($paypalResponse['batch_status'] ?? null),
                'requested_at' => now(),
                'processed_at' => $this->isProcessedStatus($paypalResponse['batch_status'] ?? null) ? now() : null,
                'paypal_recipient_email' => $recipientEmail,
                'paypal_batch_id' => $paypalResponse['batch_id'] ?? null,
                'paypal_payout_item_id' => $paypalResponse['payout_item_id'] ?? null,
                'gateway_response' => $paypalResponse['raw'] ?? null,
            ]);
        });
    }

    public function releaseRemainingEscrow(Contract $contract): ?Payout
    {
        $contract->loadMissing(['job.user', 'freelancer', 'milestones']);

        $escrow = $this->getEscrowRecordForJob($contract->job_id);

        if (! $escrow || (float) $escrow->amount <= 0) {
            return null;
        }

        $recipientEmail = $contract->freelancer?->email;

        if (! $recipientEmail) {
            throw ValidationException::withMessages([
                'freelancer' => 'The freelancer does not have an email address for PayPal payout.',
            ]);
        }

        $remainingAmount = (float) $escrow->amount;
        $currencyContext = $this->currencyConversionService->currencyContextForUser($contract->freelancer);
        $conversion = $this->currencyConversionService->convert(
            $remainingAmount,
            config('services.paypal.default_local_currency', 'USD'),
            $currencyContext['paypal_currency']
        );

        $paypalResponse = $this->payPalPayoutService->sendPayout(
            $recipientEmail,
            (float) $conversion['target_amount'],
            $conversion['target_currency'],
            'Final contract payout from BansTech',
            'Remaining contract balance has been released for "' . $contract->job->title . '".',
            'contract-' . $contract->id . '-final'
        );

        return DB::transaction(function () use ($contract, $escrow, $remainingAmount, $recipientEmail, $paypalResponse, $conversion) {
            $contract->milestones()
                ->where('status', '!=', 'released')
                ->update(['status' => 'released']);

            $this->decreaseEscrow($escrow, $remainingAmount);

            return Payout::create([
                'freelancer_id' => $contract->freelancer->id,
                'contract_id' => $contract->id,
                'amount' => $remainingAmount,
                'source_currency' => $conversion['source_currency'],
                'paypal_currency' => $conversion['target_currency'],
                'paypal_amount' => $conversion['target_amount'],
                'exchange_rate' => $conversion['exchange_rate'],
                'method' => 'paypal',
                'status' => $this->mapPayPalStatusToPayoutStatus($paypalResponse['batch_status'] ?? null),
                'requested_at' => now(),
                'processed_at' => $this->isProcessedStatus($paypalResponse['batch_status'] ?? null) ? now() : null,
                'paypal_recipient_email' => $recipientEmail,
                'paypal_batch_id' => $paypalResponse['batch_id'] ?? null,
                'paypal_payout_item_id' => $paypalResponse['payout_item_id'] ?? null,
                'gateway_response' => $paypalResponse['raw'] ?? null,
            ]);
        });
    }

    public function getEscrowAmountForJob(Job $job): float
    {
        return (float) ProjectFunding::where('client_id', $job->user_id)
            ->where('job_id', $job->id)
            ->where('status', 'pending')
            ->sum('amount');
    }

    public function getRemainingAmountToFund(Job $job): float
    {
        $contractAmount = (float) optional($job->contract)->agreed_amount;
        return round(max($contractAmount - $this->getEscrowAmountForJob($job), 0), 2);
    }

    protected function getEscrowRecordForJob(int $jobId): ?ProjectFunding
    {
        return ProjectFunding::where('job_id', $jobId)
            ->where('status', 'pending')
            ->lockForUpdate()
            ->first();
    }

    protected function decreaseEscrow(ProjectFunding $escrow, float $amount): void
    {
        $remaining = round(((float) $escrow->amount) - $amount, 2);

        if ($remaining < 0) {
            throw new RuntimeException('Escrow balance cannot go below zero.');
        }

        if ($remaining === 0.0) {
            $job = $escrow->job;
            $escrow->delete();

            if ($job) {
                $job->job_funded = false;
                $job->save();
            }

            return;
        }

        $escrow->amount = $remaining;
        $escrow->save();
    }

    protected function mapPayPalStatusToPayoutStatus(?string $paypalStatus): string
    {
        return $this->isProcessedStatus($paypalStatus) ? 'processed' : 'pending';
    }

    protected function isProcessedStatus(?string $paypalStatus): bool
    {
        return strtoupper((string) $paypalStatus) === 'SUCCESS';
    }
}
