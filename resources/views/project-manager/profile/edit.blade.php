@extends('layouts.project-manager')

@section('content')
    <div class="w-full min-h-screen bg-gray-100 px-6 py-10">
        <div class="mx-auto max-w-6xl space-y-6">
            <div class="rounded-lg p-6 shadow-lg" style="background: var(--theme-nav-surface); color: var(--theme-nav-text);">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        @include('components.user-avatar', ['user' => $user, 'size' => '5rem'])
                        <div>
                            <p class="text-sm uppercase tracking-wide" style="color: var(--theme-nav-muted);">Project Manager Profile</p>
                            <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
                            <p class="mt-1 text-sm" style="color: var(--theme-nav-muted);">{{ $profile->title ?: 'Project Manager' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('pm.dashboard') }}" class="inline-flex items-center rounded-md border px-4 py-2 text-sm font-semibold text-white" style="border-color: var(--theme-nav-border);">
                        Back to Dashboard
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="rounded-lg bg-white p-4 shadow sm:p-8 lg:col-span-2">
                    @include('project-manager.profile.partials.update-profile-information-form')
                </div>

                <div class="rounded-lg bg-white p-4 shadow sm:p-8">
                    @include('project-manager.profile.partials.update-password-form')
                </div>

                <div class="rounded-lg bg-white p-4 shadow sm:p-8 lg:col-span-3">
                    @include('project-manager.profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
