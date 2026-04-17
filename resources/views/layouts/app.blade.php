<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
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

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
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
            .pwa-required .pwa-gate { display: flex; }
            .pwa-card {
                width: min(560px, 100%);
                background: #0d0d0f;
                border: 1px solid rgba(255,255,255,0.08);
                border-radius: 24px;
                padding: 28px;
                box-shadow: 0 16px 38px rgba(0, 0, 0, 0.45);
                color: #f1f1f1;
            }
            .pwa-eyebrow {
                text-transform: uppercase;
                letter-spacing: 4px;
                font-size: 12px;
                color: #a0a0a5;
                margin-bottom: 8px;
            }
            .pwa-title {
                font-size: 22px;
                font-weight: 700;
                margin: 0 0 10px;
            }
            .pwa-copy {
                color: #a0a0a5;
                font-size: 14px;
                line-height: 1.6;
                margin: 0 0 18px;
            }
            .pwa-steps {
                display: grid;
                gap: 14px;
            }
            .pwa-step {
                background: #131316;
                border: 1px solid rgba(255,255,255,0.08);
                border-radius: 16px;
                padding: 14px 16px;
            }
            .pwa-step h3 {
                margin: 0 0 6px;
                font-size: 12px;
                letter-spacing: 2px;
                text-transform: uppercase;
                color: #7efc5b;
            }
            .pwa-step p {
                margin: 0;
                color: #a0a0a5;
                font-size: 13px;
                line-height: 1.5;
            }
        </style>
    </head>
    <body class="bg-light">
        <div class="min-vh-100 d-flex flex-column">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white border-bottom">
                    <div class="container py-4">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-grow-1">
                <div class="container py-4">
                    {{ $slot }}
                </div>
            </main>
        </div>
        <div class="pwa-gate" id="pwaGate" role="dialog" aria-modal="true" aria-label="Installazione app Reverbia">
            <div class="pwa-card">
                <div class="pwa-eyebrow">Reverbia</div>
                <h1 class="pwa-title">Installa l'app per utilizzarla</h1>
                <p class="pwa-copy">Servono pochi passi. Una volta installata, apri Reverbia dalla schermata Home.</p>
                <div class="pwa-steps">
                    <div class="pwa-step">
                        <h3>Android (Chrome)</h3>
                        <p>Apri il menu (tre puntini) e scegli "Installa app" o "Aggiungi a schermata Home".</p>
                    </div>
                    <div class="pwa-step">
                        <h3>iOS (Safari)</h3>
                        <p>Tocca Condividi e poi "Aggiungi a schermata Home".</p>
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
    </body>
</html>

