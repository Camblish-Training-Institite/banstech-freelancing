@extends('dashboards.freelancer.dashboard')
@section('body')

<div class="bg-gray-100 dark:bg-gray-900 py-12">
    <div class="max-w mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Back Link -->
        <div class="mb-6">
            <a href="{{ route('client.contests.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Back to All Contests
            </a>
        </div>

        <div class="lg:grid lg:grid-cols-3 lg:gap-8">

            <!-- Left Column: Contest Details -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">{{ $contest->title }}</h2>
                    <div class="prose prose-lg dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                        {!! $contest->description !!}
                    </div>

                    @if($contest->required_skills)
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Required Skills</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($contest->required_skills as $skill)
                                    <span class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Entries Section -->
                <div class="mt-8">
                     <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Entries</h3>
                     <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                        {{-- You can loop through and display contest entries here --}}
                        {{-- Example of an entry card --}}
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <p class="text-gray-600 dark:text-gray-400">No entries submitted yet.</p>
                        </div>
                     </div>
                </div>
            </div>

            <!-- Right Column: Summary & Actions -->
            <div class="mt-8 lg:mt-0">
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 sticky top-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">Contest Summary</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Prize</dt>
                            <dd class="mt-1 text-2xl font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($contest->prize_money, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1">
                                @php
                                    $statusClass = '';
                                    if ($contest->status === 'open') {
                                        $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                    } elseif ($contest->status === 'closed') {
                                        $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                    } else {
                                        $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium {{ $statusClass }}">
                                    {{ ucfirst($contest->status) }}
                                </span>
                            </dd>
                        </div>
                         <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Closes On</dt>
                            <dd class="mt-1 text-md font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($contest->closing_date)->format('d F, Y') }}</dd>
                        </div>
                    </div>

                    @if ($contest->status === 'open' && $contest->closing_date >= now()->toDateString())
                        <div class="mt-6">
                            <a href="{{ route('freelancer.contests.submit.show', $contest) }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10">
                                Submit Entry
                            </a>
                        </div>
                    @else
                        <div class="mt-6 p-4 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-lg text-center">
                            This contest is now closed.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection