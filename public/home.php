<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Reverbia - Home', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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
            background: linear-gradient(180deg, rgba(126,252,91,0.08), rgba(5,5,5,0.94));
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
        main { padding: 10px 14px 90px; }
        .token-card {
            background: radial-gradient(circle at 30% 15%, rgba(126,252,91,0.08), transparent 35%), #0a0a0b;
            border-radius: 24px;
            padding: 20px 18px 26px;
            border: 1px solid var(--line);
            box-shadow: var(--shadow);
        }
        .eyebrow {
            font-size: 12px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--accent);
            font-weight: 700;
        }
        .user-pill {
            border: 1px solid var(--line);
            background: #0f0f13;
            padding: 6px 12px;
            border-radius: 999px;
            color: var(--muted);
        }
        .donut {
            --percent: <?= max(0, min(100, (int)($tokens['percentage'] ?? 0))) ?>;
            width: min(72vw, 240px);
            aspect-ratio: 1/1;
            margin: 16px auto 10px;
            border-radius: 50%;
            position: relative;
            display: grid;
            place-items: center;
        }
        .donut::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background:
                radial-gradient(circle at center, #0a0a0b 62%, transparent 62%),
                repeating-conic-gradient(
                    from -90deg,
                    #2b2b30 0 9deg,
                    #141417 9deg 14deg
                );
        }
        .donut::after {
            content: "";
            position: absolute;
            inset: 2px;
            border-radius: 50%;
            background:
                conic-gradient(from -90deg, var(--accent-2) calc(var(--percent) * 1%), transparent 0);
            mask: radial-gradient(circle at center, transparent 55%, black 57%);
        }
        .donut-value {
            position: relative;
            text-align: center;
            z-index: 1;
        }
        .donut-value strong { font-size: 30px; display: block; }
        .donut-value span { font-size: 13px; color: var(--muted); }
        .token-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 12px;
        }
        .token-stat {
            background: #0f0f12;
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 10px 12px;
            text-align: center;
        }
        .token-stat .label { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; }
        .token-stat .value { font-size: 18px; font-weight: 700; margin-top: 4px; }
        .dot-indicators {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 10px;
        }
        .dot-indicators span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #2f2f33;
        }
        .dot-indicators span.active { background: var(--text); }
        .slider-block { margin-top: 28px; }
        .section-title {
            font-size: 12px;
            letter-spacing: 0.08em;
            color: var(--muted);
            text-transform: uppercase;
            font-weight: 700;
        }
        .slider-window {
            overflow: hidden;
            border-radius: 22px;
            margin-top: 12px;
        }
        .slider-track {
            display: flex;
            gap: 18px;
            transition: transform 0.3s ease;
            transform: translateX(calc(var(--index, 0) * -100%));
        }
        .slider-card {
            flex: 0 0 100%;
            background: linear-gradient(150deg, #19191d, #0f0f11);
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 16px;
            min-height: 160px;
            color: var(--text);
            box-shadow: var(--shadow);
        }
        .slider-card .tag {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 4px;
            display: block;
        }
        .slider-card .title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .slider-card .coach { color: var(--muted); font-size: 13px; }
        .slider-card .cta {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 14px;
            border-radius: 12px;
            background: var(--accent);
            color: #0a0a0a;
            font-weight: 700;
            text-decoration: none;
        }
        .slider-card .cta.link {
            background: transparent;
            color: var(--accent);
            border: 1px solid var(--accent);
        }
        .slider-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
        }
        .slider-dots button {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: none;
            background: #26262a;
            padding: 0;
        }
        .slider-dots button.active { background: var(--accent); }
        .bottom-link {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            margin-top: 6px;
        }
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
            background: rgba(0,0,0,0.8);
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
    </style>
