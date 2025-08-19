@extends('Users.Clients.layouts.body.dashboard-body')

@section('active-tab')
    {{-- NEW: Separate heading for "Active projects (0)" --}}
    <div class="flex justify-between items-center mb-2">
        <h3 class="active-projects-heading">projects ({{$projects->count()}})</h3>
        <div class="mt-4 ml-4 flex-shrink-0">
            <a href="{{ route('client.jobs.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Add New Job
            </a>
        </div>
    </div>
    {{-- The large active-projects-section box, now without its own h3 inside --}}
    <div class="active-projects-section">
        @include('Users.Clients.projects.index', ['projects' => $projects])
    </div>
@endsection