 <table class="min-w-full w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Freelancer Name</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion Date</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($projects as $project)
            @php
                $endDate = new DateTime($project->job->deadline);
                $formattedEndDate = $endDate->format('M d Y');
            @endphp
            <tr class="hover:bg-gray-50 cursor-pointer text-left" onclick="window.location.href='{{route('client.project.show', $project->id)}}'"> {{-- {{ route('client.projects.show', $project) }} --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center rounded-full w-8 h-8 object-cover overflow-hidden">
                            @if ($project->user->profile)
                                <img src="{{ asset('storage/' . $project->user->profile->avatar) }}" alt="">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ $project->user->name }}&background=random&size=128" alt="">
                            @endif
                        </div>
                        <p class="mx-2">{{ $project->user->name }}</p>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $project->job->title }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">R {{ number_format($project->agreed_amount, 2) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $formattedEndDate }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if ($project->status == "in_progress" || $project->status == "assigned" || $project->status == "open")
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-gray-400">{{ $project->status }}</span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-gray-400">{{ $project->status }}</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                    <div class="no-projects-message">
                        <div class="icon-box">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <p>No Available projects</p>
                    {{-- <button class="find-opportunities-btn">Find New Opportunities</button> --}}
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table> 