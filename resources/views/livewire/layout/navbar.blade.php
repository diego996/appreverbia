<header class="topbar">
    <div class="top-left">
        <button type="button"
                class="top-back"
                aria-label="Torna indietro"
                onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href='{{ route('home') }}'; }">
            <i class="bi bi-chevron-left"></i>
        </button>
    </div>
    <a class="brand" href="{{ route('home') }}" wire:navigate aria-label="Reverbia">
        <x-application-logo class="app-logo" />
    </a>
    <div class="top-icons" aria-label="Azioni rapide">
        <a href="{{ route('profile') }}" wire:navigate aria-label="Profilo utente">
            <i class="bi bi-person"></i>
        </a>
    </div>
</header>
