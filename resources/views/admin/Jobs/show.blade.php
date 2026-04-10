@extends('layouts.admin')

@section('content')
<div class="mx-auto py-10 sm:px-6 lg:px-8" style="width:80%;">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.jobs.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Job Details: #{{ $job->id }}</h1>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.jobs.edit', $job->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Edit Job</a>
            <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $job->title }}</h2>
                <div class="prose max-w-none text-gray-700">
                    <p>{{ $job->description }}</p>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Required Skills</h3>
                <div class="tags flex flex-wrap gap-2">
                    @php
                        $jobSkills = $job->skills ? explode(',',$job->skills) : [];
                    @endphp
                    @foreach($jobSkills as $skill)
                        <span
                            class="inline-block px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded-full">{{
                            trim($skill) }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Job Summary</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Budget</dt>
                        <dd class="text-lg font-bold text-gray-900">${{ number_format($job->budget, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $job->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="text-sm text-gray-900">{{ ucfirst($job->job_type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Deadline</dt>
                        <dd class="text-sm text-gray-900">{{ $job->deadline ? \Carbon\Carbon::parse($job->deadline)->format('M d, Y H:i') : 'No deadline set' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Client Information</h3>
                <div class="flex items-center space-x-3">
                    @include('components.user-avatar', ['user' => $job->user])
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $job->user->name ?? 'Unknown Client' }}</p>
                        <a href="{{ route('admin.users.show', $job->user->id) }}" class="text-xs text-indigo-600 hover:underline">View Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection