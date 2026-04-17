@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">{{ $action }} Milestone</h1>
            <p class="text-sm text-gray-600">project #{{ $project->id }} - {{ $project->job->title }}</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Please correct the errors below:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $action === 'Create' ? route('admin.projects.milestones.store', $project) : route('admin.milestones.update', $milestone->id) }}">
            @csrf
            @if($action === 'Edit') @method('PUT') @endif

            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Milestone Title</label>
                    <input type="text" name="title" value="{{ old('title', $milestone->title ?? '') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                {{-- Milestone Amount --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Milestone Amount</label>
                    <input type="number" name="amount" value="{{ old('amount', $milestone->amount ?? '') }}" min="0" step="0.01" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $milestone->description ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date', isset($milestone->due_date) ? \Carbon\Carbon::parse($milestone->due_date)->format('Y-m-d') : '') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block p-2 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="pending" {{ (old('status', $milestone->status ?? '') == 'pending') ? 'selected' : '' }}>Pending</option>
                            <option value="requested" {{ (old('status', $milestone->status ?? '') == 'requested') ? 'selected' : '' }}>Requested</option>
                            <option value="approved" {{ (old('status', $milestone->status ?? '') == 'approved') ? 'selected' : '' }}>Approved</option>
                            <option value="released" {{ (old('status', $milestone->status ?? '') == 'released') ? 'selected' : '' }}>Released</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex justify-end items-center space-x-4">
                <a href="{{ route('admin.projects.show', $project->id) }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md font-medium hover:bg-indigo-700">
                    {{ $action }} Milestone
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
