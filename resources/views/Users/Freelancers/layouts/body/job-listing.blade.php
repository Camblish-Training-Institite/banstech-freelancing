@extends('dashboards.freelancer.dashboard')

@section('body')
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #333;
        }
        .header .search-bar {
            width: 300px;
            height: 40px;
            border: 1px solid #ccc;
            border-radius: 4px;
            display: flex;
            align-items: center;
            padding: 0 10px;
        }
        .header .search-bar input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 16px;
        }
        .header .search-bar svg {
            margin-right: 10px;
            color: #888;
        }
        .header .notification-icon {
            font-size: 24px;
            color: #888;
        }

        /* Filters Section */
        .filters {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        .filters label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .filters input[type="text"],
        .filters select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .filters button {
            background-color: #6a51ae;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .filters button:hover {
            background-color: #5b419e;
        }

        /* Tabs Section */
        .tabs {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .tabs button {
            padding: 10px 20px;
            border: none;
            border-bottom: 3px solid transparent;
            font-size: 16px;
            cursor: pointer;
            transition: border-bottom-color 0.3s ease;
        }
        .tabs button.active {
            border-bottom: 3px solid #6a51ae;
        }

        /* Opportunity Cards */
        .opportunity-card {
            display: flex;
            align-items: flex-start;
            justify-content: space-between; 
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .opportunity-card h2 {
            font-size: 20px;
            color: #6a51ae;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .opportunity-card p {
            font-size: 16px;
            color: #666;
            max-height: 20px;
            margin-bottom: 15px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .opportunity-card .tags {
            display: flex;
            gap: 10px;
        }
        .opportunity-card .tags span {
            background-color: rgba(81, 174, 106, 0.2);
            color: ;
            padding: 3px 8px;
            border-radius: 1rem;
            font-size: 14px;
        }
        .opportunity-card .price {
            font-size: 18px;
            color: #333;
            font-weight: bolder;
        }
        .opportunity-card .proposals {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .send-proposal {
            /* border: solid #6a51ae 2px; */
            background-color: #516aae;
            color: white;
            margin-top: 0.25rem 0;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .send-proposal:hover {
            background-color: #303e66;
            color: white;
        }
        .view-job {
            background-color: #6a51ae;
            color: #fff;
            margin-top: 0.25rem 0;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .view-job:hover {
            background-color: #402c70;
            color: white;
        }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .pagination button {
            background-color: #fff;
            color: #6a51ae;
            border: 1px solid #ccc;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .pagination button.active {
            background-color: #6a51ae;
            color: white;
            border: none;
        }

        .content-block {
            display: flex;
            flex-direction: column;
            flex: 2;
            align-items: flex-start;
        }

        .price-and-proposals-block{
            display: flex;
            flex-direction: column;
            flex: 1;
            align-items: flex-end;
            justify-content:space-evenly;
        }

        .buttons-block {
            display: flex;
            gap: 10px;
        }


    </style>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Browse Opportunities</h1>
            <div class="search-bar">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg>
                <input type="text" placeholder="Search..." />
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
                <label>Type</label>
                <select>
                    <option value="all">All Types</option>
                    <option value="project">Projects</option>
                    <option value="contest">Contests</option>
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
            <button>Apply Filters</button>
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
@endsection