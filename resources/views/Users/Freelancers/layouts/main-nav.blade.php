@php
    $navItems = [
        ['label' => 'My Projects', 'route' => route('freelancer.projects.list'), 'active' => request()->routeIs('freelancer.projects.list')],
        ['label' => 'Proposals', 'route' => route('freelancer.proposals.index'), 'active' => request()->routeIs('freelancer.proposals.index')],
        ['label' => 'Contest', 'route' => route('freelancer.contests.index'), 'active' => request()->routeIs('freelancer.contests.index')],
        ['label' => 'Completed Projects', 'route' => '#', 'active' => request()->routeIs('freelancer/completed-projects*')],
        ['label' => 'Saved Jobs', 'route' => route('freelancer.jobs.saved'), 'active' => request()->routeIs('freelancer.jobs.saved')],
    ];
    $primaryItems = array_slice($navItems, 0, 2);
    $moreItems = array_slice($navItems, 2);
    $moreActive = collect($moreItems)->contains(fn ($item) => $item['active']);
@endphp

<nav class="main-nav-tabs" x-data="{ moreOpen: false }">
    <div class="main-nav-desktop">
        <ul>
            @foreach ($navItems as $item)
                <li class="{{ $item['active'] ? 'active' : '' }}">
                    <a href="{{ $item['route'] }}">{{ $item['label'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="main-nav-mobile">
        @foreach ($primaryItems as $item)
            <a href="{{ $item['route'] }}" class="mobile-nav-link {{ $item['active'] ? 'active' : '' }}">
                {{ $item['label'] }}
            </a>
        @endforeach

        <div class="main-nav-mobile-more">
            <button type="button" class="mobile-nav-more-btn {{ $moreActive ? 'active' : '' }}" @click="moreOpen = !moreOpen">
                More
            </button>
            <div class="main-nav-mobile-menu" x-show="moreOpen" @click.outside="moreOpen = false" x-transition>
                @foreach ($moreItems as $item)
                    <a href="{{ $item['route'] }}" class="{{ $item['active'] ? 'active' : '' }}">{{ $item['label'] }}</a>
                @endforeach
            </div>
        </div>
    </div>
</nav>
