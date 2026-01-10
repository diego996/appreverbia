@push('styles')
    <style>
        main.page-profile { padding: 12px 14px 90px; }
        body.modal-open { overflow: hidden; }
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
            gap: 8px;
            margin-top: 10px;
            flex-wrap: wrap;
        }
        .btn-cancel {
            background: transparent;
            color: var(--accent-2);
            border: 1px solid rgba(243,90,167,0.5);
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 12px;
        }
        .btn-duetto {
            background: rgba(126,252,91,0.18);
            color: var(--accent);
            border: 1px solid rgba(126,252,91,0.5);
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
                            @if ($lesson['can_confirm_duetto'])
                                <button class="btn-duetto" type="button" wire:click="openDuettoConfirmModal({{ $lesson['booking_id'] }})">
                                    Conferma duetto
                                </button>
                            @endif
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

        <div class="rv-modal" data-modal="cancel-booking" aria-hidden="true">
        <div class="rv-modal-backdrop" data-modal-close></div>
        <div class="rv-modal-panel">
            <button class="rv-modal-close" type="button" data-modal-close aria-label="Chiudi modal">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="modal-card">
                <div>
                    <div class="modal-title">Conferma disdetta</div>
                    <div class="modal-meta">
                        {{ $confirmingLesson['title'] ?? 'Lezione' }} - {{ $confirmingLesson['date'] ?? '' }}
                        @if (!empty($confirmingLesson['time']))
                            - {{ $confirmingLesson['time'] }}
                        @endif
                    </div>
                    <div class="modal-meta">
                        Trainer: {{ $confirmingLesson['trainer'] ?? 'Trainer' }} - {{ $confirmingLesson['branch'] ?? 'Sede' }}
                    </div>
                </div>

                @if ($cancelError)
                    <div class="modal-error">{{ $cancelError }}</div>
                @endif

                <div class="modal-actions">
                    <button class="btn-secondary" type="button" data-modal-close>Annulla</button>
                    <button class="btn-primary" type="button" wire:click="confirmCancelBooking">Conferma disdetta</button>
                </div>
            </div>
        </div>
    </div>

    <div class="rv-modal" data-modal="confirm-duetto" aria-hidden="true">
        <div class="rv-modal-backdrop" data-modal-close></div>
        <div class="rv-modal-panel">
            <button class="rv-modal-close" type="button" data-modal-close aria-label="Chiudi modal">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="modal-card">
                <div>
                    <div class="modal-title">Conferma duetto</div>
                    <div class="modal-meta">
                        {{ $confirmingDuettoLesson['title'] ?? 'Lezione' }} - {{ $confirmingDuettoLesson['date'] ?? '' }}
                        @if (!empty($confirmingDuettoLesson['time']))
                            - {{ $confirmingDuettoLesson['time'] }}
                        @endif
                    </div>
                    <div class="modal-meta">
                        Trainer: {{ $confirmingDuettoLesson['trainer'] ?? 'Trainer' }} - {{ $confirmingDuettoLesson['branch'] ?? 'Sede' }}
                    </div>
                </div>

                @if ($duettoError)
                    <div class="modal-error">{{ $duettoError }}</div>
                @endif

                <div class="modal-actions">
                    <button class="btn-secondary" type="button" data-modal-close>Annulla</button>
                    <button class="btn-primary" type="button" wire:click="confirmDuettoBooking">Conferma</button>
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

            const setupModalHandlers = () => {
                if (window.__reverbiaProfileModalHandlers) return;
                window.__reverbiaProfileModalHandlers = true;

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

            document.addEventListener('DOMContentLoaded', setupModalHandlers);
            document.addEventListener('livewire:navigated', setupModalHandlers);
        })();
    </script>
@endpush
