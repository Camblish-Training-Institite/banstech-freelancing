@extends('dashboards.client.dashboard')

@section('body')

<style>
    h1 {
        font-size: 28px;
        color: #516aae;
        margin: 0;
        margin-bottom: 1.5rem;
    }
    .back-link {
        color: #516aae;
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
    }
    .back-link:hover {
        text-decoration: underline;
    }
    /* Tags */
    .tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 1rem 0;
    }
    .tags span {
        background-color: #f0eaff;
        color: #516aae;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }
</style>

<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $contest->title }}
        </h2>
        <a href="{{ route('client.contests.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700">
            ← Back to Contests
        </a>
    </div>
</x-slot>

<div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <!-- Contest Header Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
            <div class="flex w-full justify-start p-4">
                <a href="{{ url()->previous() }}" class="back-link">← Back to Jobs</a>
            </div>
           
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-start border-b pb-4">
                    <div class="flex-1">
                        {{-- <h3 class="text-lg font-medium text-gray-900">{{ $contest->title }}</h3> --}}
                        <div class="flex items-start justify-between w-full">
                            <h1>{{ $contest->title ?? 'Job Title Not Available' }}</h1>
                            <div class="ml-6 text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $contest->status == "open" ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $contest->status == "open" ? 'Active' : 'Closed' }}
                                </span>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">{{ $contest->description }}</p>
                        <!-- Tags -->
                        @if(!empty($contest->required_skills))
                            <div class="tags">
                                @php
                                    $contestSkills = $contest->required_skills ? explode(',', $contest->required_skills) : [];
                                    // dd($contestSkills);
                                    $contestSkills = str_replace('\"', '"', $contestSkills); // Replace escaped quotes
                                @endphp
                                @foreach($contestSkills as $skill)
                                    <span>{{ trim($skill) }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="font-medium">Prize:</span>
                                <span class="text-green-600 font-semibold"> R {{ number_format($contest->prize_money, 2)
                                    }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Date Created:</span>
                                <span>{{ $contest->created_at->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="font-medium">End Date:</span>
                                @if ($contest->status === 'closed')
                                    <span class="text-red-600">{{ $contest->closing_date->format('M d, Y') }}</span>
                                @else
                                    <span class="text-blue-600">{{ $contest->closing_date->format('M d, Y') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                </div>
                <!-- Action Buttons -->
                <div class="bg-white overflow-hidden mt-6">
                    <div class="px-2 flex space-x-4 justify-end">
                        <a href="{{ route('client.contests.edit', $contest) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Edit Contest
                        </a>

                        <form action="{{ route('client.contests.destroy', $contest) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this contest?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                Delete Contest
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        

        <!-- Contest Submissions Section -->
        @if($contest->entries->isNotEmpty())
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Submissions Received ({{
                    $contest->entries->count() }})</h3>

                <div class="space-y-4">
                    @foreach($contest->entries as $entry)
                    <div class="border rounded-lg p-4 hover:bg-gray-50">
                        <div class="flex justify-between">
                            <div>
                                <p class="font-medium">{{ $entry->title }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($entry->description, 120) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-2">Submitted by: {{ $entry->freelancer->name }}
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                @if($entry->file_path)
                                <a href="{{ Storage::url($entry->file_path) }}" target="_blank"
                                    class="text-blue-600 text-sm hover:underline">View File</a>
                                @endif
                                <a href=""
                                    class="text-indigo-600 text-sm hover:underline">Review</a> {{-- {{ route('client.contests.submission.show', [$contest, $entry]) }} --}}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-center text-gray-500">
                No submissions received yet.
            </div>
        </div>
        @endif

    </div>
</div>
@endsection