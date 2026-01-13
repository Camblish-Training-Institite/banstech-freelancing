@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-4">
        <div class="px-6 py-5 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">{{ $action }} project</h1>
            <p class="mt-1 text-sm text-gray-600">Fill in the details for the freelance project posting.</p>
        </div>

        <form method="POST" action="{{ $action === 'Create' ? route('admin.projects.store') : route('admin.projects.update', $project->id) }}" class="px-4 pb-4">
            @csrf
            @if($action === 'Edit')
                @method('PUT')
            @endif

            <div class="p-6 space-y-6">
                
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Please correct the errors below:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Job Section --}}
                    @if ($action == "Create")
                        <div>
                            <label for="job_id" class="block text-sm font-medium text-gray-700">Job</label>
                            <select name="job_id" id="job_id" class="mt-1 block p-2 w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="">-- Select Job --</option>
                                @foreach($jobs as $job)
                                    <option value="{{ $job->id }}" @selected(old('job_id', $project->job_id) == $job->id)>{{ $job->id ." - ". $job->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Freelancer Section --}}
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700">Freelancer</label>
                        <select name="user_id" id="user_id" class="mt-1 block p-2 w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <option value="">-- Select Freelancer --</option>
                            @foreach($freelancers as $freelancer)
                                <option value="{{ $freelancer->id }}" @selected(old('user_id', $project->user_id) == $freelancer->id)>{{ $freelancer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Basic Info Section --}}
                    <div>
                        <label for="agreed_amount" class="block text-sm font-medium text-gray-700">Agreed Amount ($)</label>
                        <input type="number" name="agreed_amount" id="agreed_amount" value="{{ old('agreed_amount') ?? $project->agreed_amount }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" step="0.01">
                    </div>
                    {{-- <div>
                        <label for="project_type" class="block text-sm font-medium text-gray-700">project Type</label>
                        <select name="project_type" id="project_type" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <option value="online" @selected(old('project_type', $project->project_type) == 'online')>Online / Remote</option>
                            <option value="physical" @selected(old('project_type', $project->project_type) == 'physical')>Physical / On-site</option>
                        </select>
                    </div> --}}

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block p-2 w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @foreach(['active', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ (old('status', $project->status ?? 'active') == $status) ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" id="category_id" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $project->category_id) == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                </div>

                {{-- End Date Section --}}
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') ?? ($project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '') }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                {{-- Project Manager Section --}}
                <div>
                    <label for="project_manager_id" class="block text-sm font-medium text-gray-700">Project Manager</label>
                    <select name="project_manager_id" id="project_manager_id" class="mt-1 block p-2 w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <option value="">-- Select Project Manager --</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" @selected(old('project_manager_id', $project->project_manager_id) == $manager->id)>{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex justify-end items-center space-x-4 rounded-lg">
                 <a href="{{ route('admin.projects.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $action }} project
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

