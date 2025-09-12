@extends('layouts.client')

@section('content')

@if ($errors->any())
<div class="mb-4 font-medium text-sm text-red-600">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Existing status and error messages -->
@if (session('status'))
<div class="mb-4 font-medium text-sm text-green-600">
    {{ session('status') }}
</div>
@endif

@if (session('error'))
<div class="mb-4 font-medium text-sm text-red-600">
    {{ session('error') }}
</div>
@endif

<style>
    
</style>

<div class="dashboard-container">
    <div class="main-content">
        <header class="header">
            <div class="dashboard-title">Client Dashboard</div>
            <div class="header-right">
                <div class="become-client" onclick="window.location.href='{{ route('freelancer.dashboard') }}'">
                    <span>Become Freelancer</span>
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="search-bar">
                    <div class="flex w-full items-center search-bar space-x-2 rounded-full px-3 py-2 bg-gray-100">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search" class="ml-4 border-none bg-transparent rounded-full w-full focus:ring-gray-200">
                    </div>
                </div>
                <div class="notification-icon">
                    @include('components.notifications', ['user' => Auth::user()]) 
                </div>
                
                {{-- The <div class="user-avatar"> block was previously here and has been removed. --}}
            </div>
                   

        </header>

        <div class="dashboard-body">
            @yield('body')
        </div>
    </div>

    {{-- The entire <div class="profile-card"> block was previously here and has been removed. --}}

        <div class="floating-widgets">
            <button class="message-btn">Messages</button>
            <button class="notification-float-btn">
                <i class="fas fa-bell"></i>
                <span class="badge"></span>
            </button>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('pages_css/dashboards.css') }}">        
    @endpush
@endsection