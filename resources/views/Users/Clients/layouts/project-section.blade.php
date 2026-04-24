@extends('Users.Clients.layouts.body.dashboard-body')

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
            text-transform: capitalize;
        }
    </style>

    {{-- NEW: Separate heading for "Active projects (0)" --}}
    <div class="flex justify-between items-center mb-2">
        <h3 class="active-projects-heading">projects ({{$projects->count()}})</h3>
        <div class="mt-4 ml-4 flex-shrink-0">
            <a href="{{ route('client.jobs.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white projects-accent hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-300">
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
    <div class="active-projects-section projects-panel">
        @include('Users.Clients.projects.index', ['projects' => $projects])
    </div>
@endsection
