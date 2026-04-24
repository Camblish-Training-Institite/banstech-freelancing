@php
    $headers = ['Job Title', 'Amount Bidded', 'Submitted At', 'Status', 'Action'];
    $rows = [
        fn ($proposal) => e($proposal->job->title),
        fn ($proposal) => 'R ' . $proposal->bid_amount,
        fn ($proposal) => \Carbon\Carbon::parse($proposal->created_at)->format('d F Y'),
        fn ($proposal) => match ($proposal->status) {
            'pending' => '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-400">pending</span>',
            'accepted' => '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">accepted</span>',
            default => '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">' . e($proposal->status) . '</span>',
        },
        fn ($proposal) => '<a href="' . route('freelancer.proposal.show', $proposal->id) . '" class="inline-flex rounded-md bg-black px-4 py-2 text-sm font-medium text-white">View</a>',
    ];

    $mobileConfig = [
        'titleIndex' => 0,
        'primaryIndex' => 1,
        'statusIndex' => 3,
        'actionIndex' => 4,
        'excludeIndices' => [],
    ];
@endphp

<x-dashboard-table
    :headers="$headers"
    :items="$proposals"
    :rows="$rows"
    :mobile-config="$mobileConfig"
    :show-id="false"
>
    <x-slot:empty>
        <div class="flex w-full flex-col justify-center">
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Proposals Sent Yet</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by browsing some jobs.</p>
            <div class="mt-4 ml-4 flex-shrink-0">
                <a href="{{ route('jobs.listing') }}"
                    class="inline-flex items-center rounded-full border border-transparent bg-indigo-600 px-6 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Browse Jobs
                </a>
            </div>
        </div>
    </x-slot:empty>
</x-dashboard-table>
