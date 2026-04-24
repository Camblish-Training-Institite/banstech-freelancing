@php
    $headers = ['Freelancer Name', 'Title', 'Price', 'Completion Date', 'Progress'];
    $rows = [
        fn ($project) => '<div class="flex items-center">' . view('components.user-avatar', ['user' => $project->user, 'width' => '2rem', 'height' => '2rem'])->render() . '<span class="ml-2">' . e($project->user->name) . '</span></div>',
        fn ($project) => e($project->job->title),
        fn ($project) => 'R ' . number_format($project->agreed_amount, 2),
        fn ($project) => optional($project->job->deadline ? \Carbon\Carbon::parse($project->job->deadline) : null)?->format('M d Y') ?? 'N/A',
        function ($project) {
            $allMilestones = $project->milestones->isEmpty() ? 1 : $project->milestones->count();
            $completedMilestones = $project->milestones->where('status', 'released')->count();
            $percentage = number_format(($completedMilestones / $allMilestones) * 100);

            if ($project->status === 'completed') {
                return '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 projects-accent-text">Completed</span>';
            }

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
    :row-url="fn ($project) => route('client.project.show', $project->id)"
>
    <x-slot:empty>
        <div class="no-projects-message flex flex-col items-center justify-center py-8">
            <div class="icon-box projects-accent-soft mb-4 flex h-14 w-14 items-center justify-center rounded-full">
                <i class="fas fa-box-open projects-accent-text text-xl"></i>
            </div>
            <p class="projects-accent-text font-semibold">No Available projects</p>
        </div>
    </x-slot:empty>
</x-dashboard-table>
