@extends('dashboards.client.dashboard')

@section('body')

    <div class="container">
        <!-- Header -->
        <div class="header">

            
            <h1>Browse Freelancers and Project Managers</h1>


            <div class="search-bar">
                {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg> --}}
                <input type="text" placeholder="Search..." style="border: 1px solid #ccc; padding: 5px; border-radius: 4px;"/>
            </div>
            <span class="notification-icon">&#9881;</span>
        </div>

        @php
            $currentTab = 'freelancers';

            $freelancers = \App\Models\User::where('user_type', 'freelancer-client')->whereNot('id', Auth::user()->id)->paginate(9);
            $projectManagers = \App\Models\User::where('user_type', 'project-manager')->whereNot('id', Auth::user()->id)->paginate(9);
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
                    {{-- <option value="all" disabled>All Categories</option>
                    @forelse ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @empty
                        
                    @endforelse --}}
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
            <button onclick="switchTab('freelancers')" id="freelancers-tab"  class="{{ $currentTab === 'freelancers' ? 'active' : '' }}" onclick="switchTab('freelancers')">
                <i class="fas fa-list"></i> Freelancers ({{ count($freelancers) }})
            </button>
            <button onclick="switchTab('PM')" id="PM-tab" class="{{ $currentTab === 'PM' ? 'active' : '' }}" onclick="switchTab('PM')">
                <i class="fas fa-trophy"></i> Project Managers ({{ count($projectManagers) }})
            </button>
        </div>

        <!-- Tab Contents -->
        <div id="freelancers-content">
            @include('Users.Clients.listing-components._freelancers', ['freelancers' => $freelancers])
        </div>

        <div id="PM-content" style="display: none;">
            @include('Users.Clients.listing-components._projectmanagers', ['projectManagers' => $projectManagers])
        </div>
    </div>

    <!-- Temporary background div for testing centering -->
    <div style="height:100vh; width:100%; justify-content:center; align-items:center; background-color: rgba(10, 10, 10,0.5)" class="absolute blur-lg top-0 left-0 hidden">
        <div class="absolute max-w-6xl rounded-md w-full shadow-lg" style="background-color: #ae92fe; width:100%; height:700px; top:50%; left:50%; transform: translate(-50%, -50%);"></div>
    </div>

    <script>
        function switchTab(tabName) {
            // Step 1: Hide both contents
            document.getElementById('freelancers-content').style.display = 'none';
            document.getElementById('PM-content').style.display = 'none';

            // Step 2: Remove 'active' class from both buttons
            document.getElementById('freelancers-tab').classList.remove('active');
            document.getElementById('PM-tab').classList.remove('active');

            // Step 3: Show selected content and highlight button
            if (tabName === 'freelancers') {
                document.getElementById('freelancers-content').style.display = 'block';
                document.getElementById('freelancers-tab').classList.add('active');
            } else if (tabName === 'PM') {
                document.getElementById('PM-content').style.display = 'block';
                document.getElementById('PM-tab').classList.add('active');
            }

            // Optional: Update URL hash
            window.location.hash = '#' + tabName;
        }
    </script>

    @push('styles')
        <link rel="stylesheet" href="{{asset('pages_css/job_listing.css')}}">
    @endpush
@endsection