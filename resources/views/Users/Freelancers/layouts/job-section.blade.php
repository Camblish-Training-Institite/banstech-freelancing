@extends('Users.Freelancers.layouts.body.dashboard-body')

@section('active-tab')
    <style>
        .projects-accent {
            background-color: #7A4D8B;
        }

        .projects-accent-text {
            color: #7A4D8B;
        }

        .projects-accent-soft {
            background-color: #f4ecf7;
        }

        .projects-panel {
            background: linear-gradient(180deg, #fbf8fc 0%, #ffffff 100%);
            border: 1px solid #eadff0;
            border-radius: 1rem;
            box-shadow: 0 18px 45px rgba(122, 77, 139, 0.08);
            overflow: hidden;
        }

        .active-projects-heading {
            color: #7A4D8B;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
    </style>

    {{-- NEW: Separate heading for "Active projects (0)" --}}
    <h3 class="active-projects-heading">Active projects ({{ $projects->count() }})</h3>

    {{-- The large active-projects-section box, now without its own h3 inside --}}
    <div class="active-projects-section projects-panel">
        @include('Users.Freelancers.projects.index')
    </div>
@endsection
