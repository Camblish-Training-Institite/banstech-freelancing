<div class="mb-2 flex w-full items-center justify-start px-2">
    <h2 class="font-bold">Job Title: {{ $job->title }}</h2>
</div>

@php
    $pendingProposals = $job->proposals->where('status', 'pending')->values();
    $headers = ['Freelancer Name', 'Project Title', 'Bid Amount', 'Date Submitted', 'Action'];
    $rows = [
        fn ($proposal) => '<div class="flex items-center">' . view('components.user-avatar', ['user' => $proposal->user, 'width' => '2rem', 'height' => '2rem'])->render() . '<span class="mx-2">' . e($proposal->user->name) . '</span></div>',
        fn ($proposal) => e($job->title),
        fn ($proposal) => 'R ' . number_format($proposal->bid_amount, 2),
        fn ($proposal) => \Carbon\Carbon::parse($proposal->created_at)->diffForHumans(),
        fn ($proposal) => '<a href="' . route('client.proposals.show', $proposal) . '" class="text-indigo-600">view proposal</a>',
    ];

    $mobileConfig = [
        'titleIndex' => 0,
        'subtitleIndex' => 1,
        'primaryIndex' => 2,
        'actionIndex' => 4,
        'excludeIndices' => [],
    ];
@endphp

<x-dashboard-table
    :headers="$headers"
    :items="$pendingProposals"
    :rows="$rows"
    :mobile-config="$mobileConfig"
    :show-id="false"
>
    <x-slot:empty>
        <div class="no-projects-message">
            <div class="icon-box"></div>
            <div class="flex flex-col items-center">
                <p>No Proposals</p>
            </div>
        </div>
    </x-slot:empty>
</x-dashboard-table>
