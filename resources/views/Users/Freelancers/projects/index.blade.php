@php
    $headers = ['Client Name', 'Project Title', 'Payment', 'End Date', 'Progress'];
    $rows = [
        fn ($project) => '<div class="flex items-center">' . view('components.user-avatar', ['user' => $project->job->user, 'width' => '2rem', 'height' => '2rem'])->render() . '<span class="mx-2">' . e($project->job->user->name) . '</span></div>',
        fn ($project) => e($project->job->title),
        fn ($project) => 'R ' . number_format($project->agreed_amount, 2),
        fn ($project) => \Carbon\Carbon::parse($project->job->deadline)->diffForHumans(),
        function ($project) {
            $allMilestones = $project->milestones->isEmpty() ? 1 : $project->milestones->count();
            $completedMilestones = $project->milestones->where('status', 'released')->count();
            $percentage = number_format(($completedMilestones / $allMilestones) * 100);

            return '<div class="flex items-center space-x-2"><div style="width:100%; height:3px; border-radius:9999px; background-color:#eadff0;"><div style="width:' . $percentage . '%; height:100%; border-radius:2px; background-color:#7A4D8B;"></div></div><div><p class="text-sm font-semibold projects-accent-text">' . $percentage . '%</p></div></div>';
        },
    ];

    $mobileConfig = [
        'titleIndex' => 1,
        'subtitleIndex' => 0,
        'primaryIndex' => 2,
        'statusIndex' => 4,
        'excludeIndices' => [],
    ];
@endphp

<x-dashboard-table
    :headers="$headers"
    :items="$projects"
    :rows="$rows"
    :mobile-config="$mobileConfig"
    :show-id="false"
    table-class="w-full divide-y divide-purple-100"
    head-class="projects-accent-soft"
    body-class="bg-white divide-y divide-purple-50"
    row-class="hover:bg-purple-50/60 text-left"
    :row-url="fn ($project) => route('freelancer.projects.show', $project->id)"
>
    <x-slot:empty>
        <div class="no-projects-message">
            <div class="icon-box projects-accent-soft mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full">
                <i class="fas fa-box-open projects-accent-text text-xl"></i>
            </div>
            <div class="flex flex-col items-center">
                <p class="projects-accent-text font-semibold">No Available projects</p>
                <div class="mt-4 ml-4 flex-shrink-0">
                    <a href="{{ route('jobs.listing') }}"
                        class="projects-accent inline-flex items-center rounded-md border border-transparent px-4 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:ring-offset-2">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Browse Jobs
                    </a>
                </div>
            </div>
        </div>
    </x-slot:empty>
</x-dashboard-table>
