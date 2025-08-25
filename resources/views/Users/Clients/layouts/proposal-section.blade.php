@extends('Users.Clients.layouts.body.dashboard-body')

@section('active-tab')
    {{-- NEW: Separate heading for "Active projects (0)" --}}
    <h3 class="active-projects-heading">All Proposals</h3>

    {{-- The large active-projects-section box, now without its own h3 inside --}}
    <div class="active-projects-section">
        @if (request()->routeIs('client.proposals.list'))
            @include('Users.Clients.proposals.index', ['jobs' => $jobs])
        @elseif(request()->routeIs('client.proposals.job.show'))
            @include('Users.Clients.proposals.show', ['job' => $job])
        @endif
    </div>
@endsection