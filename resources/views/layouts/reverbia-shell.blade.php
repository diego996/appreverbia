<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Reverbia') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/icon-32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icons/icon-16.png">
    <link rel="shortcut icon" href="/icons/icon-32.png">
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">
    <meta name="theme-color" content="#050505">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Reverbia">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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

        * {
            box-sizing: border-box;
        }

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
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            background: linear-gradient(180deg, rgba(126, 252, 91, 0.08), rgba(5, 5, 5, 0.94));
            border-bottom: 1px solid var(--line);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: inherit;
            justify-self: center;
            grid-column: 2;
        }

        .app-logo {
            height: 22px;
            width: auto;
            display: block;
        }

        .app-logo-text {
            letter-spacing: 5px;
            font-weight: 700;
            font-size: 15px;
        }

        .top-icons {
            display: flex;
            gap: 14px;
            font-size: 18px;
            color: var(--text);
            justify-self: end;
            grid-column: 3;
        }

        .top-icons a {
            color: inherit;
            text-decoration: none;
        }

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

        .hamburger span::before {
            top: -5px;
        }

        .hamburger span::after {
            top: 5px;
        }

        .nav-bottom {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            background: #0a0a0c;
            border-top: 1px solid var(--line);
            display: grid;
            grid-template-columns: repeat(4, 1fr);
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

        .nav-bottom a.active {
            color: var(--accent);
        }

        .menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(3px);
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

        body.menu-open .menu-overlay {
            opacity: 1;
            pointer-events: auto;
        }

        body.menu-open .menu-panel {
            transform: translateY(0);
            opacity: 1;
        }

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

        .menu-items a i {
            color: var(--accent);
            font-size: 18px;
        }

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
    </style>
    @stack('styles')
</head>

<body>
    @include('livewire.layout.navbar')

    {{ $slot ?? '' }}
    @yield('slot')

    @include('livewire.layout.menu')

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
    @stack('scripts')
</body>

</html>
