@extends('dashboards.freelancer.dashboard')

@section('body')
    <div class="dashboard-title-row">
        <button type="button" class="mobile-sidebar-toggle" onclick="toggleDashboardSidebar(true)">
            <i class="fas fa-bars"></i>
        </button>
        <div class="welcome-message">Welcome back, {{ Auth::user()->name }}!</div>
    </div>

    <div class="dashboard-content-shell">
        @include('Users.Freelancers.layouts.main-nav')
        <section class="dashboard-section-body">
            @yield('active-tab')
        </section>
    </div>
@endsection
