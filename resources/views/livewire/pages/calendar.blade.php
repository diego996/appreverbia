@push('styles')
    <style>
        main.page-calendar { padding: 16px 16px 110px; }
        body.modal-open { overflow: hidden; }
        .calendar-shell {
            max-width: 780px;
            margin: 0 auto;
            display: grid;
            gap: 18px;
            position: relative;
        }
        .filters-panel {
            background: #0b0b0e;
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 14px;
            box-shadow: var(--shadow);
            display: grid;
            gap: 12px 14px;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            min-width: 0;
        }
        .filter-select {
            background: #111114;
            border: 1px solid #1f1f22;
            border-radius: 12px;
            padding: 10px 12px;
            color: var(--text);
            font-size: 14px;
        }
        .title-block {
            text-align: center;
            margin: 14px 0 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            font-size: 18px;
        }
        .title-block span { color: var(--accent); }
        .calendar-card {
            background: linear-gradient(180deg, #f4f3f7 0%, #ededf2 100%);
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 20px;
            padding: 16px 16px 18px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
            color: #17171a;
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
            color: #6b6b74;
        }
        .calendar-header .date-title {
            font-size: 20px;
            font-weight: 700;
            color: #141416;
        }
        .calendar-header button {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            background: #e7e6ee;
            border: none;
            color: #4f4f57;
            font-size: 16px;
        }
        .month-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #5a5a62;
            margin: 10px 0 8px;
            font-size: 13px;
        }
        .month-row button {
            background: #ececf1;
            border: none;
            color: #5a5a62;
            font-size: 18px;
            width: 32px;
            height: 28px;
            border-radius: 10px;
        }
        .weekday-row, .week-row {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 4px;
            text-align: center;
            color: #8a8a92;
            font-size: 11px;
            margin-bottom: 4px;
        }
        .weekday-row {
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }
        .day {
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            border: 1px solid #e2e2e6;
            color: #1b1b1e;
            background: #ffffff;
            cursor: pointer;
            transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease;
            font-weight: 600;
            width: min(38px, 100%);
            aspect-ratio: 1 / 1;
            justify-self: center;
        }
        .day.selected {
            background: #7efc5b;
            border-color: #7efc5b;
            color: #0a0a0a;
            font-weight: 700;
            box-shadow: 0 6px 14px rgba(126,252,91,0.45);
        }
        .day.busy {
            background: #ffffff;
            border-color: rgba(243,90,167,0.5);
            color: #f35aa7;
        }
        .day:hover { transform: translateY(-1px); }
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
        .list-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 4px;
            flex-wrap: wrap;
        }
        .btn-detail {
            background: transparent;
            border: 1px solid #2a2a2f;
            color: var(--muted);
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
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
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-cta.is-secondary {
            background: #131316;
            color: var(--text);
            border: 1px solid var(--line);
        }
        .btn-cta.is-waitlist {
            background: rgba(243,90,167,0.18);
            color: var(--accent-2);
            border: 1px solid rgba(243,90,167,0.5);
        }
        .btn-cta.is-disabled {
            opacity: 0.7;
            cursor: not-allowed;
            box-shadow: none;
        }
        .course-overlay {
            position: fixed;
            inset: 0;
            z-index: 35;
            display: grid;
            place-items: center;
            padding: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }
        .course-overlay.is-open {
            opacity: 1;
            pointer-events: auto;
        }
        .course-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(6, 6, 8, 0.78);
            backdrop-filter: blur(12px);
        }
        .course-card {
            position: relative;
            width: min(420px, 100%);
            background: #0f0f12;
            border-radius: 22px;
            padding: 18px;
            border: 1px solid rgba(255,255,255,0.06);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6);
            display: grid;
            gap: 12px;
        }
        .course-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid #2a2a2f;
            background: #141418;
            color: var(--text);
            display: grid;
            place-items: center;
        }
        .course-eyebrow {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: var(--muted);
        }
        .course-title {
            font-size: 20px;
            font-weight: 700;
        }
        .course-title span { color: var(--accent); }
        .course-meta {
            color: var(--muted);
            font-size: 13px;
        }
        .course-media {
            height: 180px;
            border-radius: 18px;
            background: linear-gradient(140deg, rgba(126,252,91,0.2), rgba(243,90,167,0.15)), #0b0b0e;
            border: 1px solid rgba(255,255,255,0.05);
            position: relative;
            overflow: hidden;
        }
        .course-media::after {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                120deg,
                rgba(255,255,255,0.04),
                rgba(255,255,255,0.04) 14px,
                transparent 14px,
                transparent 28px
            );
            opacity: 0.4;
        }
        .course-tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .course-tags span {
            padding: 6px 10px;
            background: #131317;
            border-radius: 999px;
            color: var(--muted);
            font-size: 11px;
            border: 1px solid #242428;
        }
        .course-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }
        .btn-ghost {
            background: transparent;
            border: 1px solid #2a2a2f;
            color: var(--muted);
            border-radius: 999px;
            padding: 9px 14px;
            font-size: 12px;
        }
        .rv-modal {
            position: fixed;
            inset: 0;
            display: grid;
            place-items: center;
            padding: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
            z-index: 40;
        }
        .rv-modal.is-open {
            opacity: 1;
            pointer-events: auto;
        }
        .rv-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(4, 4, 6, 0.72);
            backdrop-filter: blur(10px);
        }
        .rv-modal-panel {
            position: relative;
            width: min(520px, calc(100% - 32px));
            margin: 0 auto;
        }
        .rv-modal-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid #2a2a2f;
            background: #111114;
            color: var(--text);
            display: grid;
            place-items: center;
            z-index: 2;
        }
        .modal-card {
            background: #0f0f12;
            border-radius: 16px;
            padding: 18px;
            display: grid;
            gap: 12px;
        }
        .modal-title {
            font-size: 18px;
            font-weight: 700;
        }
        .modal-meta {
            color: var(--muted);
            font-size: 13px;
        }
        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 6px;
        }
        .btn-secondary {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 9px 14px;
            font-size: 13px;
        }
        .btn-primary {
            background: var(--accent);
            color: #0a0a0a;
            border: none;
            border-radius: 999px;
            padding: 9px 14px;
            font-size: 13px;
            font-weight: 700;
        }
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .modal-note {
            background: rgba(126,252,91,0.12);
            border: 1px solid rgba(126,252,91,0.3);
            color: var(--accent);
            padding: 8px 10px;
            border-radius: 10px;
            font-size: 12px;
        }
        .modal-error {
            background: rgba(243,90,167,0.12);
            border: 1px solid rgba(243,90,167,0.4);
            color: var(--accent-2);
            padding: 8px 10px;
            border-radius: 10px;
            font-size: 12px;
        }
        .duetto-toggle {
            display: flex;
            gap: 10px;
            align-items: center;
            font-size: 13px;
            color: var(--muted);
        }
        .duetto-toggle input {
            width: 18px;
            height: 18px;
            accent-color: var(--accent);
        }
        @media (max-width: 640px) {
            .filters-panel {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

<div id="calendar-root">
    <main class="page-calendar">
        <div class="calendar-shell">
            <div class="filters-panel">
                <label class="filter-group">
                    Sede
                    <select class="filter-select" wire:model="selectedBranch">
                        <option value="">Qualsiasi</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch['id'] }}">{{ $branch['name'] }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="filter-group">
                    Trainer
                    <select class="filter-select" wire:model="selectedTrainer">
                        <option value="">Qualsiasi</option>
                        @foreach ($trainers as $trainer)
                            <option value="{{ $trainer['id'] }}">{{ $trainer['name'] }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="filter-group">
                    Giorno
                    <select class="filter-select" wire:model="selectedWeekday">
                        <option value="">Qualsiasi</option>
                        @foreach ($calendar['weekdays'] as $weekday)
                            <option value="{{ $weekday }}">{{ $weekday }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="filter-group">
                    Corso
                    <select class="filter-select" wire:model="selectedCourse">
                        <option value="">Qualsiasi</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course['id'] }}">{{ $course['title'] }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="title-block">
                CALENDARIO <span>LEZIONI</span>
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
                    <button type="button" aria-label="Mese precedente" wire:click="previousMonth">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div>{{ $calendar['monthLabel'] }}</div>
                    <button type="button" aria-label="Mese successivo" wire:click="nextMonth">
                        <i class="bi bi-chevron-right"></i>
                    </button>
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
                                <div class="{{ implode(' ', $classes) }}" wire:click="selectDay({{ $day }})">{{ $day }}</div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </section>

            @if (!empty($lessonCards))
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
                            <div class="list-actions">
                                <button class="btn-detail js-course-detail" type="button"
                                        data-course-id="{{ $card['id'] }}"
                                        data-course-title="{{ $card['title'] }}"
                                        data-course-trainer="{{ $card['trainer'] }}"
                                        data-course-category="{{ $card['category'] }}"
                                        data-course-tags='@json($card['tags'])'
                                        data-course-cta="{{ $card['cta'] }}"
                                        data-course-cta-variant="{{ $card['cta_variant'] }}"
                                        data-course-cta-disabled="{{ $card['cta_disabled'] ? '1' : '0' }}"
                                        data-course-action="{{ $card['action'] ?? '' }}"
                                        data-course-target="cta-{{ $card['id'] }}">
                                    Dettagli <i class="bi bi-arrow-up-right"></i>
                                </button>
                                <button class="btn-cta {{ $card['cta_variant'] }} {{ $card['cta_disabled'] ? 'is-disabled' : '' }}"
                                        type="button"
                                        id="cta-{{ $card['id'] }}"
                                        @if ($card['cta_disabled']) disabled @endif
                                        @if ($card['action']) wire:click="openBookingModal({{ $card['occurrence_id'] }}, '{{ $card['action'] }}')" @endif>
                                    {{ $card['cta'] }}
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            @else
                <article class="list-card">
                    <div class="thumb" aria-hidden="true"></div>
                    <div class="list-body d-flex flex-column">
                        <div class="category">Nessuna lezione</div>
                        <div class="title">Non ci sono lezioni disponibili</div>
                        <div class="trainer">Prova a modificare i filtri o il giorno.</div>
                    </div>
                </article>
            @endif
        </div>
    </main>

    <div class="course-overlay" data-course-overlay aria-hidden="true">
        <div class="course-backdrop" data-course-close></div>
        <div class="course-card">
            <button class="course-close" type="button" data-course-close>
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="course-eyebrow" data-course-eyebrow>Corso</div>
            <div class="course-title">
                Lezione <span data-course-title>Reverbia</span>
            </div>
            <div class="course-meta">
                Trainer: <span data-course-trainer>Trainer</span>
            </div>
            <div class="course-media" aria-hidden="true"></div>
            <div class="course-tags" data-course-tags></div>
            <div class="course-actions">
                <button class="btn-ghost" type="button" data-course-close>Chiudi</button>
                <button class="btn-cta" type="button" data-course-cta>Prenota ora</button>
            </div>
        </div>
    </div>

    <div class="rv-modal" data-modal="booking-confirm" aria-hidden="true">
        <div class="rv-modal-backdrop" data-modal-close></div>
        <div class="rv-modal-panel">
            <button class="rv-modal-close" type="button" data-modal-close aria-label="Chiudi modal">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="modal-card">
                <div>
                    <div class="modal-title">
                        {{ $confirmingAction === 'waitlist' ? 'Conferma lista d\'attesa' : 'Conferma prenotazione' }}
                    </div>
                    <div class="modal-meta">
                        {{ $confirmingDetails['title'] ?? 'Lezione' }} - {{ $confirmingDetails['date'] ?? '' }}
                        @if (!empty($confirmingDetails['time']))
                            - {{ $confirmingDetails['time'] }}
                        @endif
                    </div>
                    <div class="modal-meta">
                        Trainer: {{ $confirmingDetails['trainer'] ?? 'Trainer' }} - {{ $confirmingDetails['branch'] ?? 'Sede' }}
                    </div>
                </div>

                @php
                    $hasAction = in_array($confirmingAction, ['book', 'waitlist'], true);
                    $requiredTokens = $confirmingAction === 'book' ? 1 : 0;
                    $duettoInsufficient = $confirmDuetto && $duettoTokens !== null && $duettoTokens < 1;
                    $insufficientTokens = $confirmingAction === 'book' && ($availableTokens < 1 || $duettoInsufficient);
                    $requiredLabel = $confirmDuetto ? '2 (1 ciascuno)' : '1';
                    $hasBlockingError = $insufficientTokens || $bookingError || !$hasAction;
                @endphp

                @if ($hasAction)
                    <div class="modal-note">
                        Token tuoi: {{ $availableTokens }}
                        @if ($confirmDuetto && $duettoTokens !== null)
                            - Duetto: {{ $duettoTokens }}
                        @endif
                        - Richiesti: {{ $requiredLabel }}
                    </div>
                @endif

                @if ($confirmingAction === 'book' && $hasDuetto)
                    <label class="duetto-toggle">
                        <input type="checkbox" wire:model="confirmDuetto">
                        Prenota in duetto {{ $duettoName ? 'con ' . $duettoName : '' }}
                    </label>
                @endif

                @if ($bookingError)
                    <div class="modal-error">{{ $bookingError }}</div>
                @endif
                @if ($insufficientTokens)
                    <div class="modal-error">Token insufficienti per questa prenotazione.</div>
                @endif
                @if (!$hasAction && !$bookingError)
                    <div class="modal-error">Seleziona una lezione valida.</div>
                @endif

                <div class="modal-actions">
                    <button class="btn-secondary" type="button" data-modal-close>Annulla</button>
                    <button class="btn-primary" type="button" wire:click="confirmBooking" @if ($hasBlockingError) disabled @endif>Conferma</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function () {
            const openModal = (name) => {
                if (!name) return;
                const modal = document.querySelector(`[data-modal="${name}"]`);
                if (!modal) return;
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('modal-open');
            };

            const closeModal = (name, modalEl = null) => {
                const modal = modalEl || document.querySelector(`[data-modal="${name}"]`);
                if (!modal) return;
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('modal-open');
            };

            const setupGlobalModalHandlers = () => {
                if (window.__reverbiaModalHandlers) return;
                window.__reverbiaModalHandlers = true;

                window.addEventListener('open-modal', (event) => {
                    const name = typeof event.detail === 'string' ? event.detail : event.detail?.name;
                    openModal(name);
                });

                window.addEventListener('close-modal', (event) => {
                    const name = typeof event.detail === 'string' ? event.detail : event.detail?.name;
                    closeModal(name);
                });

                document.addEventListener('click', (event) => {
                    const closeTrigger = event.target.closest('[data-modal-close]');
                    if (!closeTrigger) return;
                    const modal = closeTrigger.closest('[data-modal]');
                    if (modal) {
                        closeModal(null, modal);
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key !== 'Escape') return;
                    const modal = document.querySelector('[data-modal].is-open');
                    if (modal) {
                        closeModal(null, modal);
                    }
                });
            };

            const setupCourseOverlay = () => {
                const root = document.getElementById('calendar-root');
                if (!root || root.__courseOverlayInit) return;
                root.__courseOverlayInit = true;

                const overlay = root.querySelector('[data-course-overlay]');
                if (!overlay) return;

                const closeOverlay = () => {
                    overlay.classList.remove('is-open');
                    overlay.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('modal-open');
                };

                const openOverlay = (data) => {
                    overlay.classList.add('is-open');
                    overlay.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('modal-open');

                    const eyebrow = overlay.querySelector('[data-course-eyebrow]');
                    const title = overlay.querySelector('[data-course-title]');
                    const trainer = overlay.querySelector('[data-course-trainer]');
                    const tags = overlay.querySelector('[data-course-tags]');
                    const cta = overlay.querySelector('[data-course-cta]');

                    if (eyebrow) eyebrow.textContent = data.category || 'Corso';
                    if (title) title.textContent = data.title || 'Reverbia';
                    if (trainer) trainer.textContent = data.trainer || 'Trainer';

                    if (tags) {
                        tags.innerHTML = '';
                        (data.tags || []).forEach((tag) => {
                            const span = document.createElement('span');
                            span.textContent = tag;
                            tags.appendChild(span);
                        });
                    }

                    if (cta) {
                        cta.textContent = data.cta || 'Prenota ora';
                        cta.className = `btn-cta ${data.ctaVariant || ''} ${data.ctaDisabled ? 'is-disabled' : ''}`.trim();
                        cta.disabled = !data.action || data.ctaDisabled;
                        cta.dataset.ctaTarget = data.ctaTarget || '';
                    }
                };

                root.addEventListener('click', (event) => {
                    const detailButton = event.target.closest('.js-course-detail');
                    if (!detailButton) return;

                    let tags = [];
                    if (detailButton.dataset.courseTags) {
                        try {
                            tags = JSON.parse(detailButton.dataset.courseTags);
                        } catch (e) {
                            tags = [];
                        }
                    }

                    openOverlay({
                        title: detailButton.dataset.courseTitle,
                        trainer: detailButton.dataset.courseTrainer,
                        category: detailButton.dataset.courseCategory,
                        tags,
                        cta: detailButton.dataset.courseCta,
                        ctaVariant: detailButton.dataset.courseCtaVariant,
                        ctaDisabled: detailButton.dataset.courseCtaDisabled === '1',
                        action: detailButton.dataset.courseAction,
                        ctaTarget: detailButton.dataset.courseTarget,
                    });
                });

                overlay.addEventListener('click', (event) => {
                    if (event.target.closest('[data-course-close]')) {
                        closeOverlay();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && overlay.classList.contains('is-open')) {
                        closeOverlay();
                    }
                });

                const ctaButton = overlay.querySelector('[data-course-cta]');
                if (ctaButton) {
                    ctaButton.addEventListener('click', () => {
                        const targetId = ctaButton.dataset.ctaTarget;
                        if (!targetId || ctaButton.disabled) return;
                        const target = document.getElementById(targetId);
                        if (target) {
                            closeOverlay();
                            target.click();
                        }
                    });
                }
            };

            const init = () => {
                setupGlobalModalHandlers();
                setupCourseOverlay();
            };

            document.addEventListener('DOMContentLoaded', init);
            document.addEventListener('livewire:navigated', init);
        })();
    </script>
@endpush
