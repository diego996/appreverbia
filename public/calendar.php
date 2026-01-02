<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Reverbia - Calendario', ENT_QUOTES, 'UTF-8') ?></title>
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
        main { padding: 12px 14px 90px; }
        .filter-row {
            color: var(--muted);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .filter-row i { color: var(--accent); }
        .title-block {
            text-align: center;
            margin: 14px 0 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            font-size: 18px;
        }
        .title-block span { color: var(--accent); }
        .calendar-card {
            background: #0b0b0e;
            border: 1px dashed rgba(126,252,91,0.5);
            border-radius: 18px;
            padding: 14px 14px 18px;
            box-shadow: var(--shadow);
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }
        .calendar-header .label {
            font-size: 12px;
            color: var(--muted);
        }
        .calendar-header .date-title {
            font-size: 20px;
            font-weight: 700;
        }
        .calendar-header button {
            background: transparent;
            border: none;
            color: var(--muted);
            font-size: 16px;
        }
        .month-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: var(--muted);
            margin: 10px 0 8px;
            font-size: 13px;
        }
        .month-row button {
            background: transparent;
            border: none;
            color: var(--muted);
            font-size: 18px;
        }
        .weekday-row, .week-row {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            text-align: center;
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 4px;
        }
        .day {
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            border: 1px solid transparent;
            color: var(--text);
        }
        .day.selected {
            background: rgba(126,252,91,0.15);
            border-color: var(--accent);
            color: var(--accent);
            font-weight: 700;
        }
        .day.busy {
            background: rgba(243,90,167,0.08);
            border-color: rgba(243,90,167,0.5);
            color: var(--accent-2);
        }
        .filters-inline {
            display: flex;
            justify-content: space-between;
            color: var(--muted);
            font-size: 12px;
            margin: 10px 2px 18px;
        }
        .trainer-row {
            margin: 12px 0 16px;
        }
        .trainer-chips {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 6px;
        }
        .trainer-chip {
            white-space: nowrap;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: #0e0e12;
            color: var(--text);
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .trainer-chip span {
            color: var(--muted);
            font-size: 11px;
        }
        .list-card {
            display: grid;
            grid-template-columns: 94px 1fr;
            gap: 14px;
            padding: 12px;
            border-radius: 14px;
            background: var(--panel-2);
            border: 1px solid var(--line);
            margin-bottom: 12px;
            box-shadow: var(--shadow);
        }
        .thumb {
            width: 100%;
            aspect-ratio: 1/1;
            border-radius: 12px;
            background: linear-gradient(135deg, #1c1c21, #0f0f12);
            position: relative;
            overflow: hidden;
        }
        .thumb::after {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                135deg,
                rgba(255,255,255,0.04),
                rgba(255,255,255,0.04) 10px,
                transparent 10px,
                transparent 20px
            );
        }
        .list-body .category {
            color: var(--muted);
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.06em;
            margin-bottom: 4px;
        }
        .list-body .title {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 8px;
        }
        .list-body .trainer {
            font-size: 13px;
            color: var(--text);
            margin-bottom: 6px;
        }
        .list-tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .list-tags span {
            padding: 6px 10px;
            background: #0f0f12;
            border-radius: 10px;
            color: var(--muted);
            font-size: 11px;
            border: 1px solid var(--line);
        }
        .btn-cta {
            background: var(--accent);
            color: #0a0a0a;
            border: none;
            border-radius: 999px;
            padding: 10px 14px;
            font-weight: 700;
            font-size: 13px;
            align-self: flex-start;
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
        <div class="filter-row mt-1">
            <i class="bi bi-geo-alt"></i>
            <div>
                <div>Filtra per Sede</div>
                <div class="small text-secondary">(preimpostato su sede cliente)</div>
            </div>
            <i class="bi bi-caret-right-fill ms-auto"></i>
        </div>

        <div class="title-block">
            CALENDARIO <span>LEZIONI</span>
        </div>

        <div class="trainer-row">
            <div class="section-title mb-2">Scegli il trainer</div>
            <div class="trainer-chips">
                <?php foreach ($trainers as $trainer): ?>
                    <a class="trainer-chip" href="#<?= e($trainer['id']) ?>">
                        <?= e($trainer['name']) ?>
                        <span><?= e($trainer['specialty']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <section class="calendar-card">
            <div class="calendar-header">
                <div>
                    <div class="label">Seleziona il giorno desiderato</div>
                    <div class="date-title"><?= e($calendar['selectedLabel']) ?></div>
                </div>
                <button type="button" aria-label="Modifica selezione">
                    <i class="bi bi-pencil"></i>
                </button>
            </div>
            <div class="month-row">
                <button type="button" aria-label="Mese precedente"><i class="bi bi-chevron-left"></i></button>
                <div><?= e($calendar['monthLabel']) ?></div>
                <button type="button" aria-label="Mese successivo"><i class="bi bi-chevron-right"></i></button>
            </div>
            <div class="weekday-row">
                <?php foreach ($calendar['weekdays'] as $day): ?>
                    <div><?= e($day) ?></div>
                <?php endforeach; ?>
            </div>
            <?php foreach ($calendar['weeks'] as $week): ?>
                <div class="week-row">
                    <?php foreach ($week as $day): ?>
                        <?php
                            $classes = ['day'];
                            if ($day === $calendar['selectedDay']) {
                                $classes[] = 'selected';
                            } elseif (isset($calendar['specialDays'][$day])) {
                                $classes[] = 'busy';
                            }
                        ?>
                        <?php if ($day === null): ?>
                            <div></div>
                        <?php else: ?>
                            <div class="<?= implode(' ', $classes) ?>"><?= e($day) ?></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </section>

        <div class="filters-inline">
            <span>Filtra per orario</span>
            <span>Filtra per orario</span>
        </div>

        <?php foreach ($lessonCards as $card): ?>
            <article class="list-card" id="<?= e($card['id']) ?>">
                <div class="thumb" aria-hidden="true"></div>
                <div class="list-body d-flex flex-column">
                    <div class="category"><?= e($card['category']) ?></div>
                    <div class="trainer">Trainer: <?= e($card['trainer']) ?></div>
                    <div class="title"><?= e($card['title']) ?></div>
                    <div class="list-tags">
                        <?php foreach ($card['tags'] as $tag): ?>
                            <span><?= e($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <button class="btn-cta" type="button"><?= e($card['cta']) ?></button>
                </div>
            </article>
        <?php endforeach; ?>
    </main>

    <nav class="nav-bottom" aria-label="Navigazione principale">
        <a href="/app_test/home"><i class="bi bi-house-door"></i><span>Home</span></a>
        <a href="#"><i class="bi bi-calendar-check"></i><span>Prenota</span></a>
        <a href="#"><i class="bi bi-heart"></i><span>Allenamenti</span></a>
        <a href="#"><i class="bi bi-chat-dots"></i><span>Supporto</span></a>
        <a class="active" href="/app_test/calendar"><i class="bi bi-calendar4-week"></i><span>Calendario</span></a>
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
        }());
    </script>
</body>
</html>
