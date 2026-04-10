<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <link rel="stylesheet" href="{{ asset('auth_css/login.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <form method="POST" action="{{ route('admin.login.store') }}">
        @csrf
    
        <h1 class="text-2xl font-bold text-center mb-6">{{ __('Welcome Back') }}</h1>

    
        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full mt-1 rounded-full" 
                          type="email" name="email" :value="old('email')" 
                          required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
    
        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block w-full mt-1 rounded-full" 
                          type="password" name="password" 
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
    
        <div class="flex items-center justify-between">
            <!-- Log In Button -->
            <x-primary-button class="rounded-full">
                {{ __('Log in') }}
            </x-primary-button>
            
            <!-- Don't have an account? text and Register link -->
            <div class="flex items-center">
                <span class="text-sm text-gray-750 dark:text-gray-400">Don't have an account?</span>
                &nbsp;
                <a class="underline text-sm text-gray-750 dark:text-gray-750 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('register') }}">
                    {{ __('Register') }}
                </a>
            </div>
        </div>
    </form>

</x-guest-layout>
    