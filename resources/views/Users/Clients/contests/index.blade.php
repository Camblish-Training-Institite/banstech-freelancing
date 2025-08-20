{{-- @extends('dashboards.Client.dashboard')

@section('body') --}}
    {{-- <div class="bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4 md:mb-0">
                    My Contests
                </h2>
                <a href="{{ route('client.contests.create') }}"
                    class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Post New Contest
                </a> --}}

{{-- <div>
    <div class="mx-auto py-12 px-4 sm:px-6 lg:px-8"> --}}
        <!-- moved add contest section to the contest-section blade -->

        <!-- beginning of if directive with the false condition -->
        {{-- @if ($contests->isEmpty())
            <div class="text-center py-16">
                <!-- <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2z" />
                </svg> -->
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Contests Yet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by posting a new contest.</p>

            </div>
        @else --}}
            {{-- <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                
                @foreach ($contests as $contest)
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transform hover:scale-105 transition-transform duration-300 ease-in-out">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                    {{ $contest->title }}</h3>
                                @php
                                    $statusClass = '';
                                    if ($contest->status === 'open') {
                                        $statusClass =
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                    } elseif ($contest->status === 'closed') {
                                        $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                    } else {
                                        $statusClass =
                                            'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                    }
                                @endphp
                                <span
                                    class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium {{ $statusClass }}">
                                    {{ ucfirst($contest->status) }}
                                </span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                                Closing on: {{ $contest->closing_date->format('d F, Y') }}
                            </p>

                            <div
                                class="flex items-center justify-between mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Prize</span>
                                    <span
                                        class="text-lg font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($contest->prize_money, 2) }}</span>
                                </div>
                                <div class="flex flex-col text-right">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Entries</span>
                                    <span
                                        class="text-lg font-bold text-gray-900 dark:text-white">{{ $contest->entries->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4"> --}}
                            {{-- <a href="{{ route('client.contests.show', $contest) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-semibold text-sm">
                            View Details &rarr;
                        </a> --}}

                            {{-- <a href="{{ route('client.contests.show', $contest) }}" class="block hover:bg-gray-50">
                                <div class="p-4 border-b">
                                    <h3 class="font-medium">{{ $contest->title }}</h3>
                                    <p class="text-sm text-gray-600">{{ Str::limit($contest->description, 100) }}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div> --}}

            <!-- old table display section with end of if directive -->
            {{-- <table class="w-full mt-6">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contest Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Closing Date</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contests as $contest)
                        @php
                            $endDate = new DateTime($contest->closing_date);
                            $formatedEndDate = $endDate->format('d F Y');
                        @endphp
                        <tr>
                            <td class="px-6 py-4">{{ $contest->title }}</td>
                            <td class="px-6 py-4">${{ $contest->prize_money }}</td>
                            <td class="px-6 py-4">{{ $formatedEndDate }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('client.contests.show', $contest) }}" class="text-blue-500 hover:text-blue-600">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif --}}

    <table class="min-w-full w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contest Title</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Closing Date</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
            </tr>
        </thead>
        <tbody>
            {{-- @foreach ($contests as $contest)
                @php
                    $endDate = new DateTime($contest->closing_date);
                    $formatedEndDate = $endDate->format('d F Y');
                @endphp
                <tr>
                    <td class="px-6 py-4">{{ $contest->title }}</td>
                    <td class="px-6 py-4">${{ $contest->prize_money }}</td>
                    <td class="px-6 py-4">{{ $formatedEndDate }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('client.contests.show', $contest) }}" class="text-blue-500 hover:text-blue-600">View</a>
                    </td>
                </tr>
            @endforeach --}}
            @forelse ($contests as $contest)
                @php
                    $endDate = new DateTime($contest->closing_date);
                    $formatedEndDate = $endDate->format('d F Y');
                @endphp
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-left">{{ $contest->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-left">R {{ $contest->prize_money }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-left">{{ $formatedEndDate }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('client.contests.show', $contest) }}" class="text-blue-500 hover:text-blue-600" style="background-color: rgb(0 0 0); color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 500;">View</a>
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
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Contests Yet</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by posting a new contest.</p>
                        <div class="mt-4 ml-4 flex-shrink-0">
                            <a href="{{ route('client.contests.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Post New Contest
                            </a>
                        </div>
                    </div>
                </td>
            @endforelse
        </tbody>
    </table>
