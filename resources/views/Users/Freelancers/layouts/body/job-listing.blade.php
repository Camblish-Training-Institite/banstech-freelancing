@extends('dashboards.freelancer.dashboard')

@section('body')

    <div class="container">
        <!-- Header -->
        <div class="header">

            
            <h1>Browse Opportunities</h1>


            <div class="search-bar">
                {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg> --}}
                <input type="text" placeholder="Search..." style="border: 1px solid #ccc; padding: 5px; border-radius: 4px;"/>
            </div>
            <span class="notification-icon">&#9881;</span>
        </div>

        @php
            $currentTab = 'jobs';
        @endphp

        <!-- Filters -->
        <div class="filters">
            <div>
                <label>Keywords</label>
                <input type="text" placeholder="e.g., UI/UX Design" />
            </div>
            <div>
                <label>Categories</label>
                <select>
                    <option value="all" disabled>All Categories</option>
                    @forelse ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @empty
                        
                    @endforelse
                </select>
            </div>
            <div>
                <label>Skills</label>
                <input type="text" placeholder="e.g., Figma, HTML" />
            </div>
            <div>
                <label>Budget (USD)</label>
                <input type="text" placeholder="Min amount" />
            </div>
            <button style="grid-column: span 4; justify-self: end;">
                <div class="flex items-center">
                    <img width="15" height="15" src="https://img.icons8.com/ios/50/filter--v1.png" alt="filter--v1" class="mr-2"/>
                Apply Filters
                </div>
            </button>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button onclick="switchTab('jobs')" id="jobs-tab"  class="{{ $currentTab === 'jobs' ? 'active' : '' }}" onclick="switchTab('jobs')">
                <i class="fas fa-list"></i> Jobs ({{ count($jobs) }})
            </button>
            <button onclick="switchTab('contests')" id="contests-tab" class="{{ $currentTab === 'contests' ? 'active' : '' }}" onclick="switchTab('contests')">
                <i class="fas fa-trophy"></i> Contests ({{ count($contests) }})
            </button>
        </div>

        <!-- Tab Contents -->
        <div id="jobs-content">
            @include('Users.Freelancers.listing-components._jobs', ['jobs' => $jobs])
        </div>

        <div id="contests-content" style="display: none;">
            @include('Users.Freelancers.listing-components._contests', ['contests' => $contests])
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Step 1: Hide both contents
            document.getElementById('jobs-content').style.display = 'none';
            document.getElementById('contests-content').style.display = 'none';

            // Step 2: Remove 'active' class from both buttons
            document.getElementById('jobs-tab').classList.remove('active');
            document.getElementById('contests-tab').classList.remove('active');

            // Step 3: Show selected content and highlight button
            if (tabName === 'jobs') {
                document.getElementById('jobs-content').style.display = 'block';
                document.getElementById('jobs-tab').classList.add('active');
            } else if (tabName === 'contests') {
                document.getElementById('contests-content').style.display = 'block';
                document.getElementById('contests-tab').classList.add('active');
            }

            // Optional: Update URL hash
            window.location.hash = '#' + tabName;
        }
    </script>

    @push('styles')
        <link rel="stylesheet" href="{{asset('pages_css/job_listing.css')}}">
    @endpush
@endsection