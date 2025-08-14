@extends('Users.Clients.layouts.body.dashboard-body')

@section('active-tab')
    {{-- NEW: Separate heading for "Active projects (0)" --}}
    <div class="flex justify-between items-center mb-2">
        <h3 class="active-projects-heading">Created Jobs ({{$jobs->count()}})</h3>
        <div class="mt-4 ml-4 flex-shrink-0">
            <a href="{{ route('client.jobs.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Add New Job
            </a>
        </div>
    </div>
    {{-- The large active-projects-section box, now without its own h3 inside --}}
    <div class="active-projects-section">
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
                            @if ($job->status == "in_progress" || $job->status == "assigned" || $job->status == "open")
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-gray-400">{{ $job->status }}</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-gray-400">{{ $job->status }}</span>
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
                            <p>No Available Projects</p>
                            {{-- <button class="find-opportunities-btn">Find New Opportunities</button> --}}
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table> 
    </div>
@endsection