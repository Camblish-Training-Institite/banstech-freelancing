<table class="min-w-full w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client Name</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project Title</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($projects as $project)
            @php
                $endDate = new DateTime($project->end_date);
                $formattedEndDate = $endDate->format('M d Y');
                // dd($project->milestones->where('status', 'released')->count());
                $allMilestones = $project->milestones->isempty() ? 1:  $project->milestones->count();
                // dd($allMilestones);
                $completedMilestones = $project->milestones->where('status', 'released')->count();

                $percentage = number_format(($completedMilestones/$allMilestones)*100);
            @endphp
            <tr class="hover:bg-gray-50 cursor-pointer text-left" onclick="window.location.href='{{ route('freelancer.projects.show', $project->id) }}'">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center rounded-full w-8 h-8 object-cover overflow-hidden">
                            @if ($project->job->user->profile)
                                <img src="{{ asset('storage/' . $project->job->user->profile->avatar) }}" alt="">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ $project->job->user->name }}&background=random&size=128" alt="">
                            @endif
                        </div>
                        <p class="mx-2">{{ $project->job->user->name }}</p>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $project->job->title }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">R {{ number_format($project->agreed_amount, 2) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($project->job->deadline)->diffForHumans() }}</td> {{-- $formattedEndDate --}}
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                        <div class="" style="width:100%; height:3px; border-radius:2px; background-color:#ddd; font-weight:bold;">
                            <div class="" style="width:{{$percentage}}%; height:100%; border-radius:2px; background-color:#7A4D8B; font-weight:bold;"></div>
                        </div>
                        <div>
                            <p class="text-sm">{{$percentage}}%</p>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                    <div class="no-projects-message">
                        <div class="icon-box">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="flex flex-col items-center">
                        <p>No Available projects</p>
                        <div class="mt-4 ml-4 flex-shrink-0">
                            <a href="{{ route('jobs.listing') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Browse Jobs 
                            </a>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table> 