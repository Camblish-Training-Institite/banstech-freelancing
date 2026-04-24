@extends('dashboards.freelancer.dashboard')

@section('body')
    <div class="container">
        <div class="header">
            <h1>Browse Opportunities</h1>
            <div class="search-bar">
                <form method="GET" action="{{ route('jobs.listing') }}">
                    <input
                        type="text"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Search by job title or client"
                    />
                    @if (!empty($filters['type']))
                        <input type="hidden" name="type" value="{{ $filters['type'] }}">
                    @endif
                    @if (!empty($filters['category_id']))
                        <input type="hidden" name="category_id" value="{{ $filters['category_id'] }}">
                    @endif
                    @if (!empty($filters['funded']))
                        <input type="hidden" name="funded" value="{{ $filters['funded'] }}">
                    @endif
                    <button type="submit">Search Jobs</button>
                </form>
            </div>
            <span class="notification-icon">&#9881;</span>
        </div>

        @php
            $currentTab = 'jobs';
        @endphp

        <form method="GET" action="{{ route('jobs.listing') }}" class="filters">
            <div>
                <label for="search">Keywords</label>
                <input
                    id="search"
                    type="text"
                    name="search"
                    value="{{ $filters['search'] ?? '' }}"
                    placeholder="e.g., UI/UX Design or client name"
                />
            </div>

            <div>
                <label for="type">Job Type</label>
                <select id="type" name="type">
                    <option value="">All job types</option>
                    <option value="online" {{ ($filters['type'] ?? '') === 'online' ? 'selected' : '' }}>Online</option>
                    <option value="physical" {{ ($filters['type'] ?? '') === 'physical' ? 'selected' : '' }}>Physical</option>
                </select>
            </div>

            <div>
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ (string) ($filters['category_id'] ?? '') === (string) $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="funded">Funding Status</label>
                <select id="funded" name="funded">
                    <option value="">All jobs</option>
                    <option value="funded" {{ ($filters['funded'] ?? '') === 'funded' ? 'selected' : '' }}>Funded only</option>
                    <option value="not_funded" {{ ($filters['funded'] ?? '') === 'not_funded' ? 'selected' : '' }}>Not funded</option>
                </select>
            </div>

            <div class="filter-actions">
                <a href="{{ route('jobs.listing') }}" class="clear-link">
                    Clear
                </a>
                <button type="submit">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-filter" style="color: rgb(255, 255, 255);"></i>
                        Apply Filters
                    </div>
                </button>
            </div>
        </form>

        <div class="tabs">
            <button onclick="switchTab('jobs')" id="jobs-tab" class="{{ $currentTab === 'jobs' ? 'active' : '' }}">
                <i class="fas fa-list"></i> Jobs ({{ $jobs->total() }})
            </button>
            <button onclick="switchTab('contests')" id="contests-tab" class="{{ $currentTab === 'contests' ? 'active' : '' }}">
                <i class="fas fa-trophy"></i> Contests ({{ count($contests) }})
            </button>
        </div>

        <div id="jobs-content">
            @include('Users.Freelancers.listing-components._jobs', ['jobs' => $jobs])
        </div>

        <div id="contests-content" style="display: none;">
            @include('Users.Freelancers.listing-components._contests', ['contests' => $contests])
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            document.getElementById('jobs-content').style.display = 'none';
            document.getElementById('contests-content').style.display = 'none';

            document.getElementById('jobs-tab').classList.remove('active');
            document.getElementById('contests-tab').classList.remove('active');

            if (tabName === 'jobs') {
                document.getElementById('jobs-content').style.display = 'block';
                document.getElementById('jobs-tab').classList.add('active');
            } else if (tabName === 'contests') {
                document.getElementById('contests-content').style.display = 'block';
                document.getElementById('contests-tab').classList.add('active');
            }

            window.location.hash = '#' + tabName;
        }
    </script>

    @push('styles')
        <link rel="stylesheet" href="{{asset('pages_css/job_listing.css')}}">
    @endpush
@endsection
