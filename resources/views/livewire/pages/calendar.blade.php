@push('styles')
    <style>
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
        .trainer-row { margin: 12px 0 16px; }
        .section-title {
            font-size: 12px;
            letter-spacing: 0.08em;
            color: var(--muted);
            text-transform: uppercase;
            font-weight: 700;
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
    </style>
@endpush

<div>
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
                @foreach ($trainers as $trainer)
                    <a class="trainer-chip" href="#{{ $trainer['id'] }}">
                        {{ $trainer['name'] }}
                        <span>{{ $trainer['specialty'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <section class="calendar-card">
            <div class="calendar-header">
                <div>
                    <div class="label">Seleziona il giorno desiderato</div>
                    <div class="date-title">{{ $calendar['selectedLabel'] }}</div>
                </div>
                <button type="button" aria-label="Modifica selezione">
                    <i class="bi bi-pencil"></i>
                </button>
            </div>
            <div class="month-row">
                <button type="button" aria-label="Mese precedente"><i class="bi bi-chevron-left"></i></button>
                <div>{{ $calendar['monthLabel'] }}</div>
                <button type="button" aria-label="Mese successivo"><i class="bi bi-chevron-right"></i></button>
            </div>
            <div class="weekday-row">
                @foreach ($calendar['weekdays'] as $day)
                    <div>{{ $day }}</div>
                @endforeach
            </div>
            @foreach ($calendar['weeks'] as $week)
                <div class="week-row">
                    @foreach ($week as $day)
                        @php
                            $classes = ['day'];
                            if ($day === $calendar['selectedDay']) {
                                $classes[] = 'selected';
                            } elseif ($day !== null && isset($calendar['specialDays'][$day])) {
                                $classes[] = 'busy';
                            }
                        @endphp
                        @if ($day === null)
                            <div></div>
                        @else
                            <div class="{{ implode(' ', $classes) }}">{{ $day }}</div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </section>

        <div class="filters-inline">
            <span>Filtra per orario</span>
            <span>Filtra per orario</span>
        </div>

        @foreach ($lessonCards as $card)
            <article class="list-card" id="{{ $card['id'] }}">
                <div class="thumb" aria-hidden="true"></div>
                <div class="list-body d-flex flex-column">
                    <div class="category">{{ $card['category'] }}</div>
                    <div class="trainer">Trainer: {{ $card['trainer'] }}</div>
                    <div class="title">{{ $card['title'] }}</div>
                    <div class="list-tags">
                        @foreach ($card['tags'] as $tag)
                            <span>{{ $tag }}</span>
                        @endforeach
                    </div>
                    <button class="btn-cta" type="button">{{ $card['cta'] }}</button>
                </div>
            </article>
        @endforeach
    </main>

    <nav class="nav-bottom" aria-label="Navigazione principale">
        <a href="{{ route('dashboard') }}" wire:navigate><i class="bi bi-house-door"></i><span>Home</span></a>
        <a href="#"><i class="bi bi-calendar-check"></i><span>Prenota</span></a>
        <a href="#"><i class="bi bi-heart"></i><span>Allenamenti</span></a>
        <a href="#"><i class="bi bi-chat-dots"></i><span>Supporto</span></a>
        <a class="{{ request()->routeIs('calendar') ? 'active' : '' }}" href="{{ route('calendar') }}" wire:navigate>
            <i class="bi bi-calendar4-week"></i><span>Calendario</span>
        </a>
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
            @foreach ($menuLinks as $link)
                <a href="{{ $link['url'] }}" @if(!str_starts_with($link['url'], '#')) wire:navigate @endif>
                    <i class="bi {{ $link['icon'] }}"></i>
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
            </div>
        </div>
    </div>
</div>
