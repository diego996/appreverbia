<nav class="nav-bottom" aria-label="Menu principale">
    <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}" wire:navigate>
        <i class="bi bi-house-door"></i><span>Home</span>
    </a>
    <a class="{{ request()->routeIs('pricing') ? 'active' : '' }}" href="{{ route('pricing') }}" wire:navigate>
        <i class="bi bi-bag-plus"></i><span>Acquista</span>
    </a>
    <a class="{{ request()->routeIs('calendar') ? 'active' : '' }}" href="{{ route('calendar') }}" wire:navigate>
        <i class="bi bi-calendar4-week"></i><span>Calendario</span>
    </a>
    <a class="{{ request()->routeIs('support') ? 'active' : '' }}" href="{{ route('support') }}" wire:navigate>
        <i class="bi bi-headset"></i><span>Supporto</span>
    </a>
</nav>