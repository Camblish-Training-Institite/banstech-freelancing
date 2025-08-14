
@extends('Users.Clients.layouts.body.dashboard-body')


@section('active-tab')
    {{-- NEW: Separate heading for "Active projects (0)" --}}
    {{-- <h3 class="active-projects-heading">Active Contests (0)</h3> --}}

    {{-- The large active-projects-section box, now without its own h3 inside --}}

    {{-- <div class="active-projects-section">
        <div class="no-projects-message">
            <div class="icon-box">
                <i class="fas fa-box-open"></i>
            </div>
            <p>No Available Contests</p> --}}
            {{-- <button class="find-opportunities-btn">Find New Opportunities</button> --}}
        {{-- </div>
    </div> --}}
    @include('Users.Clients.contests.index')


@endsection