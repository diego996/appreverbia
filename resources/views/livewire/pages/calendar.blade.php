@push('styles')
    <style>
        main.page-calendar { padding: 16px 16px 110px; }
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
            z-index: 25;
            display: grid;
            place-items: center;
            padding: 20px;
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
        .modal-content {
            background: transparent;
            border: none;
            box-shadow: none;
        }
        .modal-dialog {
            max-width: min(520px, calc(100% - 28px));
            margin: 0 auto;
            width: 100%;
            padding: 0 6px;
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
        [x-cloak] { display: none !important; }
        @media (max-width: 640px) {
            .filters-panel {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

<div x-data="{ courseOverlayOpen: false, course: {}, openCourse(detail) { this.course = detail; this.courseOverlayOpen = true; }, closeCourse() { this.courseOverlayOpen = false; } }"
     x-on:open-course.window="openCourse($event.detail)"
     x-on:keydown.escape.window="if (courseOverlayOpen) { closeCourse(); }">
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
                                <button class="btn-detail" type="button"
                                        x-on:click="$dispatch('open-course', @js([
                                            'id' => $card['id'],
                                            'title' => $card['title'],
                                            'trainer' => $card['trainer'],
                                            'category' => $card['category'],
                                            'tags' => $card['tags'],
                                            'cta' => $card['cta'],
                                            'ctaVariant' => $card['cta_variant'],
                                            'ctaDisabled' => $card['cta_disabled'],
                                            'occurrenceId' => $card['occurrence_id'],
                                            'action' => $card['action'],
                                        ]))">
                                    Dettagli <i class="bi bi-arrow-up-right"></i>
                                </button>
                                <button class="btn-cta {{ $card['cta_variant'] }} {{ $card['cta_disabled'] ? 'is-disabled' : '' }}"
                                        type="button"
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

    <div class="course-overlay" x-show="courseOverlayOpen" x-cloak x-transition.opacity>
        <div class="course-backdrop" x-on:click="closeCourse()"></div>
        <div class="course-card" x-transition>
            <button class="course-close" type="button" x-on:click="closeCourse()">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="course-eyebrow" x-text="(course.category || 'Corso')"></div>
            <div class="course-title">
                Lezione <span x-text="course.title || 'Reverbia'"></span>
            </div>
            <div class="course-meta">
                Trainer: <span x-text="course.trainer || 'Trainer'"></span>
            </div>
            <div class="course-media" aria-hidden="true"></div>
            <div class="course-tags" x-show="course.tags && course.tags.length">
                <template x-for="tag in course.tags" :key="tag">
                    <span x-text="tag"></span>
                </template>
            </div>
            <div class="course-actions">
                <button class="btn-ghost" type="button" x-on:click="closeCourse()">Chiudi</button>
                <button class="btn-cta"
                        type="button"
                        x-bind:class="(course.ctaVariant || '') + (course.ctaDisabled ? ' is-disabled' : '')"
                        x-bind:disabled="!course.action || course.ctaDisabled"
                        x-on:click="if (course.action && !course.ctaDisabled) { $wire.openBookingModal(course.occurrenceId, course.action); closeCourse(); }">
                    <span x-text="course.cta || 'Prenota ora'"></span>
                </button>
            </div>
        </div>
    </div>

    <x-modal name="booking-confirm" maxWidth="md" :show="false" focusable>
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
                $requiredTokens = $confirmingAction === 'book' ? ($confirmDuetto ? 2 : 1) : 0;
                $insufficientTokens = $confirmingAction === 'book' && $availableTokens < $requiredTokens;
                $hasBlockingError = $insufficientTokens || $bookingError || !$hasAction;
            @endphp

            @if ($hasAction)
                <div class="modal-note">
                    Token disponibili: {{ $availableTokens }} - Richiesti: {{ $requiredTokens }}
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
                <button class="btn-secondary" type="button" x-on:click="$dispatch('close-modal', 'booking-confirm')">Annulla</button>
                <button class="btn-primary" type="button" wire:click="confirmBooking" @if ($hasBlockingError) disabled @endif>Conferma</button>
            </div>
        </div>
    </x-modal>
</div>
