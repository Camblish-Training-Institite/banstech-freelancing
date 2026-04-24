<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">Open Contests</h2>
</x-slot>

@php
    $headers = ['Project', 'Price', 'Closing Date', 'Action'];
    $rows = [
        fn ($contest) => e($contest->title),
        fn ($contest) => '$' . $contest->prize_money,
        fn ($contest) => $contest->closing_date->format('d F Y'),
        fn ($contest) => '<a href="' . route('freelancer.contests.show', $contest) . '" class="text-blue-500 hover:text-blue-600">View</a>',
    ];

    $mobileConfig = [
        'titleIndex' => 0,
        'primaryIndex' => 1,
        'actionIndex' => 3,
        'excludeIndices' => [],
    ];
@endphp

<x-dashboard-table
    :headers="$headers"
    :items="$contests"
    :rows="$rows"
    :mobile-config="$mobileConfig"
    :show-id="false"
>
    <x-slot:empty>
        <div class="no-projects-message">
            <div class="icon-box">
                <i class="fas fa-box-open"></i>
            </div>
            <p>No Available Contest</p>
        </div>
    </x-slot:empty>
</x-dashboard-table>
