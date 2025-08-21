<table class="min-w-full w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Title</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($jobs as $job)
            @php
                $endDate = new DateTime($job->deadline);
                $formattedEndDate = $endDate->format('M d Y');
            @endphp
            <tr class="hover:bg-gray-50 cursor-pointer text-left" onclick="window.location.href='{{ route('client.jobs.show', $job) }}'">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $job->title }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">R {{ number_format($job->budget, 2) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $formattedEndDate }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    {{-- @if ($job->status == "in_progress" || $job->status == "assigned" || $job->status == "open")
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-gray-400">{{ $job->status }}</span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-gray-400">{{ $job->status }}</span>
                    @endif --}}
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $job->status }}
                                        </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('client.jobs.show', $job) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <a href="{{ route('client.jobs.edit', $job) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                        <form action="{{ route('client.jobs.destroy', $job) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                    <div class="no-projects-message">
                        <div class="icon-box">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <p>No Available Projects</p>
                    {{-- <button class="find-opportunities-btn">Find New Opportunities</button> --}}
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table> 