</head>
<body>
    <header class="topbar">
        <button id="menuToggle" class="hamburger" aria-label="Apri menu">
            <span></span>
        </button>
        <div class="brand" aria-label="Reverbia">
            <span>RE</span>VER<span>B</span>IA
        </div>
        <div class="top-icons" aria-label="Azioni rapide">
            <i class="bi bi-cart3"></i>
            <i class="bi bi-bell"></i>
        </div>
    </header>

    <main>
        <section class="token-card" aria-labelledby="token-balance">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="eyebrow mb-1">Lezioni rimanenti</div>
                    <div id="token-balance" class="fw-semibold">Saldo token aggiornato</div>
                </div>
                <div class="user-pill small"><?= e(optional($user)->name ?? 'Ospite') ?></div>
            </div>
            <div class="donut" role="img" aria-label="<?= (int)$tokens['percentage'] ?>% dei token ancora disponibili">
                <div class="donut-value">
                    <strong><?= (int)$tokens['percentage'] ?>%</strong>
                    <span>token disponibili</span>
                </div>
            </div>
            <div class="token-stats">
                <div class="token-stat">
                    <div class="label">Token totali</div>
                    <div class="value"><?= (int)$tokens['total'] ?></div>
                </div>
                <div class="token-stat">
                    <div class="label">Token prenotati</div>
                    <div class="value"><?= (int)$tokens['booked'] ?></div>
                </div>
                <div class="token-stat">
                    <div class="label">Token disponibili</div>
                    <div class="value"><?= (int)$tokens['available'] ?></div>
                </div>
            </div>
            <div class="dot-indicators" aria-hidden="true">
                <span></span><span></span><span class="active"></span><span></span><span></span>
            </div>
        </section>

        <section class="slider-block" data-slider>
            <div class="d-flex justify-content-between align-items-center">
                <div class="section-title">Le tue prossime lezioni prenotate</div>
            </div>
            <div class="slider-window mt-3">
                <div class="slider-track" data-track>
                    <?php foreach ($bookedLessons as $lesson): ?>
                        <?php if (!empty($lesson['empty'])): ?>
                            <article class="slider-card">
                                <div class="title"><?= e($lesson['message']) ?></div>
                                <div class="coach"><?= e($lesson['hint']) ?></div>
                            </article>
                        <?php else: ?>
                            <article class="slider-card">
                                <span class="tag"><?= e(strtoupper($lesson['date'])) ?> - <?= e($lesson['time']) ?></span>
                                <div class="title"><?= e($lesson['title']) ?></div>
                                <div class="coach mb-2">Coach: <?= e($lesson['coach']) ?></div>
                                <div class="coach text-success fw-semibold"><?= e($lesson['status']) ?></div>
                            </article>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="slider-dots" data-dots>
                <?php foreach ($bookedLessons as $index => $lesson): ?>
                    <button type="button" aria-label="Slide <?= $index + 1 ?>" data-slide="<?= $index ?>"></button>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="slider-block" data-slider>
            <div class="d-flex justify-content-between align-items-center">
                <div class="section-title text-success">Corsi disponibili per la tua sede</div>
            </div>
            <div class="slider-window mt-3">
                <div class="slider-track" data-track>
                    <?php foreach ($availableCourses as $course): ?>
                        <article class="slider-card">
                            <span class="tag"><?= e(($course['tag'] ?? '') . ' - ' . $course['time']) ?></span>
                            <div class="title"><?= e($course['title']) ?></div>
                            <div class="coach mb-2">Coach: <?= e($course['coach']) ?></div>
                            <a href="#" class="cta link"><?= e($course['cta']) ?></a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="slider-dots" data-dots>
                <?php foreach ($availableCourses as $index => $course): ?>
                    <button type="button" aria-label="Slide <?= $index + 1 ?>" data-slide="<?= $index ?>"></button>
                <?php endforeach; ?>
            </div>
            <a href="#" class="bottom-link">
                <span>Scopri tutti i corsi disponibili</span>
                <i class="bi bi-chevron-right"></i>
            </a>
        </section>
    </main>

    <nav class="nav-bottom" aria-label="Navigazione principale">
        <a class="active" href="#"><i class="bi bi-house-door"></i><span>Home</span></a>
        <a href="#"><i class="bi bi-calendar-check"></i><span>Prenota</span></a>
        <a href="#"><i class="bi bi-heart"></i><span>Allenamenti</span></a>
        <a href="#"><i class="bi bi-chat-dots"></i><span>Supporto</span></a>
        <a href="#"><i class="bi bi-person"></i><span>Profilo</span></a>
    </nav>

    <div id="menuOverlay" class="menu-overlay" aria-hidden="true">
        <div class="menu-panel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="brand"><span>RE</span>VER<span>B</span>IA</div>
                <button class="btn-close-menu" type="button" data-close aria-label="Chiudi menu">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="menu-items">
                <?php foreach ($menuLinks as $link): ?>
                    <a href="#">
                        <i class="bi <?= e($link['icon']) ?>"></i>
                        <span><?= e($link['label']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const body = document.body;
            const toggle = document.getElementById('menuToggle');
            const overlay = document.getElementById('menuOverlay');
            const closeButtons = overlay ? overlay.querySelectorAll('[data-close]') : [];

            const closeMenu = () => body.classList.remove('menu-open');
            const openMenu = () => body.classList.add('menu-open');

            if (toggle) {
                toggle.addEventListener('click', () => {
                    body.classList.toggle('menu-open');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', (event) => {
                    if (event.target === overlay) {
                        closeMenu();
                    }
                });
            }

            closeButtons.forEach((btn) => btn.addEventListener('click', closeMenu));

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeMenu();
                }
            });

            document.querySelectorAll('[data-slider]').forEach((slider) => {
                const track = slider.querySelector('[data-track]');
                const dots = Array.from(slider.querySelectorAll('[data-slide]'));
                if (!track || dots.length === 0) {
                    return;
                }
                let index = 0;
                const setIndex = (next) => {
                    index = Math.max(0, Math.min(next, dots.length - 1));
                    track.style.setProperty('--index', index);
                    dots.forEach((dot, dotIndex) => dot.classList.toggle('active', dotIndex === index));
                };
                dots.forEach((dot, dotIndex) => {
                    dot.addEventListener('click', () => setIndex(dotIndex));
                });
                setIndex(0);
            });
        }());
    </script>
</body>
</html>
