<?php

namespace App\Services\Contracts;

use App\Models\Contract;
use App\Models\Job;
use App\Models\Milestone;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AssignFreelancerToJobService
{
    public function handle(Job $job, User $freelancer, float $agreedAmount, int $milestoneCount = 1): Contract
    {
        if ($job->status !== 'open') {
            throw new RuntimeException('This job is no longer open for assignment.');
        }

        if ($job->contract()->exists()) {
            throw new RuntimeException('This job already has an active contract.');
        }

        $milestoneCount = max(1, min($milestoneCount, 50));

        return DB::transaction(function () use ($job, $freelancer, $agreedAmount, $milestoneCount) {
            $job->status = 'assigned';
            $job->save();

            $contract = Contract::create([
                'job_id' => $job->id,
                'user_id' => $freelancer->id,
                'agreed_amount' => round($agreedAmount, 2),
                'status' => 'active',
                'end_date' => $job->deadline,
            ]);

            $this->createDefaultMilestones($contract, $milestoneCount);

            return $contract;
        });
    }

    protected function createDefaultMilestones(Contract $contract, int $milestoneCount): void
    {
        $totalAmount = round((float) $contract->agreed_amount, 2);
        $baseAmount = floor(($totalAmount / $milestoneCount) * 100) / 100;
        $amounts = [];

        for ($index = 0; $index < $milestoneCount; $index++) {
            $amounts[] = $baseAmount;
        }

        $distributed = round(array_sum($amounts), 2);
        $amounts[$milestoneCount - 1] = round($amounts[$milestoneCount - 1] + ($totalAmount - $distributed), 2);

        $startDate = now();
        $endDate = $contract->end_date ? \Illuminate\Support\Carbon::parse($contract->end_date) : now()->addWeeks($milestoneCount);
        $daySpan = max($startDate->diffInDays($endDate), 1);

        foreach ($amounts as $index => $amount) {
            $dueDate = $milestoneCount === 1
                ? $endDate
                : $startDate->copy()->addDays((int) round(($daySpan / $milestoneCount) * ($index + 1)));

            Milestone::create([
                'project_id' => $contract->id,
                'title' => $milestoneCount === 1
                    ? 'Full project delivery'
                    : 'Milestone ' . ($index + 1),
                'description' => $milestoneCount === 1
                    ? 'Complete project delivery and release the full agreed amount.'
                    : 'Auto-generated milestone ' . ($index + 1) . ' for this project.',
                'amount' => $amount,
                'due_date' => $dueDate->toDateString(),
                'status' => 'approved',
            ]);
        }
    }
}
