@extends('layouts.client')

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
    html,
    body {
        margin: 0;
        padding: 0;
        height: 100%;
        width: 100%;
        overflow-x: hidden;
        /* Prevent body scroll if dashboard is designed to scroll internally */
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f0f2f5;
        /* Light grey background */
        display: flex;
        /* Use flexbox for body */
        min-height: 100vh;
        /* Ensure full viewport height */
        min-width: 100vw;
        /* Ensure full viewport width */
    }

    .dashboard-container {
        flex-grow: 1;
        display: flex;
        flex-grow: 1;
        /* Allows the dashboard to take up remaining space */
        flex-direction: column;
        /* Stack content vertically */
        height: 100vh;
        /* Full viewport height */
        width: 100%;
        background-color: #fff;
        /* Match the sidebar width to push the content */
        box-sizing: border-box;
        /* Include padding and border in the width calculation */
        overflow: hidden;
        /* Important for containing internal scrolls */
    }




    /* Main Content Area */
    .main-content {
        flex-grow: 1;
        /* Allows this section to take up remaining space */
        display: flex;
        flex-direction: column;
        width: 100%;
        overflow: hidden;
        /* Important to control scrolling within main content */
    }

    /* Header Styles */
    .header {
        background-color: #fff;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        flex-shrink: 0;
        /* Prevent header from shrinking */
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

    input:checked+.slider {
        background-color: #6a0dad;
        /* Purple */
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #6a0dad;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(20px);
        -ms-transform: translateX(20px);
        transform: translateX(20px);
    }

    .search-bar {
        position: relative;
    }

    .search-bar input {
        padding: 8px 10px 8px 30px;
        /* Left padding for icon */
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
        overflow-y: auto;
        /* Enable scrolling for content if it overflows */
    }

    .welcome-message {
        font-size: 1.5em;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
    }

    /* Navigation Tabs Styles */
    .main-nav-tabs {
        background-color: #2c2c2c;
        /* Black horizontal line/background */
        padding: 0 20px;
        /* Match left/right padding of dashboard-body */
        margin-bottom: 30px;
        /* Space below the tabs */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        /* Optional subtle shadow */
    }

    .main-nav-tabs ul {
        list-style: none;
        padding: 0;
        margin: 0;
        /* Remove default ul margin */
        display: flex;
    }

    .main-nav-tabs ul li {
        margin-right: 60px;
        /* Spacing between items */
        padding-bottom: 0;
        position: relative;
    }

    .main-nav-tabs ul li:last-child {
        margin-right: 0;
        /* No margin on the last item */
    }

    .main-nav-tabs ul li a {
        text-decoration: none;
        color: #fff;
        /* Make text white */
        font-weight: bold;
        padding: 20px 0;
        /* UPDATED: Increased vertical padding for taller black belt */
        display: block;
        transition: color 0.3s ease;
    }

    .main-nav-tabs ul li a:hover {
        color: #ccc;
        /* Slightly lighter white on hover */
    }

    .main-nav-tabs ul li.active a {
        color: #fff;
        /* Ensure active text remains white */
    }

    .main-nav-tabs ul li.active::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        /* Place at the very bottom of the li */
        width: 100%;
        height: 3px;
        background-color: #6a0dad;
        /* Purple indicator line */
        border-radius: 2px;
    }

    /* New style for the separate "Active projects (0)" heading */
    .active-projects-heading {
        font-size: 1.2em;
        /* Matches the size in the image */
        font-weight: bold;
        /* Often headings are bold */
        color: #333;
        margin-top: 30px;
        /* Space from the tabs above */
        margin-bottom: 20px;
        /* Space to the box below */
        /* This will naturally align left due to dashboard-body padding */
    }

    .active-projects-section {
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        text-align: center;
        /* Centers content within the box */
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        /* Centers items horizontally within the flex container */
        justify-content: flex-start;
        /* Centers items vertically within the flex container */
        min-height: 400px;
        /* Example height, adjust as needed */
        margin-bottom: 30px;
        /* Space below the box */
        /* The box itself will naturally align left within dashboard-body padding */
    }

    /* Removed the specific h3 rule for active-projects-section h3
           as the h3 is now a separate element. */

    .no-projects-message {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        text-align: center;
        /* Ensures the icon, text, and button are centered within this div */
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
        background-color: #6a0dad;
        /* Purple */
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

    .message-btn,
    .notification-float-btn {
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
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: background-color 0.3s ease;
    }

    .message-btn:hover,
    .notification-float-btn:hover {
        background-color: #555;
    }

    .notification-float-btn {
        position: relative;
        padding: 10px 15px;
        /* Adjust padding for icon only */
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
        border: 1px solid #333;
        /* To stand out */
    }

    /* Basic Responsiveness - adjust values as needed */
    @media (max-width: 992px) {
        .dashboard-container {
            flex-direction: column;
            /* Stack elements vertically */
            height: auto;
            /* Auto height when stacked, to allow scrolling */
            width: 100%;
            min-height: 100vh;
            /* Ensure it still takes at least full viewport height */
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
            gap: 10px 20px;
            /* Adjust gap for wrapped items */
            margin-bottom: 15px;
            /* Adjust margin if tabs wrap */
        }

        .main-nav-tabs ul li {
            margin-right: 0;
            /* Remove fixed margin-right when wrapping */
        }

        .main-nav-tabs ul li:last-child {
            margin-right: 0;
            /* Ensure no margin on the last item */
        }

        .main-nav-tabs ul li a {
            padding: 10px 0;
            /* Reduce padding when wrapped */
        }

        .floating-widgets {
            right: 20px;
            bottom: 20px;
        }
    }

    @media (max-width: 768px) {

        .header .dashboard-title {
            width: 100%;
            text-align: center;
            margin-bottom: 10px;
        }

        .header-right {
            flex-direction: column;
            align-items: center;
        }

        .become-client,
        .search-bar,
        .notification-icon {
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
    <div class="main-content">
        <header class="header">
            <div class="dashboard-title">Client Dashboard</div>
            <div class="header-right">
                <div class="become-client" onclick="window.location.href='{{ route('freelancer.dashboard') }}'">
                    <span>Become Freelancer</span>
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

                {{-- code below is supposed to display milestone requested  (might have to implement in the freelancer dashboard) --}}
                {{-- @foreach(auth()->user()->notifications->where('type', 'milestone_requested') as $notification)
                @include('dashboards.components.notification-item', ['notification' => $notification])
                @endforeach
                <a href="{{ route('notifications.markAsRead', $notification) }}">Mark as Read</a> --}}
        </header>

        <div class="dashboard-body">
            @yield('body')
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