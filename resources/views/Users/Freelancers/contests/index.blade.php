    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Open Contests</h2>
    </x-slot>

    <div class="card-body">
        <div class="max-w mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full mt-6">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b">Project</th>
                                <th class="px-6 py-3 border-b">Price</th>
                                <th class="px-6 py-3 border-b">Closing Date</th>
                                <th class="px-6 py-3 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contests as $contest)
                                <tr>
                                    <td class="px-6 py-4">{{ $contest->title }}</td>
                                    <td class="px-6 py-4">${{ $contest->prize_money }}</td>
                                    <td class="px-6 py-4">{{ $contest->closing_date->format('d F Y') }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('freelancer.contests.show', $contest) }}" class="text-blue-500 hover:text-blue-600">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>