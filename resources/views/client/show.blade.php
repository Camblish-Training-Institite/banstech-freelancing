@extends('layouts.app')

@section('content')
<div class="container max-w-xl mx-auto">
    <h2 class="font-bold text-2xl mb-6">Job Details</h2>

    <div class="bg-white shadow-md rounded p-6 space-y-4">

        <!-- Title -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" value="{{ $job->title }}" readonly
                   class="mt-1 p-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed">
        </div>

        <!-- Description -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea readonly
                      class="mt-1 p-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed"
                      rows="4">{{ $job->description }}</textarea>
        </div>

        <!-- Budget -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Budget</label>
            <input type="text" value="${{ number_format($job->budget, 2) }}" readonly
                   class="mt-1 p-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed">
        </div>

        <!-- Status -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <input type="text" value="{{ $job->status }}" readonly
                   class="mt-1 p-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed">
        </div>

        <!-- Deadline -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Deadline</label>
            <input type="text" value="{{ \Carbon\Carbon::parse($job->deadline)->format('Y-m-d') }}" readonly
                   class="mt-1 p-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed">
        </div>

        <!-- Skills -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Skills</label>
            <input type="text"
                   value="{{ is_array($job->skills) && count($job->skills) > 0 ? implode(', ', $job->skills) : 'No skills listed.' }}"
                   readonly
                   class="mt-1 p-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed">
        </div>        
        

        <!-- Back Button -->
        <div class="pt-2">
            <a href="{{ route('jobs.index') }}"
               class="inline-block btn btn-success hover:bg-blue-700 text-whie py-1 px-4 ">
                Back
            </a>
        </div>

    </div>
</div>
@endsection
