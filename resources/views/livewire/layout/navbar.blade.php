@php
    $menuLinks = $menuLinks ?? [
        ['icon' => 'bi-house-door', 'label' => 'Home', 'url' => route('home')],
        ['icon' => 'bi-calendar4-week', 'label' => 'Calendario', 'url' => route('calendar')],
    ];
    $hasNotifications = $hasNotifications ?? false;
@endphp

<header class="topbar">
    <button id="menuToggle" class="hamburger" aria-label="Apri menu">
        <span></span>
    </button>
    <a class="brand" href="{{ route('home') }}" wire:navigate aria-label="Reverbia">
        <x-application-logo class="app-logo" />
    </a>
    <div class="top-icons" aria-label="Azioni rapide">
        <i class="bi bi-cart3"></i>
        @if ($hasNotifications)
            <i class="bi bi-bell"></i>
        @endif
    </div>
</header>

<div id="menuOverlay" class="menu-overlay" aria-hidden="true">
    <div class="menu-panel">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a class="brand" href="{{ route('home') }}" wire:navigate aria-label="Reverbia">
                <x-application-logo class="app-logo" />
            </a>
            <button class="btn-close-menu" type="button" data-close aria-label="Chiudi menu">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="menu-items">
            @foreach ($menuLinks as $link)
                <a href="{{ $link['url'] }}" @if(!str_starts_with($link['url'], '#')) wire:navigate @endif>
                    <i class="bi {{ $link['icon'] }}"></i>
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
