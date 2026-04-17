{{-- resources/views/layouts/partials/provider-navigation.blade.php --}}
<style>
    /* Custom complimentary color for the navigation bar */
    .bg-complimentary-nav {
        background-color: var(--theme-nav-surface); /* Complimentary color #68E4AD adjusted for nav */
    }
    /* Darker shade for the active link background */
    .bg-complimentary-nav-darker {
        background-color: var(--theme-nav-active-surface); /* A darker shade of #E4AD68 */
    }

    .admin-nav-link {
        color: var(--theme-nav-text);
    }

    .admin-nav-link:hover {
        background-color: var(--theme-nav-hover);
        color: var(--theme-nav-text);
    }

    .admin-nav-link-active {
        background-color: var(--theme-nav-active-surface);
        color: var(--theme-nav-active-text);
    }
</style>

<nav x-data="{ open: false }" class="bg-complimentary-nav border-b border-green-300 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}">
                        <img class="h-9 w-9" src="{{asset('storage/pictures/logo.png') }}" alt="">
                        {{-- <img height="50" width="50" src="{{ asset('storage/icons/logo.png') }}" alt="Oplanla Logo" class="block h-9 w-auto"> --}}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                    @php
                        // Define classes for active and inactive links to keep the template clean
                        $activeLinkClasses = 'admin-nav-link admin-nav-link-active inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out';
                        $inactiveLinkClasses = 'admin-nav-link inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out';
                    @endphp
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? $activeLinkClasses : $inactiveLinkClasses }}">
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users*') || request()->routeIs('admin.users.show') ? $activeLinkClasses : $inactiveLinkClasses }}">
                        {{ __('Users') }}
                    </a>
                    {{-- <a href="{{ route('room.booking.index') }}" class="{{ request()->routeIs('room.booking.index*') || request()->routeIs('room.booking.show') ? $activeLinkClasses : $inactiveLinkClasses }}">
                        {{ __('Bookings') }}
                    </a> --}}
                    <a href="{{ route('admin.jobs.index') }}" class="{{ request()->routeIs('admin.jobs*') || request()->routeIs('admin.jobs.single') ? $activeLinkClasses : $inactiveLinkClasses }}">
                        {{ __('Jobs') }}
                    </a>
                    <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects*') || request()->routeIs('admin.projects.show') ? $activeLinkClasses : $inactiveLinkClasses }}">
                        {{ __('Projects') }}
                    </a>
                    <a href="{{ route('admin.milestones.index') }}" class="{{ request()->routeIs('admin.milestones*') ? $activeLinkClasses : $inactiveLinkClasses }}">
                        {{ __('Milestones') }}
                    </a>
                    {{-- <a href="{{ route('admin.provider-users.index') }}" class="{{ request()->routeIs('admin.provider-users.index*') || request()->routeIs('admin.provider-users.show') ? $activeLinkClasses : $inactiveLinkClasses }}">
                        {{ __('Provider Users') }}
                    </a> --}}
                    {{-- <a href="{{ route('admin.booking-requests.index') }}" class="{{ request()->routeIs('admin.booking-requests.index') ? $activeLinkClasses : $inactiveLinkClasses }}">
                        {{ __('Edit Requests') }}
                    </a> --}}
                    {{-- <a href="{{ route('admin.partner-requests.index') }}" class="{{ request()->routeIs('admin.partner-requests.index') || request()->routeIs('admin.partner-requests.show') ? $activeLinkClasses : $inactiveLinkClasses }}">
                        {{ __('Partner Requests') }}
                    </a> --}}
                    {{-- <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.index') || request()->routeIs('admin.reports.index') ? $activeLinkClasses : $inactiveLinkClasses }}">
                        {{ __('Reports') }}
                    </a> --}}
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button style="color: var(--theme-nav-text);" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md bg-transparent focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::guard('admin')->user()->name}}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('admin.settings.edit')">
                            {{ __('Settings') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('admin.logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-800 hover:text-white hover:bg-complimentary-nav-darker focus:outline-none focus:bg-complimentary-nav-darker focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users*')">
                {{ __('Users') }}
            </x-responsive-nav-link>
            {{-- <x-responsive-nav-link :href="route('room.booking.index')" :active="request()->routeIs('room.booking.index*')">
                {{ __('All Bookings') }}
            </x-responsive-nav-link> --}}
            <x-responsive-nav-link :href="route('admin.jobs.index')" :active="request()->routeIs('admin.jobs.index*')">
                {{ __('Jobs') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.projects.index')" :active="request()->routeIs('admin.projects*')">
                {{ __('Projects') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.milestones.index')" :active="request()->routeIs('admin.milestones*')">
                {{ __('Milestones') }}
            </x-responsive-nav-link>
            {{-- <x-responsive-nav-link :href="route('providers.index')" :active="request()->routeIs('providers.index*')">
                {{ __('Providers') }}
            </x-responsive-nav-link> --}}
            {{-- <x-responsive-nav-link :href="route('admin.provider-users.index')" :active="request()->routeIs('admin.provider-users*')">
                {{ __('Provider Users') }}
            </x-responsive-nav-link> --}}
            {{-- <x-responsive-nav-link :href="route('admin.booking-requests.index')" :active="request()->routeIs('admin.booking-requests.index*')">
                {{ __('Edit Requests') }}
            </x-responsive-nav-link> --}}
            {{-- <x-responsive-nav-link :href="route('admin.partner-requests.index')" :active="request()->routeIs('admin.partner-requests.index*')">
                {{ __('Partner Requests') }}
            </x-responsive-nav-link> --}}
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-green-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::guard('admin')->user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::guard('admin')->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('admin.settings.edit')">
                    {{ __('Settings') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('admin.logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
