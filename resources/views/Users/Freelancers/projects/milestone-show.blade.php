@extends('dashboards.client.dashboard')

@section('body')
    <div class="flex flex-col h-full">
        <div class="flex w-full justify-start">
            <a href="{{ route('client.projects.list') }}" class="back-link">← Back to Projects</a>
        </div>
        
        <div class="flex flex-col h-full w-full mt-4 bg-gray-100 p-6 rounded-lg shadow-md">
            <div class="header flex flex-col mb-2 items-start justify-start w-full">
                <h1 class="text-2xl font-bold mb-2">Milestone Details</h1>
                <h2 class="text-lg font-meddium text-gray-400 mb-2">Created by: {{$milestone->project->job->user->name}}</h2>
            </div>
            <div class="content">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold mb-2">{{ $milestone->title }}</h2>
                    <p class="mb-4">{{ $milestone->description }}</p>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-600">Amount: R{{ number_format($milestone->amount, 2) }}</span>
                        <span class="text-gray-600">Status: 
                            @if($milestone->status == 'approved')
                                <span class="text-yellow-400 font-semibold">Pending</span>
                            @elseif($milestone->status == 'released')
                                <span class="text-green-500 font-semibold">Released</span>
                            @elseif($milestone->status == 'completed')
                                <span class="text-blue-500 font-semibold">Completed</span>
                            @elseif($milestone->status == 'canceled')
                                <span class="text-red-500 font-semibold">Canceled</span>    
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection