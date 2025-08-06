@extends('dashboards.freelancer.dashboard')

@section('body')
    {{-- Welcome message is first --}}
    <div class="welcome-message">Welcome back, {{Auth::User()->name}}! 👋</div>
    @include('Users.Freelancers.layouts.main-nav')
    
    @yield('active-tab')
@endsection