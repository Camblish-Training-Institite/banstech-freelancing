@extends('layouts.app')

@section('content')

    @if ($errors->any())
        <div class="mb-4 font-medium text-sm text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Existing status and error messages -->
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-red-600">
            {{ session('error') }}
        </div>
    @endif

    <style>
        /* Essential for full screen coverage and removing all white space */
        html, body {
            margin: 0;
            padding: 0;
            height: 100%; /* Ensure html and body take full height */
            width: 100%;  /* Ensure html and body take full width */
            overflow: hidden; /* Prevent body scroll if dashboard is designed to scroll internally */
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5; /* Light grey background - though it should be fully covered */
            display: flex; /* Use flexbox for body */
            min-height: 100vh; /* Ensure full viewport height */
        }

        .dashboard-container {
            display: flex;
            width: 100vw; /* Take full viewport width */
            height: 100vh; /* Take full viewport height */
            max-width: none; /* Remove any max-width limits */
            box-shadow: none; /* Remove shadow for a clean full-screen look */
            background-color: #fff; /* Main background, should be covered by sections */
            margin: 0; /* No external margins */
            border-radius: 0; /* No rounded corners */
            overflow: hidden; /* Important for containing internal scrolls and preventing external overflow */
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px; /* Fixed width for the sidebar */
            background-color: #2c2c2c; /* Dark background */
            color: #f0f0f0;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Re-added to push content to top/bottom */
            flex-shrink: 0; /* Prevent sidebar from shrinking */
            overflow-y: auto; /* Allow sidebar to scroll if content overflows */
        }

        .sidebar-header {
            text-transform: uppercase;
            font-size: 0.8em;
            color: #888;
            padding: 15px 20px 5px;
            margin-top: 15px;
        }

        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav ul li a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #f0f0f0;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .sidebar-nav ul li a:hover {
            background-color: #444;
        }

        .sidebar-nav ul li a i {
            margin-right: 10px;
            font-size: 1.1em;
        }

        /* New styles for the pushed-down Logout link */
        .sidebar-logout {
            padding: 20px; /* Matches previous footer padding, creates space */
            border-top: 1px solid #444; /* Optional: Adds a separator line */
            margin-bottom: 40px; /* Pushes it up further from the very bottom */
        }

        .sidebar-logout a {
            display: flex;
            align-items: center;
            padding: 12px 0px; /* Adjusted padding to fit into new container */
            color: #f0f0f0;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .sidebar-logout a:hover {
            background-color: #444; /* Match hover of other menu items */
        }

        .sidebar-logout a i {
            margin-right: 10px;
            font-size: 1.1em;
        }


        /* Main Content Area */
        .main-content {
            flex-grow: 1; /* Allows this section to take up remaining space */
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Important to control scrolling within main content */
        }

        /* Header Styles */
        .header {
            background-color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            flex-shrink: 0; /* Prevent header from shrinking */
        }

        .dashboard-title {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .become-client {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Toggle Switch (CSS from W3Schools example, adapted) */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 20px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #6a0dad; /* Purple */
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #6a0dad;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(20px);
            -ms-transform: translateX(20px);
            transform: translateX(20px);
        }

        .search-bar {
            position: relative;
        }

        .search-bar input {
            padding: 8px 10px 8px 30px; /* Left padding for icon */
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 0.9em;
            outline: none;
        }

        .search-bar .fas.fa-search {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .notification-icon {
            font-size: 1.2em;
            color: #555;
            cursor: pointer;
        }

        /* Dashboard Body */
        .dashboard-body {
            padding: 20px;
            flex-grow: 1;
            background-color: #fdfdfd;
            overflow-y: auto; /* Enable scrolling for content if it overflows */
        }

        .welcome-message {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        /* Navigation Tabs Styles */
        .main-nav-tabs {
            background-color: #2c2c2c; /* Black horizontal line/background */
            padding: 0 20px; /* Match left/right padding of dashboard-body */
            margin-bottom: 30px; /* Space below the tabs */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Optional subtle shadow */
        }

        .main-nav-tabs ul {
            list-style: none;
            padding: 0;
            margin: 0; /* Remove default ul margin */
            display: flex;
        }

        .main-nav-tabs ul li {
            margin-right: 60px; /* Spacing between items */
            padding-bottom: 0;
            position: relative;
        }

        .main-nav-tabs ul li:last-child {
            margin-right: 0; /* No margin on the last item */
        }

        .main-nav-tabs ul li a {
            text-decoration: none;
            color: #fff; /* Make text white */
            font-weight: bold;
            padding: 20px 0; /* UPDATED: Increased vertical padding for taller black belt */
            display: block;
            transition: color 0.3s ease;
        }

        .main-nav-tabs ul li a:hover {
            color: #ccc; /* Slightly lighter white on hover */
        }

        .main-nav-tabs ul li.active a {
            color: #fff; /* Ensure active text remains white */
        }

        .main-nav-tabs ul li.active::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0; /* Place at the very bottom of the li */
            width: 100%;
            height: 3px;
            background-color: #6a0dad; /* Purple indicator line */
            border-radius: 2px;
        }

        /* New style for the separate "Active projects (0)" heading */
        .active-projects-heading {
            font-size: 1.2em; /* Matches the size in the image */
            font-weight: bold; /* Often headings are bold */
            color: #333;
            margin-top: 30px; /* Space from the tabs above */
            margin-bottom: 20px; /* Space to the box below */
            /* This will naturally align left due to dashboard-body padding */
        }

        .active-projects-section {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center; /* Centers content within the box */
            display: flex;
            flex-direction: column;
            align-items: center; /* Centers items horizontally within the flex container */
            justify-content: center; /* Centers items vertically within the flex container */
            min-height: 400px; /* Example height, adjust as needed */
            margin-bottom: 30px; /* Space below the box */
            /* The box itself will naturally align left within dashboard-body padding */
        }

        /* Removed the specific h3 rule for active-projects-section h3
           as the h3 is now a separate element. */

        .no-projects-message {
            text-align: center; /* Ensures the icon, text, and button are centered within this div */
        }

        .no-projects-message .icon-box {
            font-size: 6em;
            color: #ccc;
            margin-bottom: 20px;
        }

        .no-projects-message p {
            font-size: 1.2em;
            color: #555;
            margin-bottom: 30px;
        }

        .find-opportunities-btn {
            background-color: #6a0dad; /* Purple */
            color: #fff;
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .find-opportunities-btn:hover {
            background-color: #540aa8;
        }

        /* Floating Widgets (Messages/Notifications) */
        .floating-widgets {
            position: fixed;
            bottom: 30px;
            right: 50px;
            display: flex;
            gap: 15px;
            z-index: 1000;
        }

        .message-btn, .notification-float-btn {
            background-color: #333;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: background-color 0.3s ease;
        }

        .message-btn:hover, .notification-float-btn:hover {
            background-color: #555;
        }

        .notification-float-btn {
            position: relative;
            padding: 10px 15px; /* Adjust padding for icon only */
        }

        .notification-float-btn .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            width: 15px;
            height: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.7em;
            border: 1px solid #333; /* To stand out */
        }

        /* Basic Responsiveness - adjust values as needed */
        @media (max-width: 992px) {
            .dashboard-container {
                flex-direction: column; /* Stack elements vertically */
                height: auto; /* Auto height when stacked, to allow scrolling */
                min-height: 100vh; /* Ensure it still takes at least full viewport height */
            }

            .sidebar {
                width: 100%; /* Full width for sidebar when stacked */
                border-radius: 0; /* No border-radius when stacked */
                box-shadow: none; /* No shadow when stacked */
            }

            .sidebar-logout {
                margin-top: 15px; /* Adjust margin for mobile */
                padding: 10px 20px; /* Adjust padding for mobile */
                border-top: none; /* No top border when stacked on mobile */
            }
            .sidebar-logout a {
                justify-content: center; /* Center content when stacked */
            }
            .sidebar-logout a span {
                display: none; /* Hide text on small screens if desired */
            }


            .header {
                flex-wrap: wrap;
                gap: 10px;
                padding-bottom: 10px;
            }

            .search-bar {
                flex-grow: 1;
                order: 1;
                margin-top: 10px;
            }
            .header-right {
                width: 100%;
                justify-content: flex-end;
            }
             .become-client {
                order: 2;
            }
            .notification-icon {
                order: 3;
            }

            .main-nav-tabs ul {
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px 20px; /* Adjust gap for wrapped items */
                margin-bottom: 15px; /* Adjust margin if tabs wrap */
            }

            .main-nav-tabs ul li {
                margin-right: 0; /* Remove fixed margin-right when wrapping */
            }

            .main-nav-tabs ul li:last-child {
                margin-right: 0; /* Ensure no margin on the last item */
            }

            .main-nav-tabs ul li a {
                padding: 10px 0; /* Reduce padding when wrapped */
            }

            .floating-widgets {
                right: 20px;
                bottom: 20px;
            }
        }

        @media (max-width: 768px) {
            .sidebar-nav ul li a {
                justify-content: center;
                padding: 10px 0;
            }
            .sidebar-nav ul li a span {
                display: none;
            }
            .sidebar-header {
                text-align: center;
            }

            .sidebar-logout a {
                 justify-content: center;
            }
            .sidebar-logout a span {
                display: none;
            }

            .header .dashboard-title {
                width: 100%;
                text-align: center;
                margin-bottom: 10px;
            }
            .header-right {
                flex-direction: column;
                align-items: center;
            }
            .become-client, .search-bar, .notification-icon {
                width: 100%;
                justify-content: center;
                margin-bottom: 10px;
            }
             .search-bar input {
                width: calc(100% - 40px);
            }
             .search-bar .fas.fa-search {
                left: 15px;
            }
        }

        @media (max-width: 480px) {
            .dashboard-body {
                padding: 15px;
            }
            .welcome-message {
                font-size: 1.3em;
            }
            .no-projects-message .icon-box {
                font-size: 4em;
            }
            .no-projects-message p {
                font-size: 1em;
            }
            .find-opportunities-btn {
                padding: 10px 20px;
                font-size: 0.9em;
            }
            .floating-widgets {
                flex-direction: column;
                align-items: flex-end;
                right: 15px;
                bottom: 15px;
                gap: 10px;
            }
        }
    </style>

    <div class="dashboard-container">
        <aside class="sidebar">
            <div> {{-- This div now wraps all top content to be pushed up --}}
                <div class="sidebar-header">Main Menu</div>
                <nav class="sidebar-nav">
                    <ul>
                        <li><a href="#"><i class="fas fa-briefcase"></i> <span>Services</span></a></li>
                        <li><a href="#"><i class="fas fa-tasks"></i> <span>Project Manager</span></a></li>
                        <li><a href="#"><i class="fas fa-inbox"></i> <span>Inbox</span></a></li>
                        <li><a href="#"><i class="fas fa-dollar-sign"></i> <span>Earnings</span></a></li>
                    </ul>
                    <div class="sidebar-header">Preference</div>
                    <ul>
                        <li><a href="#"><i class="fas fa-user-circle"></i> <span>Profile Management</span></a></li>
                        <li><a href="#"><i class="fas fa-hands-helping"></i> <span>Support & Assistance</span></a></li>
                        <li><a href="#"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
                    </ul>
                </nav>
            </div>
            {{-- NEW: Log Out button now in its own div, pushed to bottom --}}
            <div class="sidebar-logout">
                <a href="#"><i class="fas fa-sign-out-alt"></i> <span>Log Out</span></a>
            </div>
        </aside>

        <div class="main-content">
            <header class="header">
                <div class="dashboard-title">Freelancer Dashboard</div>
                <div class="header-right">
                    <div class="become-client">
                        <span>Become Client</span>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="search-bar">
                        <input type="text" placeholder="Search">
                        <i class="fas fa-search"></i>
                    </div>
                    <i class="fas fa-bell notification-icon"></i>
                    {{-- The <div class="user-avatar"> block was previously here and has been removed. --}}
                </div>
            </header>

            <div class="dashboard-body">
                {{-- Welcome message is first --}}
                <div class="welcome-message">Welcome back, John! 👋</div>

                {{-- Navigation tabs are second --}}
                <nav class="main-nav-tabs">
                    <ul>
                        <li class="active"><a href="#">My Jobs</a></li>
                        <li><a href="#">Proposals</a></li>
                        <li><a href="#">Contest</a></li>
                        <li><a href="#">Completed Projects</a></li>
                        <li><a href="#">Contest</a></li>
                    </ul>
                </nav>

                {{-- NEW: Separate heading for "Active projects (0)" --}}
                <h3 class="active-projects-heading">Active projects (0)</h3>

                {{-- The large active-projects-section box, now without its own h3 inside --}}
                <div class="active-projects-section">
                    <div class="no-projects-message">
                        <div class="icon-box">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <p>No Available Projects</p>
                        <button class="find-opportunities-btn">Find New Opportunities</button>
                    </div>
                </div>

            </div>
        </div>

        {{-- The entire <div class="profile-card"> block was previously here and has been removed. --}}

        <div class="floating-widgets">
            <button class="message-btn">Messages</button>
            <button class="notification-float-btn">
                <i class="fas fa-bell"></i>
                <span class="badge"></span>
            </button>
        </div>
    </div>
@endsection