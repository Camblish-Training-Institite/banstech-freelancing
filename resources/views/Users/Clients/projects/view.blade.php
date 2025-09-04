@extends('dashboards.client.dashboard')

@section('body')
<style>
    .back-link {
        color: #516aae;
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
    }
    .back-link:hover {
        text-decoration: underline;
    }
</style>

<div class="container mx-auto px-4 md:px-12">
    <div class="flex w-full justify-start">
        <a href="{{ route('client.projects.list') }}" class="back-link">← Back to Projects</a>
    </div>
    <div class="flex flex-wrap -mx-1 lg:-mx-4">

        <!-- Project Details -->
        <div class="my-1 px-1 w-full">
            <div class="bg-white rounded-lg shadow-md p-6">

                <div class="flex items-center justify-between w-full">
    <!-- Left side (avatar + text) -->
    <div class="flex items-center space-x-2">
        <div class="flex items-center justify-center rounded-full object-cover overflow-hidden" style="width: 4rem; height:4rem;">
            <img src="{{$project->job->user->profile? asset('storage/'.$project->job->user->profile->avatar) : "https://ui-avatars.com/api/?name=". $project->job->user->name . "&background=random&size=128" }}" 
                 alt="{{ $project->job->user->name }}">
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $project->job->title }}</h2>
            <p class="text-gray-600">Posted by {{ $project->job->user->name }}</p>
        </div>
    </div>

    <!-- Right side (buttons) -->
    <div class="flex space-x-2">
        <form action="{{ route('client.projects.cancel', $project->id) }}" method="POST">
            @csrf
        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-400">
            Cancel
        </button>
    </form>
    
     <form action="{{ route('client.projects.completed', $project->id) }}" method="POST">
        @csrf
        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-300">
            Completed
        </button>
     </form>
    </div>
</div>


                <div class="mt-6">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Budget</span>
                        <span class="text-lg font-bold text-gray-800">${{ number_format($project->agreed_amount, 2) }}</span>
                    </div>
                    @php
                        $endDate = new DateTime($project->job->deadline);
                    @endphp
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-gray-600">Ends in</span>
                        <span class="text-gray-800">{{ $endDate->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Tabs -->
        <div class="my-1 px-1 w-full">
            <div x-data="{ tab: 'details' }">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <a href="#" @click.prevent="tab = 'details'" :class="{ 'border-indigo-500
                         text-indigo-600': tab === 'details', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'details' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Details
                        </a>

                        <a href="#" @click.prevent="tab = 'payments'" :class="{ 'border-indigo-500 text-indigo-600':
                         tab === 'payments', 'border-transparent text-gray-500 hover:text-gray-700
                          hover:border-gray-300': tab !== 'payments' }" class="whitespace-nowrap py-4 
                          px-1 border-b-2 font-medium text-sm">
                            Payments
                        </a>

                        <a href="#" @click.prevent="{{ $project->job->job_funded ? 'tab = \'files\'' : '' }}" :class="{ 'border-indigo-500 text-indigo-600': tab === 'files', 'border-transparent
                         text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'files' }" 
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{$project->job->job_funded ? '' : 'cursor-not-allowed'}}">
                            Files
                        </a>

                        <a href="#" @click.prevent="{{ $project->job->job_funded ? 'tab = \'tasks\'' : '' }}" :class="{ 'border-indigo-500 text-indigo-600': tab === 'tasks', 'border-transparent
                         text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'tasks' }"
                          class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{$project->job->job_funded ? '' : 'cursor-not-allowed'}}">
                            Tasks
                        </a>
                    </nav>
                </div>

                <div class="py-6">
                    <div x-show="tab === 'details'">
                        @include('Users.Clients.projects.tabs.details')
                    </div>
                    <div x-show="tab === 'payments'">
                        @include('Users.Clients.projects.tabs.payments')
                    </div>
                    <div x-show="tab === 'files'">
                        @include('Users.Clients.projects.tabs.files')
                    </div>
                    <div x-show="tab === 'tasks'">
                        @include('Users.Clients.projects.tabs.tasks')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
