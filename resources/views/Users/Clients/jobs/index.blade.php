@php
    $headers = ['Job Title', 'Budget', 'End Date', 'Status', 'Action'];
    $rows = [
        fn ($job) => e($job->title),
        fn ($job) => 'R ' . number_format($job->budget, 2),
        fn ($job) => optional($job->deadline ? \Carbon\Carbon::parse($job->deadline) : null)?->format('M d Y') ?? 'N/A',
        fn ($job) => match ($job->status) {
            'completed' => '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">completed</span>',
            'assigned' => '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">assigned</span>',
            'in_progress' => '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">in progress</span>',
            'open' => '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">open</span>',
            'canceled', 'cancelled' => '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">' . e($job->status) . '</span>',
            default => '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800">' . e($job->status) . '</span>',
        },
        fn ($job) => '<a href="' . route('client.jobs.show', $job) . '" class="inline-flex items-center justify-center rounded-md bg-black px-4 py-2 text-sm font-medium text-white">View</a>',
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
    :items="$jobs"
    :rows="$rows"
    :mobile-config="$mobileConfig"
    :show-id="false"
    :row-url="fn ($job) => route('client.jobs.show', $job)"
>
    <x-slot:empty>
        <div class="no-projects-message">
            <div class="icon-box">
                <i class="fas fa-box-open"></i>
            </div>
            <div class="flex flex-col items-center">
                <p>No Available Projects</p>
                <div class="mt-4 ml-4 flex-shrink-0">
                    <a href="{{ route('client.jobs.create') }}"
                        class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Post New Job
                    </a>
                </div>
            </div>
        </div>
    </x-slot:empty>
</x-dashboard-table>
