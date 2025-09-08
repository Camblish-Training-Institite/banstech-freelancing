<div class="bg-white rounded-lg shadow-md">
    <div class="p-6 flex justify-between items-center">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Payment Summary</h3>
            <div class="flex space-x-8 mt-4">
                <div>
                    <p class="text-gray-500">Requested</p>
                    <p class="text-2xl font-bold text-gray-800">$0.00</p>
                </div>
                <div>
                    <p class="text-gray-500">In Progress</p>
                    <p class="text-2xl font-bold text-gray-800">R{{ number_format($project->milestones->where('status', '<>', 'released')->sum('amount'), 2) }}</p> {{-- number_format($project->milestones->where('status', 'in_progress')->sum('amount'), 2) --}}
                </div>
                <div>
                    <p class="text-gray-500">Released</p>
                    <p class="text-2xl font-bold text-gray-800">R{{ number_format($project->sumReleased(), 2) }}</p> {{-- number_format($project->milestones->where('status', 'released')->sum('amount'), 2) --}}
                </div>
            </div>
        </div>
        <!-- insert your project milestone creation route here -->
        {{-- route('projects.milestones.create', $project) --}}
        <div>
            <a href="{{route('pm.project.milestones.create', $project)}}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                Create Milestone
            </a>
        </div>
    </div>

    <div class="p-6 border-t border-gray-200">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Milestone Payments</h3>
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 border-b-2 border-gray-300"></th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse ($project->milestones as $milestone)
                    <tr>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $milestone->due_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $milestone->title }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($milestone->status == 'released') bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $milestone->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">${{ number_format($milestone->amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                            @if($milestone->status != 'released')
                                <a href="{{route('pm.project.milestone.release', [$project, $milestone])}}" class="text-indigo-600 hover:text-indigo-900">Release</a>
                            @else
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">View Invoice</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>
                            <h2>No milestones yet</h2>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
