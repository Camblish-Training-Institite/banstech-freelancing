@php
    $navItems = [
        ['label' => 'My Jobs', 'route' => route('client.jobs.list'), 'active' => request()->routeIs('client.jobs.list')],
        ['label' => 'My Projects', 'route' => route('client.projects.list'), 'active' => request()->routeIs('client.projects.list')],
        ['label' => 'Proposals', 'route' => route('client.proposals.list'), 'active' => request()->routeIs('client.proposals.list') || request()->routeIs('client.proposals.show') || request()->routeIs('client.proposals.job.show')],
        ['label' => 'Contest', 'route' => route('client.contests.index'), 'active' => request()->routeIs('client.contests.index')],
        ['label' => 'Completed Projects', 'route' => '#', 'active' => request()->routeIs('freelancer/completed-projects*')],
        ['label' => 'Profile', 'route' => route('client.profile.edit'), 'active' => request()->routeIs('client.profile.edit')],
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
