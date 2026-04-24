@extends('Users.Freelancers.layouts.body.dashboard-body')

@section('active-tab')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Saved Jobs</h2>
            <p class="mt-1 text-sm text-gray-600">Quickly revisit opportunities you bookmarked for later.</p>
        </div>
        <a href="{{ route('jobs.listing') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Browse More Jobs
        </a>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        @include('Users.Freelancers.listing-components._jobs', ['jobs' => $jobs])
    </div>
</div>
@endsection
