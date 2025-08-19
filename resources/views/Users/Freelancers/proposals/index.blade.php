<table class="min-w-full w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Title</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Bidded</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted At</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
        </tr>
    </thead>
    <tbody>
        {{-- @foreach ($proposals as $proposal)
            @php
                $endDate = new DateTime($proposal->closing_date);
                $formatedEndDate = $endDate->format('d F Y');
            @endphp
            <tr>
                <td class="px-6 py-4">{{ $proposal->title }}</td>
                <td class="px-6 py-4">${{ $proposal->prize_money }}</td>
                <td class="px-6 py-4">{{ $formatedEndDate }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('freelancer.proposal.show', $proposal) }}" class="text-blue-500 hover:text-blue-600">View</a>
                </td>
            </tr>
        @endforeach --}}
        @forelse ($proposals as $proposal)
            @php
                $endDate = new DateTime($proposal->created_at);
                $formatedEndDate = $endDate->format('d F Y');
            @endphp
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-left">{{ $proposal->job->title }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-left">R {{ $proposal->bid_amount }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-left">{{ $formatedEndDate }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-left">
                    @if ($proposal->status == "pending")
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-400">{{ $proposal->status }}</span>
                    @else
                        @if ($proposal->status == "accepted")
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ $proposal->status }}</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ $proposal->status }}</span>
                        @endif
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <a href="{{ route('freelancer.proposal.show', $proposal->id) }}" class="text-blue-500 hover:text-blue-600" style="background-color: #000; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 500;">View</a>
                </td>
            </tr>
        @empty
            <td colspan="3" class="px-8 py-12 text-center text-sm text-gray-500"    >
                <div class="flex flex-col w-full justify-center">
                    {{-- <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2z" />
                    </svg> --}}
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Proposals Sent Yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by browsing some jobs.</p>
                    <div class="mt-4 ml-4 flex-shrink-0">
                        <a href="{{ route('jobs.listing') }}"
                            class="inline-flex items-center py-2 px-6 border border-transparent rounded-full text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Browse Jobs
                        </a>
                    </div>
                </div>
            </td>
        @endforelse
    </tbody>
</table>
