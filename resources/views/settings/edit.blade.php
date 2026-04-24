@extends($layout)

@php
    $activeThemeKey = old('theme', $user->theme ?? 'default');
    $isDashboardContext = in_array($layout, ['layouts.client', 'layouts.freelancer'], true);
@endphp

@section('content')
@if ($isDashboardContext)
    <div class="dashboard-container">
        <div class="main-content">
            <header class="header">
                <div class="dashboard-title-row header-title-group">
                    <button type="button" class="mobile-sidebar-toggle" onclick="toggleDashboardSidebar(true)">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="dashboard-title">{{ $pageTitle }}</div>
                </div>
                <div class="header-right">
                    <a href="{{ route($dashboardRouteName) }}" class="become-client">
                        <span>Back to Dashboard</span>
                    </a>
                </div>
            </header>

            <div class="dashboard-body">
                <div class="dashboard-content-shell">
                    <section class="dashboard-section-body">
@endif

<div class="w-full max-w-5xl {{ $isDashboardContext ? '' : 'px-6 py-10' }}">
    <div class="mx-auto overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-xl">
        <div class="border-b border-gray-200 bg-gray-50 px-8 py-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">Settings</p>
                    <h1 class="mt-2 text-3xl font-bold text-gray-900">{{ $pageTitle }}</h1>
                    <p class="mt-3 max-w-2xl text-sm text-gray-600">
                        Choose the look and feel of your workspace. Your selection will follow you across the shared app layouts and dashboards.
                    </p>
                </div>
                <a href="{{ route($dashboardRouteName) }}" class="inline-flex items-center rounded-full border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="px-8 py-8">
            @if (session('status') === 'theme-updated')
                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    Theme updated successfully.
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route($settingsRouteName) }}" class="space-y-8">
                @csrf
                @method('PATCH')

                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Theme Selection</h2>
                    <p class="mt-2 text-sm text-gray-600">Pick one of the three curated themes below.</p>
                    <p class="mt-2 text-sm font-medium text-indigo-600">Selecting a theme previews it immediately on this page before you save.</p>
                </div>

                <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
                    @foreach ($themes as $theme)
                        <label class="group relative block cursor-pointer">
                            <input
                                type="radio"
                                name="theme"
                                value="{{ $theme['key'] }}"
                                class="theme-selector sr-only"
                                {{ $activeThemeKey === $theme['key'] ? 'checked' : '' }}
                            >
                            <div class="theme-option rounded-3xl border border-gray-200 bg-white p-5 shadow-sm transition duration-200 group-hover:-translate-y-1 group-hover:shadow-lg">
                                <div class="mb-5 flex items-center justify-between gap-3">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $theme['name'] }}</h3>
                                        <p class="mt-1 text-sm text-gray-600">{{ $theme['description'] }}</p>
                                    </div>
                                    <span class="theme-option-indicator inline-flex h-6 w-6 items-center justify-center rounded-full border border-gray-300 bg-white text-xs font-bold text-white">
                                        ✓
                                    </span>
                                </div>

                                <div class="mb-5 flex gap-3">
                                    @foreach ($theme['swatches'] as $swatch)
                                        <span class="h-10 w-10 rounded-2xl border border-white/50 shadow-sm" style="background-color: {{ $swatch }};"></span>
                                    @endforeach
                                </div>

                                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                                    <div class="mb-3 flex items-center justify-between">
                                        <span class="h-3 w-24 rounded-full bg-gray-300"></span>
                                        <span class="h-3 w-12 rounded-full bg-gray-200"></span>
                                    </div>
                                    <div class="space-y-2">
                                        <span class="block h-3 w-full rounded-full bg-gray-200"></span>
                                        <span class="block h-3 w-4/5 rounded-full bg-gray-200"></span>
                                    </div>
                                    <div class="mt-4 h-10 w-32 rounded-2xl" style="background-color: {{ $theme['swatches'][1] }};"></div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center rounded-full bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                        Save Theme
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($isDashboardContext)
                    </section>
                </div>
            </div>
        </div>
    </div>
@endif

@push('styles')
    @if ($isDashboardContext)
        <link rel="stylesheet" href="{{ asset('pages_css/dashboards.css') }}">
    @endif
@endpush

<style>
    input[type="radio"]:checked + .theme-option {
        border-color: var(--theme-accent);
        box-shadow: var(--theme-shadow);
        outline: 2px solid var(--theme-accent);
        outline-offset: 2px;
    }

    input[type="radio"]:checked + .theme-option .theme-option-indicator {
        border-color: var(--theme-accent);
        background: var(--theme-accent);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const root = document.documentElement;
        const savedTheme = @json($user->theme ?? 'default');
        const selectors = document.querySelectorAll('.theme-selector');

        selectors.forEach((selector) => {
            selector.addEventListener('change', (event) => {
                root.setAttribute('data-theme', event.target.value);
            });
        });

        window.addEventListener('beforeunload', () => {
            root.setAttribute('data-theme', savedTheme);
        });
    });
</script>
@endsection
