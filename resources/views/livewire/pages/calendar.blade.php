@push('styles')
    <style>
        main.page-calendar { padding: 12px 14px 90px; }
        .filters-panel {
            background: #0b0b0e;
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 14px;
            box-shadow: var(--shadow);
            display: grid;
            gap: 12px;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
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
    </style>
@endpush

<div>
    <main class="page-calendar">
        <div class="filters-panel mt-2">
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
                        <button class="btn-cta {{ $card['cta_variant'] }} {{ $card['cta_disabled'] ? 'is-disabled' : '' }}"
                                type="button"
                                @if ($card['cta_disabled']) disabled @endif
                                @if ($card['action']) wire:click="openBookingModal({{ $card['occurrence_id'] }}, '{{ $card['action'] }}')" @endif>
                            {{ $card['cta'] }}
                        </button>
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
    </main>

    <x-modal name="booking-confirm" :show="false" focusable>
        <div class="modal-card">
            <div>
                <div class="modal-title">
                    {{ $confirmingAction === 'waitlist' ? 'Conferma lista d\'attesa' : 'Conferma prenotazione' }}
                </div>
                <div class="modal-meta">
                    {{ $confirmingDetails['title'] ?? 'Lezione' }} · {{ $confirmingDetails['date'] ?? '' }}
                    @if (!empty($confirmingDetails['time']))
                        · {{ $confirmingDetails['time'] }}
                    @endif
                </div>
                <div class="modal-meta">
                    Trainer: {{ $confirmingDetails['trainer'] ?? 'Trainer' }} · {{ $confirmingDetails['branch'] ?? 'Sede' }}
                </div>
            </div>

            @php
                $requiredTokens = $confirmingAction === 'book' ? ($confirmDuetto ? 2 : 1) : 0;
                $insufficientTokens = $confirmingAction === 'book' && $availableTokens < $requiredTokens;
                $hasBlockingError = $insufficientTokens || $bookingError;
            @endphp

            <div class="modal-note">
                Token disponibili: {{ $availableTokens }} · Richiesti: {{ $requiredTokens }}
            </div>

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

            <div class="modal-actions">
                <button class="btn-secondary" type="button" x-on:click="$dispatch('close-modal', 'booking-confirm')">Annulla</button>
                <button class="btn-primary" type="button" wire:click="confirmBooking" @if ($hasBlockingError) disabled @endif>Conferma</button>
            </div>
        </div>
    </x-modal>
</div>
