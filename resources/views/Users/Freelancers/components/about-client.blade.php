@php
    $userProfile = $user->profile ?? null;
    $profileFields = [
        $userProfile?->first_name,
        $userProfile?->last_name,
        $userProfile?->bio,
        $userProfile?->address,
        $userProfile?->city,
        $userProfile?->country,
        $userProfile?->company,
        $userProfile?->location,
        $userProfile?->timezone,
        $userProfile?->avatar,
    ];

    $profileCompletion = count(array_filter($profileFields, fn ($value) => filled($value)));
    $profileCompletionPercentage = (int) round(($profileCompletion / max(count($profileFields), 1)) * 100);
    $hasCompletedProfile = $profileCompletionPercentage >= 60;
    $hasVerifiedEmail = !is_null($user->email_verified_at);
    $escrowRecord = $job
        ? \App\Models\ProjectFunding::where('job_id', $job->id)
            ->where('status', 'pending')
            ->latest('id')
            ->first()
        : null;
    $escrowAmount = (float) ($escrowRecord->amount ?? 0);
    $hasEscrowFunding = (bool) ($job?->job_funded) || $escrowAmount > 0;
    $walletDepositCount = \App\Models\ProjectFunding::where('client_id', $user->id)
        ->whereNull('job_id')
        ->where('status', 'deposited')
        ->count();
    $escrowHistoryCount = \App\Models\ProjectFunding::where('client_id', $user->id)
        ->whereNotNull('job_id')
        ->count();
    $hasBankDetails = !is_null($user->bankDetail);
    $hasPaymentMethodSignal = $walletDepositCount > 0 || $escrowHistoryCount > 0 || $hasBankDetails;
    $jobsPostedCount = $user->jobs()->count();
    $fundedJobsCount = $user->jobs()->where('job_funded', true)->count();
    $openJobsCount = $user->jobs()->where('status', 'open')->count();
    $accountAgeInDays = (int) $user->created_at->diffInDays(now());

    $riskScore = 0;
    $riskNotes = [];

    if (! $hasVerifiedEmail) {
        $riskScore += 3;
        $riskNotes[] = 'Email is not verified.';
    }

    if (! $hasEscrowFunding) {
        $riskScore += 3;
        $riskNotes[] = 'This job is not funded in escrow yet.';
    }

    if (! $hasCompletedProfile) {
        $riskScore += 2;
        $riskNotes[] = 'Client profile is still incomplete.';
    }

    if (! $hasPaymentMethodSignal) {
        $riskScore += 2;
        $riskNotes[] = 'No platform payment history is visible yet.';
    }

    if ($accountAgeInDays < 30) {
        $riskScore += 1;
        $riskNotes[] = 'Account is still new on the platform.';
    }

    if ($jobsPostedCount === 0) {
        $riskScore += 1;
        $riskNotes[] = 'No previous jobs posted yet.';
    }

    if ($riskScore >= 7) {
        $riskLabel = 'Higher Risk';
        $riskClasses = 'bg-red-100 text-red-700 border-red-200';
    } elseif ($riskScore >= 4) {
        $riskLabel = 'Needs Review';
        $riskClasses = 'bg-amber-100 text-amber-700 border-amber-200';
    } else {
        $riskLabel = 'Lower Risk';
        $riskClasses = 'bg-green-100 text-green-700 border-green-200';
    }

    $trustChecks = [
        ['label' => 'Escrow funded for this job', 'state' => $hasEscrowFunding, 'hint' => $hasEscrowFunding ? 'Funds are available for this contract.' : 'Ask the client to fund escrow before you begin.'],
        ['label' => 'Verified email', 'state' => $hasVerifiedEmail, 'hint' => $hasVerifiedEmail ? 'Email address has been verified.' : 'Treat unverified accounts more carefully.'],
        ['label' => 'Completed profile', 'state' => $hasCompletedProfile, 'hint' => $profileCompletionPercentage . '% of profile details completed.'],
        ['label' => 'Payment method / platform payment history', 'state' => $hasPaymentMethodSignal, 'hint' => $hasPaymentMethodSignal ? 'Client has payment activity or payment details on file.' : 'No clear payment trail on the platform yet.'],
        ['label' => 'Hiring history', 'state' => $jobsPostedCount > 0, 'hint' => $jobsPostedCount . ' job(s) posted, ' . $fundedJobsCount . ' funded.'],
    ];
@endphp

<aside class="space-y-6 mt-8 lg:mt-0 py-3">
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <img
                    class="h-16 w-16 rounded-full object-cover"
                    src="{{ $userProfile && $userProfile->avatar ? asset('storage/' . $userProfile->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&size=128' }}"
                    alt="{{ $user->name }}"
                >
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $userProfile?->location ?: 'Location not added yet' }}</p>
                    <p class="mt-1 text-xs text-gray-400">Member since {{ $user->created_at->format('d M, Y') }}</p>
                </div>
            </div>
            <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $riskClasses }}">
                {{ $riskLabel }}
            </span>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
            <div class="rounded-lg bg-gray-50 p-3">
                <p class="text-xs uppercase tracking-wide text-gray-500">Escrow</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $hasEscrowFunding ? 'R' . number_format($escrowAmount > 0 ? $escrowAmount : (float) $job->budget, 2) . ' secured' : 'Not funded yet' }}
                </p>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
                <p class="text-xs uppercase tracking-wide text-gray-500">Profile Completion</p>
                <p class="mt-1 font-semibold text-gray-900">{{ $profileCompletionPercentage }}%</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
                <p class="text-xs uppercase tracking-wide text-gray-500">Jobs Posted</p>
                <p class="mt-1 font-semibold text-gray-900">{{ $jobsPostedCount }}</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
                <p class="text-xs uppercase tracking-wide text-gray-500">Funded Jobs</p>
                <p class="mt-1 font-semibold text-gray-900">{{ $fundedJobsCount }}</p>
            </div>
        </div>

        <div class="mt-6">
            <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Trust Checks</h4>
            <ul class="mt-3 space-y-3">
                @foreach ($trustChecks as $check)
                    <li class="flex items-start gap-3">
                        <i class="{{ $check['state'] ? 'fa-solid fa-circle-check text-green-500' : 'fa-solid fa-circle-xmark text-red-500' }} mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $check['label'] }}</p>
                            <p class="text-xs text-gray-500">{{ $check['hint'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
            <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Scam Check</h4>
            @if (count($riskNotes) > 0)
                <ul class="mt-3 space-y-2 text-sm text-gray-700">
                    @foreach ($riskNotes as $note)
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-triangle-exclamation mt-0.5 text-amber-500"></i>
                            <span>{{ $note }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="mt-3 text-sm text-gray-700">No major warning signs were detected from the client profile and payment activity shown on-platform.</p>
            @endif

            <p class="mt-4 text-xs text-gray-500">
                Best practice: keep communication on-platform and do not begin physical or digital delivery until escrow is funded.
            </p>
        </div>
    </div>
</aside>
