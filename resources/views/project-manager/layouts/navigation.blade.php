{{-- <nav x-data="{ open: false }" class="bg-black dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-8 w-full h-full">
            <div class="flex flex-col items-start justify-start"
                style="display:flex; align-items: flex-start; padding:0rem 1rem;"
            >
                {{-- <!-- Logo -->
                <div class="flex items-center border border-blue-500">
                    <a href="{{ route('pm.dashboard') }}">
                        <img class="h-9 w-9" src="{{asset('storage/pictures/logo.png') }}" alt="">
                    </a>
                </div> --}}

            {{-- <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            {{-- </div> --}}

            {{--<!-- Hamburger -->
            <div class="flex w-full items-center py-2 px-4">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 w-full rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-600 dark:hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div> 
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="" class="">
            {{-- <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('pm.dashboard')" :active="request()->routeIs('pm.dashboard')">
                    {{ __('pm.Dashboard') }}
                </x-responsive-nav-link>
            </div> 

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-200 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('pm.profile.edit')" :active="request()->routeIs('pm.profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
            </div>
        </div>

        <div class=""
            style="width:100%; "
        >
            <x-responsive-nav-link :href="route('pm.dashboard')" :active="request()->routeIs('pm.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <div class=""
            style="width:100%; "
        >
            <x-responsive-nav-link :href="route('pm.projects')" :active="request()->routeIs('pm.projects')">
                {{ __('All Projects') }}
            </x-responsive-nav-link>
        </div>

        <div class=""
            style="width:100%; "
        >
            <x-responsive-nav-link :href="route('pm.inbox')" :active="request()->routeIs('pm.inbox')">
                {{ __('Inbox') }}
            </x-responsive-nav-link>
        </div>

        <div
            style="width: 100%;"
        >
            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>

        
    </div>
</nav> --}}

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
        justify-content: space-evenly
    }

    .user-avatar{
        height: 3rem;
        width: 3rem;
        margin-right:0.5rem;
    }
</style>

<aside class="sidebar">
    <div> {{-- This div now wraps all top content to be pushed up --}}
        <div class="user-details">
            <div class="user-avatar rounded-full bg-white">
                <img src="" alt="">
            </div>
            <div>
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
        </div>
        <div class="sidebar-header">Main Menu</div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="{{route('pm.dashboard')}}"><i class="fas fa-briefcase"></i> <span>Dashboard</span></a></li>
                <li><a href="{{route('pm.profile.edit')}}"><i class="fas fa-briefcase"></i> <span>Profile</span></a></li>
                <li><a href="{{route('pm.inbox')}}"><i class="fas fa-inbox"></i> <span>Management Requests</span></a></li>
                <li><a href="{{route('pm.projects')}}"><i class="fas fa-user-circle"></i> <span>Managed Projetcs</span></a></li>
                <li><a href="#"><i class="fas fa-hands-helping"></i> <span>Support & Assistance</span></a></li>
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

