{{-- Navigation tabs are second --}}
<nav class="main-nav-tabs">
    <ul>
        <!-- My Jobs -->
        <li class="{{ request()->routeIs('client.jobs.list') ? 'active' : '' }}">
            <a href="{{ route('client.jobs.list') }}">My Jobs</a>
        </li>

        <!-- My Projects -->
        <li class="{{ request()->routeIs('client.projects.list') ? 'active' : '' }}">
            <a href="{{ route('client.projects.list') }}">My Projects</a>
        </li>

        <!-- Proposals -->
        <li class="{{ request()->routeIs('client.proposals.list') ? 'active' : '' }}">
            <a href="{{ route('client.proposals.list') }}">Proposals</a>
        </li>

        <!-- Contest -->

        <li class="{{ request()->routeIs('client.contests.index') ? 'active' : '' }}">
            <a href="{{ route('client.contests.index') }}">Contest</a>

        </li>

        <!-- Completed Projects -->
        <li class="{{ request()->routeIs('freelancer/completed-projects*') ? 'active' : '' }}">
            <a href="#">Completed Projects</a>
        </li>
    </ul>
</nav>