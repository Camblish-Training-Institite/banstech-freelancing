@extends('layouts.admin')

@section('content')
<div class="mx-auto w-full max-w-4xl px-6 py-10">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg">
        <div class="px-8 py-7" style="background: var(--theme-nav-surface); color: var(--theme-nav-text);">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    @include('components.user-avatar', ['user' => $user, 'size' => '5rem'])
                    <div>
                        <p class="text-sm uppercase tracking-wide" style="color: var(--theme-nav-muted);">Banstech Admin</p>
                        <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
                        <p class="mt-1 text-sm" style="color: var(--theme-nav-muted);">{{ $user->email }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center rounded-md border px-4 py-2 text-sm font-semibold text-white" style="border-color: var(--theme-nav-border);">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="px-8 py-8">
            @if (session('status') === 'profile-updated')
                <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    Profile updated successfully.
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700">Username / Display Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="avatar" class="block text-sm font-semibold text-gray-700">Profile Image</label>
                    <div class="mt-3 flex flex-col gap-4 sm:flex-row sm:items-center">
                        @include('components.user-avatar', ['user' => $user, 'size' => '4rem'])
                        <input id="avatar" name="avatar" type="file" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                        Save Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
