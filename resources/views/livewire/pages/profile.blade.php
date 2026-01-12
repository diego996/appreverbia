@push('styles')
    <style>
        main.page-profile { padding: 14px 16px 90px; }
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
        .profile-hero {
            display: grid;
            gap: 16px;
        }
        .user-card {
            display: grid;
            gap: 10px;
        }
        .user-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .user-title strong {
            font-size: 18px;
        }
        .status-pill {
            background: rgba(126, 252, 91, 0.12);
            color: var(--accent);
            border: 1px solid rgba(126, 252, 91, 0.4);
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
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
        .action-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid var(--line);
            background: #0f0f12;
            text-decoration: none;
            color: var(--text);
        }
        .action-card .left {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .action-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid rgba(126, 252, 91, 0.3);
            background: rgba(126, 252, 91, 0.12);
            display: grid;
            place-items: center;
            color: var(--accent);
        }
        .action-card p {
            margin: 0;
            font-size: 12px;
            color: var(--muted);
        }
        .action-card i.bi-chevron-right {
            color: var(--muted);
        }
        .booking-list {
            display: grid;
            gap: 12px;
        }
        .booking-item {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 12px;
            background: #0f0f12;
        }
        .booking-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--muted);
            font-size: 12px;
        }
        .booking-title {
            font-weight: 700;
            margin: 6px 0;
        }
        .booking-meta {
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
        .booking-actions {
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
        .wallet-card {
            display: grid;
            gap: 10px;
        }
        .wallet-balance {
            font-size: 26px;
            font-weight: 800;
        }
        .wallet-meta {
            color: var(--muted);
            font-size: 12px;
        }
        .empty-state {
            color: var(--muted);
            font-size: 13px;
            text-align: center;
            padding: 18px 12px;
        }
        .link-hint {
            font-size: 12px;
            color: var(--muted);
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
            <div class="section-title">Info base utente</div>
            <div class="card-panel profile-hero">
                <div class="user-card">
                    <div class="user-title">
                        <strong>{{ $userInfo['name'] ?? 'Utente' }}</strong>
                        <span class="status-pill">{{ $userInfo['status'] ?? 'attivo' }}</span>
                    </div>
                    <div class="info-grid">
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
            </div>
        </section>

        <section class="section-block">
            <div class="section-title">Contatta supporto</div>
            <a class="action-card" href="{{ route('support') }}" wire:navigate>
                <div class="left">
                    <div class="action-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <div>
                        <strong>Apri una richiesta</strong>
                        <p>Scrivi al team di supporto</p>
                    </div>
                </div>
                <i class="bi bi-chevron-right"></i>
            </a>
        </section>

        <section class="section-block">
            <div class="section-title">Le tue prenotazioni</div>
            <div class="booking-list">
                @forelse ($upcomingLessons as $lesson)
                    <article class="booking-item">
                        <div class="booking-top">
                            <span>{{ strtoupper($lesson['date']) }} - {{ $lesson['time'] }}</span>
                            <span class="badge-status">{{ $lesson['status'] }}</span>
                        </div>
                        <div class="booking-title">{{ $lesson['title'] }}</div>
                        <div class="booking-meta">Trainer: {{ $lesson['trainer'] }}</div>
                        <div class="booking-meta">Sede: {{ $lesson['location'] }}</div>
                        <div class="booking-actions">
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
            <div class="section-title">Il tuo wallet</div>
            <div class="card-panel wallet-card">
                <div>
                    <div class="wallet-balance">{{ $walletSummary['balance'] ?? 0 }} token</div>
                    <div class="wallet-meta">Saldo attuale disponibile</div>
                </div>
                <div class="wallet-meta">
                    @if (!empty($walletSummary['next_expiry']))
                        Scadenza prossima: {{ $walletSummary['next_expiry']->format('d/m/Y') }}
                    @else
                        Nessuna scadenza imminente
                    @endif
                </div>
            </div>
        </section>

        <section class="section-block">
            <div class="section-title">Storico pagamenti</div>
            <a class="action-card" href="{{ route('payments.history') }}" wire:navigate>
                <div class="left">
                    <div class="action-icon">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <strong>Visualizza pagamenti</strong>
                        <p>Totali, date e motivazioni</p>
                    </div>
                </div>
                <i class="bi bi-chevron-right"></i>
            </a>
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
