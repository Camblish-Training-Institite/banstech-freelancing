<div class="flex w-full items-center justify-start mb-2 px-2">
    <h2 class="font-bold">Job Title: {{$job->title}}</h2>
</div>
<table class="min-w-full w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Freelancer Name</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project Title</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bid Amount</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Submitted</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($job->proposals->where('status', 'pending') as $proposal)
            @php
                $endDate = new DateTime($proposal->created_at);
                $formattedEndDate = $endDate->format('M d Y');
                
            @endphp
            <tr class="hover:bg-gray-50 cursor-pointer text-left">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center rounded-full w-8 h-8 object-cover overflow-hidden">
                            @if ($proposal->user->profile)
                                <img src="{{ asset('storage/' . $proposal->user->profile->avatar) }}" alt="">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ $proposal->user->name }}&background=random&size=128" alt="">
                            @endif
                        </div>
                        <p class="mx-2">{{ $proposal->user->name }}</p>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $job->title }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">R {{ number_format($proposal->bid_amount, 2) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($proposal->created_at)->diffForHumans() }}</td> {{-- $formattedEndDate --}}
                <td class="px-6 py-4 whitespace-nowrap">
                    <a href="{{ route('client.proposals.show', $proposal) }}">
                        <p class="text-indigo-600 ">view proposal</p>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                    <div class="no-projects-message">
                        <div class="icon-box">
                    </div>
                    <div class="flex flex-col items-center">
                        <p>No Proposals</p>
                    </div>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table> 