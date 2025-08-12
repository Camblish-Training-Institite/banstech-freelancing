{{-- Navigation tabs are second --}}
<nav class="main-nav-tabs">
    <ul>
        <!-- My Jobs -->
        <li class="{{ request()->routeIs('client.jobs.list') ? 'active' : '' }}">
            <a href="{{ route('client.jobs.list') }}">My Jobs</a>
        </li>

        <!-- Proposals -->
        <li class="{{ request()->routeIs('client.proposals.list') ? 'active' : '' }}">
            <a href="{{ route('client.proposals.list') }}">Proposals</a>
        </li>

        <!-- Contest -->
        <li class="{{ request()->routeIs('client.contests.list') ? 'active' : '' }}">
            <a href="{{ route('client.contests.list') }}">Contest</a>
        </li>

        <!-- Completed Projects -->
        <li class="{{ request()->routeIs('freelancer/completed-projects*') ? 'active' : '' }}">
            <a href="#">Completed Projects</a>
        </li>
    </ul>
</nav>