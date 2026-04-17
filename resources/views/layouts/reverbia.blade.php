<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Reverbia') }}</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" type="image/jpeg" sizes="32x32" href="/icons/icon-32.jpg">
        <link rel="icon" type="image/jpeg" sizes="16x16" href="/icons/icon-16.jpg">
        <link rel="shortcut icon" href="/icons/icon-32.jpg">
        <link rel="manifest" href="/manifest.webmanifest">
        <link rel="apple-touch-icon" href="/icons/apple-touch-icon.jpg">
        <meta name="theme-color" content="#050505">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="Reverbia">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --bg: #050505;
                --panel: #0d0d0f;
                --panel-2: #131316;
                --accent: #7efc5b;
                --accent-2: #f35aa7;
                --muted: #a0a0a5;
                --text: #f1f1f1;
                --line: #1f1f22;
                --shadow: 0 16px 38px rgba(0, 0, 0, 0.45);
            }
            * { box-sizing: border-box; }
            body {
                margin: 0;
                min-height: 100vh;
                background: var(--bg);
                color: var(--text);
                font-family: 'Montserrat', system-ui, -apple-system, sans-serif;
            }
            .topbar {
                position: sticky;
                top: 0;
                z-index: 30;
                padding: 14px 18px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: #050505;
                border-bottom: 1px solid var(--line);
            }
            .brand {
                letter-spacing: 5px;
                font-weight: 700;
                font-size: 15px;
                display: flex;
                align-items: center;
                gap: 6px;
            }
            .brand span { color: var(--accent); }
            .top-icons { display: flex; gap: 14px; font-size: 18px; color: var(--text); }
            .hamburger {
                width: 34px;
                height: 34px;
                border-radius: 50%;
                border: 1px solid var(--line);
                background: #0a0a0c;
                display: grid;
                place-items: center;
                padding: 0;
                color: var(--text);
            }
            .hamburger span {
                position: relative;
                display: block;
                width: 14px;
                height: 2px;
                background: var(--text);
            }
            .hamburger span::before,
            .hamburger span::after {
                content: "";
                position: absolute;
                left: 0;
                width: 100%;
                height: 2px;
                background: var(--text);
                transition: transform 0.2s ease;
            }
            .hamburger span::before { top: -5px; }
            .hamburger span::after { top: 5px; }
            .nav-bottom {
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                background: #0a0a0c;
                border-top: 1px solid var(--line);
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                padding: 10px 4px;
                color: var(--muted);
                font-size: 12px;
                z-index: 10;
            }
            .nav-bottom a {
                color: inherit;
                text-decoration: none;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 4px;
            }
            .nav-bottom a.active { color: var(--accent); }
            .menu-overlay {
                position: fixed;
                inset: 0;
                background: #050505;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.25s ease;
                z-index: 40;
            }
            .menu-panel {
                position: absolute;
                inset: 0;
                background: #050505;
                padding: 26px 20px 20px;
                transform: translateY(-6%);
                opacity: 0;
                transition: all 0.28s ease;
                overflow-y: auto;
            }
            body.menu-open .menu-overlay { opacity: 1; pointer-events: auto; }
            body.menu-open .menu-panel { transform: translateY(0); opacity: 1; }
            .menu-items a {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 10px;
                color: var(--text);
                text-decoration: none;
                border-bottom: 1px solid var(--line);
                font-weight: 600;
            }
            .menu-items a i { color: var(--accent); font-size: 18px; }
            .btn-close-menu {
                width: 34px;
                height: 34px;
                border-radius: 50%;
                border: 1px solid var(--line);
                background: #0f0f12;
                color: var(--text);
                display: grid;
                place-items: center;
            }
            .pwa-gate {
                position: fixed;
                inset: 0;
                display: none;
                align-items: center;
                justify-content: center;
                padding: 24px;
                background: radial-gradient(circle at 15% 10%, rgba(126,252,91,0.12), transparent 40%),
                            radial-gradient(circle at 85% 20%, rgba(243,90,167,0.12), transparent 45%),
                            rgba(5,5,5,0.95);
                z-index: 9999;
                text-align: left;
            }
            .pwa-required .pwa-gate { display: none !important; }
            .pwa-card {
                width: min(560px, 100%);
                background: var(--panel);
                border: 1px solid var(--line);
                border-radius: 24px;
                padding: 28px;
                box-shadow: var(--shadow);
            }
            .pwa-eyebrow {
                text-transform: uppercase;
                letter-spacing: 4px;
                font-size: 12px;
                color: var(--muted);
                margin-bottom: 8px;
            }
            .pwa-title {
                font-size: 22px;
                font-weight: 700;
                margin: 0 0 10px;
            }
            .pwa-copy {
                color: var(--muted);
                font-size: 14px;
                line-height: 1.6;
                margin: 0 0 18px;
            }
            .pwa-steps {
                display: grid;
                gap: 14px;
            }
            .pwa-step {
                background: var(--panel-2);
                border: 1px solid var(--line);
                border-radius: 16px;
                padding: 14px 16px;
            }
            .pwa-step h3 {
                margin: 0 0 6px;
                font-size: 12px;
                letter-spacing: 2px;
                text-transform: uppercase;
                color: var(--accent);
            }
            .pwa-step p {
                margin: 0;
                color: var(--muted);
                font-size: 13px;
                line-height: 1.5;
            }
        </style>
        @stack('styles')
    </head>
    <body>
        <!-- Topbar -->
        <div class="topbar">
            <a href="{{ route('home') }}" class="brand text-decoration-none" wire:navigate>
                <span>RE</span>VERBIA
            </a>
            <div class="top-icons">
                <a href="{{ route('pricing') }}" class="text-decoration-none text-white" wire:navigate>
                    <i class="bi bi-bag-plus"></i>
                </a>
                <div class="hamburger" id="menuToggle">
                    <span></span>
                </div>
            </div>
        </div>

        <!-- Fullscreen Menu Overlay -->
        <div class="menu-overlay" id="menuOverlay">
            <div class="menu-panel">
                <div class="d-flex justify-content-end mb-4">
                    <div class="btn-close-menu" data-close>
                        <i class="bi bi-x-lg"></i>
                    </div>
                </div>
                <div class="menu-items d-flex flex-column">
                    <a href="{{ route('home') }}" wire:navigate>
                        <i class="bi bi-house"></i> Home
                    </a>
                    <a href="{{ route('pricing') }}" wire:navigate>
                        <i class="bi bi-cart"></i> Acquista Crediti
                    </a>
                    <a href="{{ route('profile') }}" wire:navigate>
                        <i class="bi bi-person"></i> Profilo
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-100">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main style="padding-bottom: 70px;">
            {{ $slot ?? '' }}
            @yield('slot')
            @unless(request()->routeIs('payment.success') || request()->routeIs('payment.cancel'))
                @include('livewire.layout.menu')
            @endunless
        </main>

        <div class="pwa-gate" id="pwaGate" role="dialog" aria-modal="true" aria-label="Installazione app Reverbia">
            <div class="pwa-card">
                <div class="pwa-eyebrow">Reverbia</div>
                <h1 class="pwa-title">Installa l'app per utilizzarla</h1>
                <p class="pwa-copy">Servono pochi passi. Una volta installata, apri Reverbia dalla schermata Home.</p>
                <div class="pwa-steps">
                    <div class="pwa-step">
                        <h3>Android (Chrome)</h3>
                        <p>Apri il menu <i class="bi bi-three-dots-vertical"></i> e scegli "Installa app" o "Aggiungi a schermata Home".</p>
                    </div>
                    <div class="pwa-step">
                        <h3>iOS (Safari)</h3>
                        <p>Tocca Condividi <i class="bi bi-box-arrow-up"></i> e poi "Aggiungi a schermata Home".</p>
                    </div>
                    <div class="pwa-step">
                        <h3>Ultimo passo</h3>
                        <p>Conferma su "Installa" ed e fatto.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Navigation -->
        
            

       

        <script>
            (function () {
                const setupMenu = () => {
                    const body = document.body;
                    const toggle = document.getElementById('menuToggle');
                    const overlay = document.getElementById('menuOverlay');
                    const closeButtons = overlay ? overlay.querySelectorAll('[data-close]') : [];

                    if (!toggle || !overlay) {
                        return;
                    }

                    const closeMenu = () => body.classList.remove('menu-open');

                    toggle.addEventListener('click', () => {
                        body.classList.toggle('menu-open');
                    });

                    overlay.addEventListener('click', (event) => {
                        if (event.target === overlay) {
                            closeMenu();
                        }
                    });

                    closeButtons.forEach((btn) => btn.addEventListener('click', closeMenu));

                    document.addEventListener('keydown', (event) => {
                        if (event.key === 'Escape') {
                            closeMenu();
                        }
                    });
                };

                document.addEventListener('DOMContentLoaded', setupMenu);
                document.addEventListener('livewire:navigated', setupMenu);
            }());
        </script>
        <script>
            (function () {
                const updatePwaGate = () => {
                    const isStandalone = window.matchMedia && window.matchMedia('(display-mode: standalone)').matches;
                    const isIosStandalone = window.navigator && window.navigator.standalone === true;
                    if (isStandalone || isIosStandalone) {
                        document.body.classList.remove('pwa-required');
                    } else {
                        document.body.classList.add('pwa-required');
                    }
                };

                document.addEventListener('DOMContentLoaded', updatePwaGate);
                document.addEventListener('livewire:navigated', updatePwaGate);

                if (window.matchMedia) {
                    const media = window.matchMedia('(display-mode: standalone)');
                    if (media.addEventListener) {
                        media.addEventListener('change', updatePwaGate);
                    } else if (media.addListener) {
                        media.addListener(updatePwaGate);
                    }
                }
            }());
        </script>
        @stack('scripts')
    </body>
</html>

