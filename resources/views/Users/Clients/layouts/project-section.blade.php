@extends('Users.Clients.layouts.body.dashboard-body')

@section('active-tab')
    {{-- NEW: Separate heading for "Active projects (0)" --}}
    <div class="flex justify-between items-center mb-2">
        <h3 class="active-projects-heading">projects ({{$projects->count()}})</h3>
        <div class="mt-4 ml-4 flex-shrink-0">
            <a href="{{ route('client.jobs.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                Add New Job
            </a>
        </div>
    </div>
    {{-- The large active-projects-section box, now without its own h3 inside --}}
    <div class="active-projects-section">
        @include('Users.Clients.projects.index', ['projects' => $projects])
    </div>
@endsection