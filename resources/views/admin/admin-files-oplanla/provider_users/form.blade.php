@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">{{ $action }} Provider User</h1>
            <p class="mt-1 text-sm text-gray-600">Please fill out the details below.</p>
        </div>

        <form method="POST" action="{{ $action === 'Create' ? route('admin.provider-users.store') : route('admin.provider-users.update', $user->id) }}">
            @csrf
            @if($action === 'Edit')
                @method('PUT')
            @endif

            <div class="p-6 space-y-6">
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Please correct the errors below:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- Assign Provider -->
                <div>
                    <label for="provider_id" class="block text-sm font-medium text-gray-700">Assign to Provider</label>
                    <select name="provider_id" id="provider_id" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <option value="">-- Select a Provider --</option>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}" @selected(old('provider_id', $user->provider_id) == $provider->id)>{{$provider->id}} - {{ $provider->provider_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" {{ $action === 'Create' ? 'required' : '' }} class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                     @if($action === 'Edit')
                        <p class="mt-1 text-xs text-gray-500">Leave blank to keep the current password.</p>
                    @endif
                </div>
                
                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" {{ $action === 'Create' ? 'required' : '' }} class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex justify-end items-center space-x-4">
                 <a href="{{ route('admin.provider-users.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $action }} Provider User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
