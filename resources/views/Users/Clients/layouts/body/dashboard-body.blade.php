@extends('dashboards.client.dashboard')

@section('body')
    {{-- Welcome message is first --}}
    <div class="welcome-message">Welcome back, {{Auth::User()->name}}! 👋</div>
    @include('Users.Clients.layouts.main-nav')
    
    @yield('active-tab')
@endsection