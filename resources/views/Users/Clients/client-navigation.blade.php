<style>
    /* Sidebar Styles */
    .sidebar {
        background-color: #2c2c2c; /* Dark background */
        color: #f0f0f0;
        padding: 20px 1rem;;
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

    /* Basic Responsiveness - adjust values as needed */
    @media (max-width: 992px) {
        .sidebar {
            /* width: 100%; */
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

        .sidebar-logout button {
            justify-content: center;
        }

        .sidebar-logout:hover {
            background-color: #444
        }
        .sidebar-logout button span {
            display: none;
        }

        
    }

    .user-details{
        display: flex;
        font-size: 0.8em;
        color: #888;
        padding: 0.125rem 1rem 0.5rem;
        margin-top: 15px;
        align-items: center;
        justify-content: space-evenly;
    }

    .user-avatar{
        max-height: 3rem;
        max-width: 3rem;
        margin-right:0.5rem;
        overflow: hidden;
    }
</style>

@php
    $user = Auth::user();
@endphp

<aside class="sidebar">
    <div> {{-- This div now wraps all top content to be pushed up --}}
        <div class="user-details">
            {{-- <div class="user-avatar rounded-full bg-white ">
                <img width="100%" src="{{ $user->profile ? asset('storage/' . $user->profile->avatar) : 'https://ui-avatars.com/api/?name='. $user->name .'&background=random&size=128' }}" alt="{{$user->name}}">
            </div> --}}
            @include('components.user-avatar', ['user' => $user, 'width' => '3rem', 'height' => '3rem'])
            <div>
                <div class="font-medium text-base text-gray-200">{{ $user->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ $user->email }}</div>
            </div>
        </div>
        <div class="sidebar-header">Main Menu</div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="{{ route('client.dashboard') }}"><i class="fas fa-briefcase"></i> <span>Dashboard</span></a></li>
                <li><a href="#"><i class="fas fa-briefcase"></i> <span>Services</span></a></li>
                <li><a href="{{ route(config('chatify.routes.prefix'), ['userType' => 'client']) }}"><i class="fas fa-inbox"></i> <span>Inbox</span></a></li>
                <li><a href="{{ route('client.find.users.index') }}"><i class="fas fa-tasks"></i> <span>Explore Freelancers</span></a></li>
                <li><a href="{{route('billing')}}"><i class="fas fa-dollar-sign"></i> <span>Billing</span></a></li>
            </ul>
            <div class="sidebar-header">Preference</div>
            <ul>
                <li><a href="{{ route('client.profile.edit') }}"><i class="fas fa-user-circle"></i> <span>Profile Management</span></a></li>
                <li><a href="#"><i class="fas fa-hands-helping"></i> <span>Support & Assistance</span></a></li>
                <li><a href="#"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
            </ul>
        </nav>
    </div>
    {{-- NEW: Log Out button now in its own div, pushed to bottom --}}
    <div class="sidebar-logout">
        {{-- <a href="#"><i class="fas fa-sign-out-alt"></i> <span>Log Out</span></a> --}}
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf

            <button type="submit" class="fas fa-sign-out-alt w-full">Log out</button>
        </form>
    </div>
</aside>
