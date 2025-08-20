@extends('Users.Freelancers.layouts.body.dashboard-body')

@section('active-tab')
    {{-- NEW: Separate heading for "Active projects (0)" --}}
    <h3 class="active-projects-heading">All Proposals (0)</h3>

    {{-- The large active-projects-section box, now without its own h3 inside --}}
    <div class="active-projects-section">
        @include('Users.Freelancers.proposals.index', ['proposals' => $proposals])
    </div>
@endsection