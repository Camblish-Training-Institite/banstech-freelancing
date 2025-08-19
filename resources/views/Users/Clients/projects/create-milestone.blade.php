@extends('dashboards.client.dashboard')

@section('body')
<div class="container mx-auto px-4 md:px-12">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Create a New Milestone for "{{ $project->job->title }}"</h2>

        <!-- add actionURL -->
        {{-- {{ route('projects.milestones.store', $project) }} --}}
        <form action="" method="POST">
            @csrf

            <!-- Title -->
            <div class="mb-6">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Milestone Title</label>
                <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('amount') border-red-500 @enderror" value="{{ old('title') }}" required >
                @error('title')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror" required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Due Date -->
            <div class="mb-6">
                <label for="due_date" class="block text-gray-700 text-sm font-bold mb-2">Milestone Due date</label>
                <input type="datetime" id="due_date" name="due_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('due_date') border-red-500 @enderror" value="{{ old('due_date') }}" required >
                @error('due_date')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div class="mb-6">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount ($)</label>
                <input type="number" id="amount" name="amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('amount') border-red-500 @enderror" value="{{ old('amount') }}" required min="1" step="0.01">
                @error('amount')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Create Milestone
                </button>
                <a href="{{ url()->previous() }}" class="inline-block align-baseline font-bold text-sm text-indigo-600 hover:text-indigo-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
