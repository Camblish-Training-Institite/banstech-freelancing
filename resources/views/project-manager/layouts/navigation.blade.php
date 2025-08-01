<nav x-data="{ open: false }" class="bg-black dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
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
            </div> --}}
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="" class="">
            {{-- <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('pm.dashboard')" :active="request()->routeIs('pm.dashboard')">
                    {{ __('pm.Dashboard') }}
                </x-responsive-nav-link>
            </div> --}}

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
</nav>
