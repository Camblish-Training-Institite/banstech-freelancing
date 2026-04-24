@php
    $headers = ['Job Title', 'Lowest Bid', 'Latest Submission', 'No. of Proposals', 'Action'];
    $rows = [
        fn ($job) => e($job->title),
        fn ($job) => 'R' . ($job->lowestBid() !== null ? $job->lowestBid() : '0.00'),
        fn ($job) => $job->latestSubmissionDate() ? \Carbon\Carbon::parse($job->latestSubmissionDate())->format('d F Y') : 'No submissions yet',
        fn ($job) => $job->proposals->where('status', 'pending')->count(),
        fn ($job) => '<a href="' . route('client.jobs.show', $job) . '" class="inline-flex rounded-md bg-black px-4 py-2 text-sm font-medium text-white">View</a>',
    ];

    $mobileConfig = [
        'titleIndex' => 0,
        'primaryIndex' => 1,
        'actionIndex' => 4,
        'excludeIndices' => [],
    ];
@endphp

<x-dashboard-table
    :headers="$headers"
    :items="$jobs"
    :rows="$rows"
    :mobile-config="$mobileConfig"
    :show-id="false"
>
    <x-slot:empty>
        <div class="flex w-full flex-col justify-center">
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Proposals Made Yet</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by posting a new job.</p>
            <div class="mt-4 ml-4 flex-shrink-0">
                <a href="{{ route('client.jobs.create') }}"
                    class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Post New job
                </a>
            </div>
        </div>
    </x-slot:empty>
</x-dashboard-table>
