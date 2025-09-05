@extends('dashboards.client.dashboard')

@section('body')
{{-- Welcome message is first --}}
<div class="flex items-center justify-between p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="welcome-message">Welcome back, {{Auth::User()->name}}! 👋</div>
    @php
        $jobs = Auth::user()->jobs;
    @endphp
    <div class="mt-2 ml-0 flex-shrink-0">
        <a href="{{ route('client.jobs.create') }}"
            class="inline-flex items-center justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" style="background-color: #3AAFA9; font-size: 20px;">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                    clip-rule="evenodd" />
            </svg>
            Post a Job
        </a>
    </div>
</div>
@include('Users.Clients.layouts.main-nav')

@yield('active-tab')
@endsection