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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --bg: #050505;
                --card: #0e0e0f;
                --accent: #8df968;
                --text: #e5e5e5;
                --muted: #9ea0a3;
            }
            * { box-sizing: border-box; }
            body {
                margin: 0;
                min-height: 100vh;
                background: radial-gradient(circle at 20% 20%, rgba(141,249,104,0.08), transparent 35%),
                            radial-gradient(circle at 80% 10%, rgba(141,249,104,0.08), transparent 25%),
                            var(--bg);
                color: var(--text);
                font-family: 'Montserrat', system-ui, -apple-system, sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
            }
            .panel {
                width: min(420px, 100%);
                background: var(--card);
                padding: 32px 28px;
                border-radius: 22px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.5);
                border: 1px solid rgba(255,255,255,0.04);
            }
            .logo {
                text-align: center;
                margin-bottom: 12px;
            }
            .app-logo {
                height: 32px;
                width: auto;
            }
            .app-logo-text {
                letter-spacing: 6px;
                font-size: 22px;
                font-weight: 700;
                color: var(--text);
            }
            h1 {
                text-align: center;
                font-size: 18px;
                font-weight: 600;
                color: #cfd2d5;
                margin: 0 0 24px;
            }
            .field { margin-bottom: 16px; }
            label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
                color: #cfd2d5;
                font-size: 13px;
            }
            input[type="email"],
            input[type="password"] {
                width: 100%;
                background: #151516;
                border: 1px solid #1f1f21;
                border-radius: 12px;
                color: #fff;
                padding: 14px 14px;
                font-size: 14px;
                outline: none;
            }
            input:focus {
                border-color: var(--accent);
                box-shadow: 0 0 0 3px rgba(141,249,104,0.25);
            }
            .checkbox {
                display: flex;
                align-items: center;
                gap: 10px;
                margin: 10px 0 14px;
                color: var(--muted);
                font-size: 13px;
            }
            .checkbox input {
                width: 18px;
                height: 18px;
                accent-color: var(--accent);
            }
            .btn {
                width: 100%;
                background: linear-gradient(90deg, #8df968, #47e361);
                color: #041005;
                font-weight: 700;
                font-size: 15px;
                padding: 14px;
                border-radius: 12px;
                border: none;
                cursor: pointer;
                box-shadow: 0 14px 30px rgba(71,227,97,0.35);
                transition: transform .2s ease, box-shadow .2s ease;
            }
            .btn:hover { transform: translateY(-1px); box-shadow: 0 16px 36px rgba(71,227,97,0.45); }
            .btn:active { transform: translateY(0); }
            .links {
                text-align: center;
                color: var(--muted);
                margin-top: 18px;
                font-size: 13px;
                line-height: 1.7;
            }
            .links a { color: var(--accent); text-decoration: none; font-weight: 600; }
            .error-text {
                color: #ff7a7a;
                font-size: 12px;
                margin-top: 6px;
            }
            .status-text {
                background: rgba(141,249,104,0.12);
                border: 1px solid rgba(141,249,104,0.3);
                color: var(--accent);
                padding: 10px 12px;
                border-radius: 12px;
                font-size: 13px;
                margin-bottom: 16px;
                text-align: center;
            }
            .pwa-gate {
                position: fixed;
                inset: 0;
                display: none;
                align-items: center;
                justify-content: center;
                padding: 24px;
                background: radial-gradient(circle at 15% 10%, rgba(141,249,104,0.18), transparent 40%),
                            radial-gradient(circle at 85% 20%, rgba(141,249,104,0.12), transparent 45%),
                            rgba(5,5,5,0.95);
                z-index: 9999;
                text-align: left;
            }
            .pwa-required .pwa-gate { display: none !important; }
            .pwa-card {
                width: min(560px, 100%);
                background: var(--card);
                border: 1px solid rgba(255,255,255,0.06);
                border-radius: 24px;
                padding: 28px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.55);
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
                background: #141415;
                border: 1px solid #1f1f21;
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
        {{ $slot }}
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

