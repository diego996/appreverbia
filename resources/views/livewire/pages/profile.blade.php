@push('styles')
    <style>
        main.page-profile { padding: 12px 14px 90px; }
        .section-block { margin-bottom: 22px; }
        .section-title {
            font-size: 12px;
            letter-spacing: 0.08em;
            color: var(--muted);
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .card-panel {
            background: #0b0b0e;
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 16px;
            box-shadow: var(--shadow);
        }
        .info-grid {
            display: grid;
            gap: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            color: var(--muted);
            font-size: 13px;
        }
        .info-row strong { color: var(--text); font-weight: 600; }
        .lesson-list {
            display: grid;
            gap: 12px;
        }
        .lesson-item {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 12px;
            background: #0f0f12;
        }
        .lesson-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }
        .btn-cancel {
            background: transparent;
            color: var(--accent-2);
            border: 1px solid rgba(243,90,167,0.5);
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 12px;
        }
        .lesson-item .title {
            font-weight: 700;
            margin: 6px 0;
        }
        .lesson-item .meta {
            color: var(--muted);
            font-size: 12px;
        }
        .lesson-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--muted);
            font-size: 12px;
        }
        .badge-status {
            background: rgba(126,252,91,0.12);
            border: 1px solid rgba(126,252,91,0.4);
            color: var(--accent);
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 11px;
        }
        .empty-state {
            color: var(--muted);
            font-size: 13px;
            text-align: center;
            padding: 18px 12px;
        }
        .link-list {
            display: grid;
            gap: 10px;
        }
        .link-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border-radius: 12px;
            background: #0f0f12;
            border: 1px solid var(--line);
            color: var(--text);
            text-decoration: none;
        }
        .link-item span { color: var(--muted); font-size: 12px; }
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
            background: rgba(243,90,167,0.18);
            color: var(--accent-2);
            border: 1px solid rgba(243,90,167,0.5);
            border-radius: 999px;
            padding: 9px 14px;
            font-size: 13px;
            font-weight: 700;
        }
        .modal-error {
            background: rgba(243,90,167,0.12);
            border: 1px solid rgba(243,90,167,0.4);
            color: var(--accent-2);
            padding: 8px 10px;
            border-radius: 10px;
            font-size: 12px;
        }
    </style>
@endpush

<div>
    <main class="page-profile">
        <section class="section-block">
            <div class="section-title">Dati utente</div>
            <div class="card-panel">
                <div class="info-grid">
                    <div class="info-row">
                        <strong>{{ $userInfo['name'] ?? 'Utente' }}</strong>
                        <span>{{ $userInfo['status'] ?? 'attivo' }}</span>
                    </div>
                    <div class="info-row">
                        <span>Email</span>
                        <strong>{{ $userInfo['email'] ?? '-' }}</strong>
                    </div>
                    <div class="info-row">
                        <span>Telefono</span>
                        <strong>{{ $userInfo['phone'] ?? '-' }}</strong>
                    </div>
                    <div class="info-row">
                        <span>Sede</span>
                        <strong>{{ $userInfo['branch'] ?? 'Non assegnata' }}</strong>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-block">
            <div class="section-title">Lezioni in programma</div>
            <div class="lesson-list">
                @forelse ($upcomingLessons as $lesson)
                    <article class="lesson-item">
                        <div class="lesson-top">
                            <span>{{ strtoupper($lesson['date']) }} - {{ $lesson['time'] }}</span>
                            <span class="badge-status">{{ $lesson['status'] }}</span>
                        </div>
                        <div class="title">{{ $lesson['title'] }}</div>
                        <div class="meta">Trainer: {{ $lesson['trainer'] }}</div>
                        <div class="meta">Sede: {{ $lesson['location'] }}</div>
                        <div class="lesson-actions">
                            <button class="btn-cancel" type="button" wire:click="openCancelModal({{ $lesson['booking_id'] }})">
                                Disdici
                            </button>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">Nessuna lezione futura al momento.</div>
                @endforelse
            </div>
        </section>

        <section class="section-block">
            <div class="section-title">Storico lezioni</div>
            <div class="lesson-list">
                @forelse ($historyLessons as $lesson)
                    <article class="lesson-item">
                        <div class="lesson-top">
                            <span>{{ strtoupper($lesson['date']) }} - {{ $lesson['time'] }}</span>
                            <span class="badge-status">{{ $lesson['status'] }}</span>
                        </div>
                        <div class="title">{{ $lesson['title'] }}</div>
                        <div class="meta">Trainer: {{ $lesson['trainer'] }}</div>
                        <div class="meta">Sede: {{ $lesson['location'] }}</div>
                    </article>
                @empty
                    <div class="empty-state">Nessuna lezione passata.</div>
                @endforelse
            </div>
        </section>

        <section class="section-block">
            <div class="section-title">Duetto</div>
            <div class="card-panel">
                @if ($duetto)
                    <div class="info-grid">
                        <div class="info-row">
                            <span>Nome</span>
                            <strong>{{ $duetto['name'] }}</strong>
                        </div>
                        <div class="info-row">
                            <span>Email</span>
                            <strong>{{ $duetto['email'] ?? '-' }}</strong>
                        </div>
                        <div class="info-row">
                            <span>Telefono</span>
                            <strong>{{ $duetto['phone'] ?? '-' }}</strong>
                        </div>
                    </div>
                @else
                    <div class="empty-state">Nessun duetto associato al profilo.</div>
                @endif
            </div>
        </section>

        <section class="section-block">
            <div class="section-title">Link utili</div>
            <div class="link-list">
                @foreach ($usefulLinks as $link)
                    <a class="link-item" href="{{ $link['url'] }}">
                        <strong>{{ $link['label'] }}</strong>
                        <span>Apri</span>
                    </a>
                @endforeach
            </div>
        </section>
    </main>

    <x-modal name="cancel-booking" maxWidth="md" :show="false" focusable>
        <div class="modal-card">
            <div>
                <div class="modal-title">Conferma disdetta</div>
                <div class="modal-meta">
                    {{ $confirmingLesson['title'] ?? 'Lezione' }} · {{ $confirmingLesson['date'] ?? '' }}
                    @if (!empty($confirmingLesson['time']))
                        · {{ $confirmingLesson['time'] }}
                    @endif
                </div>
                <div class="modal-meta">
                    Trainer: {{ $confirmingLesson['trainer'] ?? 'Trainer' }} · {{ $confirmingLesson['branch'] ?? 'Sede' }}
                </div>
            </div>

            @if ($cancelError)
                <div class="modal-error">{{ $cancelError }}</div>
            @endif

            <div class="modal-actions">
                <button class="btn-secondary" type="button" x-on:click="$dispatch('close-modal', 'cancel-booking')">Annulla</button>
                <button class="btn-primary" type="button" wire:click="confirmCancelBooking">Conferma disdetta</button>
            </div>
        </div>
    </x-modal>
</div>
