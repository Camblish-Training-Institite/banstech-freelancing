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

<div class="container mx-auto px-2 md:px-12">
    <div class="flex w-full justify-start">
        <a href="{{ route('client.projects.list') }}" class="back-link">← Back to Projects</a>
    </div>
    {{-- <div class="flex flex-wrap -mx-1 lg:-mx-4"> --}}

    <!-- Project Details -->
    <div class="my-1 px-1 w-full">
        <div class="rounded-lg shadow-md p-6 pt-2" style="background-color:#2c2c2c;">
            <div class="flex items-center justify-between w-full">
                <!-- Left side (avatar + text) -->
                <div class="flex items-center space-x-2">
                    <div class="flex flex-col items-center justify-center mt-2" style="width: 4rem; height:4rem;">
                        {{-- <img src="{{$project->job->user->profile? asset('storage/'.$project->job->user->profile->avatar) : "https://ui-avatars.com/api/?name=". $project->job->user->name . "&background=random&size=128" }}" 
                            alt="{{ $project->job->user->name }}"> --}}
                        @include('components.user-avatar', ['user' => $project->job->user, 'width' => '4rem', 'height' => '4rem'])
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-200">{{ $project->job->title }}</h2>
                        <p class="text-gray-400">Posted by {{ $project->job->user->name }}</p>
                    </div>
                </div>

                <!-- Right side (buttons) -->
                <div class="flex space-x-2">
                    @if ($project->status == "active")


                        <!-- Cancel Project Button -->
                        {{-- <form action="{{ route('client.projects.cancel', $project->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-400">
                                Cancel
                            </button>
                        </form> --}}

                        <!-- logic to check if all milestones are completed -->
                        @php
                            $total = $project->milestones->count();
                            $completed = $project->milestones->where('status', 'released')
                                ->count();
                        @endphp 
                    
                        <!-- End Project Button -->
                        <form action="{{ route('client.projects.completed', $project->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="{{ ($total == $completed) ? 'bg-green-600 hover:bg-green-300' : 'hidden' }} text-white px-3 py-1 rounded">
                                End Project
                            </button>
                        </form>
                    @else
                        <div class="">
                            <span class="px-3 py-1 rounded-full bg-gray-200 text-gray-700 font-semibold">
                                {{ ucfirst($project->status) }}
                            </span>    
                        </div>     
                    @endif
                </div>
            </div>


                    <div class="mt-6">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Budget</span>
                            <span class="text-lg font-bold text-gray-200">R{{ number_format($project->agreed_amount, 2) }}</span>
                        </div>
                        @php
                            $endDate = new DateTime($project->job->deadline);
                        @endphp
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-gray-400">Ends in</span>
                            <span class="text-gray-200">{{ $endDate->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
                    @if ($project->status == "completed")
                        <div class="flex w-full py-2 mb-2 justify-end space-x-2">
                            <a href="{{route('client.reviews.create', $project)}}" class="flex rounded-md text-white bg-indigo-600 hover:bg-indigo-300 {{ ($project->user->reviews->count() > 0) ? 'hidden' : '' }}" style="padding: 0.45rem 1rem;">Review Freelancer</a>
                            <a href="{{route('client.reviews.pm.create', $project)}}" class="flex rounded-md text-black bg-gray-200 hover:bg-gray-300 {{ $project->project_manager_id ? '' : 'hidden'}}" style="padding: 0.45rem 1rem;">Review Project Manager</a>
                        </div>
                    @endif
            </div>

            <!-- Project Tabs -->
            <div class="my-1 px-1 w-full">
                <div x-data="{ tab: 'details' }">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <a href="#" @click.prevent="tab = 'details'"
                                :class="{ 'border-indigo-500 text-indigo-600': tab === 'details', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'details' }"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Details
                            </a>

                            <a href="#" @click.prevent="tab = 'payments'"
                                :class="{ 'border-indigo-500 text-indigo-600': tab === 'payments', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'payments' }"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Milestones
                            </a>

                            <a href="#" @click.prevent="tab = 'files'"
                                :class="{ 'border-indigo-500 text-indigo-600': tab === 'files', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'files' }"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $project->job->job_funded ? '' : 'hidden' }}">
                                Files
                            </a>

                            <a href="#" @click.prevent="tab = 'tasks'"
                                :class="{ 'border-indigo-500 text-indigo-600': tab === 'tasks', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'tasks' }"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $project->job->job_funded ? '' : 'hidden' }}">
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
</div>
@endsection
