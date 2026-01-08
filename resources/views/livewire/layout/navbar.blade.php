<header class="topbar">
    <a class="brand" href="{{ route('home') }}" wire:navigate aria-label="Reverbia">
        <x-application-logo class="app-logo" />
    </a>
    <div class="top-icons" aria-label="Azioni rapide">
        <a href="{{ route('profile') }}" wire:navigate aria-label="Profilo utente">
            <i class="bi bi-person"></i>
        </a>
    </div>
</header>
