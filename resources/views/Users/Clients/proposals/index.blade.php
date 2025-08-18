<table class="min-w-full w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">job Title</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lowest Bid</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Latest Submission</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. of proposals</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
        </tr>
    </thead>
    <tbody>
        {{-- @foreach ($jobs as $job)
            @php
                $endDate = new DateTime($job->closing_date);
                $formatedEndDate = $endDate->format('d F Y');
            @endphp
            <tr>
                <td class="px-6 py-4">{{ $job->title }}</td>
                <td class="px-6 py-4">${{ $job->prize_money }}</td>
                <td class="px-6 py-4">{{ $formatedEndDate }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('client.proposals.show', $job) }}" class="text-blue-500 hover:text-blue-600">View</a>
                </td>
            </tr>
        @endforeach --}}
        @forelse ($jobs as $job)
            @php
                // dd($job->latestSubmissionDate());
                $endDate = new DateTime($job->latestSubmissionDate());
                $formatedEndDate = $endDate->format('d F Y');
                
            @endphp
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-left">{{ $job->title }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-left">R {{ $job->lowestBid() }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-left">{{ $formatedEndDate }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-left">{{ $job->proposals->count() }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <a href="{{ route('client.proposals.show', $job) }}" class="text-blue-500 hover:text-blue-600" style="background-color: #000; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 500;">View</a>
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
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Proposals Made Yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by posting a new job.</p>
                    <div class="mt-4 ml-4 flex-shrink-0">
                        <a href="{{ route('client.jobs.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Post New job
                        </a>
                    </div>
                </div>
            </td>
        @endforelse
    </tbody>
</table>
