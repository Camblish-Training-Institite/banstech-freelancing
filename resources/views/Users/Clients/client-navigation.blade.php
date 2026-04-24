<style>
    .sidebar {
        background-color: var(--theme-sidebar-bg);
        color: #f0f0f0;
        padding: 20px 1rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex-shrink: 0;
        overflow-y: auto;
    }

    .sidebar-header {
        text-transform: uppercase;
        font-size: 0.8em;
        color: var(--theme-sidebar-muted);
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
        border-radius: 12px;
    }

    .sidebar-nav ul li a:hover {
        background-color: var(--theme-sidebar-hover);
    }

    .sidebar-nav ul li a i {
        margin-right: 10px;
        font-size: 1.1em;
    }

    .sidebar-logout {
        padding: 20px;
        border-top: 1px solid var(--theme-sidebar-border);
        margin-bottom: 40px;
    }

    .user-details {
        display: flex;
        font-size: 0.8em;
        color: var(--theme-sidebar-muted);
        padding: 0.125rem 1rem 0.5rem;
        margin-top: 15px;
        align-items: center;
        justify-content: space-evenly;
    }
</style>

@php($user = Auth::user())

<div class="sidebar-overlay" onclick="toggleDashboardSidebar(false)"></div>

<aside class="sidebar dashboard-sidebar" id="client-sidebar">
    <div>
        <div class="mobile-sidebar-header">
            <span class="text-sm uppercase tracking-wide text-gray-300">Client Menu</span>
            <button type="button" class="mobile-sidebar-close" onclick="toggleDashboardSidebar(false)">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="user-details">
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
                <li><a href="{{ route('client.inbox') }}"><i class="fas fa-inbox"></i> <span>Inbox</span></a></li>
                <li><a href="{{ route('client.find.users.index') }}"><i class="fas fa-tasks"></i> <span>Explore Freelancers</span></a></li>
                <li><a href="{{ route('billing') }}"><i class="fas fa-dollar-sign"></i> <span>Billing</span></a></li>
            </ul>
            <div class="sidebar-header">Preference</div>
            <ul>
                <li><a href="{{ route('client.profile.edit') }}"><i class="fas fa-user-circle"></i> <span>Profile Management</span></a></li>
                <li><a href="#"><i class="fas fa-hands-helping"></i> <span>Support & Assistance</span></a></li>
                <li><a href="{{ route('client.settings.edit') }}"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
            </ul>
        </nav>
    </div>

    <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="fas fa-sign-out-alt w-full"> Log out</button>
        </form>
    </div>
</aside>
