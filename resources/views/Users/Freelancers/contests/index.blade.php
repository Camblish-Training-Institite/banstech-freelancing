<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Open Contests</h2>
</x-slot>


<table class="min-w-full w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Project</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Closing Date</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Action</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($contests as $contest)
        <tr class="hover:bg-gray-50 cursor-pointer text-left">
            
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $contest->title }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ $contest->prize_money }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $contest->closing_date->format('d F Y') }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                <a href="{{ route('freelancer.contests.show', $contest) }}"
                    class="text-blue-500 hover:text-blue-600">View</a>
            </td>
        </tr>
        @empty
            <tr>
                <div class="no-projects-message">
                        <div class="icon-box">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <p>No Available Contest</p>
                    {{-- <button class="find-opportunities-btn">Find New Opportunities</button> --}}
                    </div>
            </tr>
        @endforelse
    </tbody>
</table>