{{-- Navigation tabs are second --}}
<nav class="main-nav-tabs">
    <ul>
        <!-- My Jobs -->
        <li class="{{ request()->routeIs('freelancer.jobs.list') ? 'active' : '' }}">
            <a href="{{ route('freelancer.jobs.list') }}">My Projects</a>
        </li>

        <!-- Proposals -->
        <li class="{{ request()->routeIs('freelancer.proposals.index') ? 'active' : '' }}">
            <a href="{{ route('freelancer.proposals.index') }}">Proposals</a>
        </li>

        <!-- Contest -->
        <li class="{{ request()->routeIs('freelancer.contests.index') ? 'active' : '' }}">
            <a href="{{ route('freelancer.contests.index') }}">Contest</a>
        </li>

        <!-- Completed Projects -->
        <li class="{{ request()->routeIs('freelancer/completed-projects*') ? 'active' : '' }}">
            <a href="#">Completed Projects</a>
        </li>
    </ul>
</nav>