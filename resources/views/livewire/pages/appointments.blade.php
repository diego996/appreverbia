@push('styles')
    <style>
        main.page-appointments { padding: 14px 16px 96px; }
        .appointments-shell { display: grid; gap: 14px; }
        .appointments-loading {
            position: fixed;
            inset: 0;
            background: rgba(5, 5, 5, 0.28);
            z-index: 1100;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .appointments-loading-spinner {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 3px solid rgba(126,252,91,0.25);
            border-top-color: var(--accent);
            animation: appointmentsSpin .8s linear infinite;
        }
        @keyframes appointmentsSpin {
            to { transform: rotate(360deg); }
        }
        .toolbar {
            background: #0f0f12;
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 10px;
            display: grid;
            gap: 10px;
        }
        .search-input, .scope-select {
            width: 100%;
            background: #0a0a0c;
            border: 1px solid var(--line);
            border-radius: 10px;
            color: var(--text);
            font-size: 14px;
            padding: 10px 12px;
        }
        .appointment-list { display: grid; gap: 10px; }
        .appointment-item {
            background: #0f0f12;
            border: 1px solid rgba(126,252,91,0.25);
            border-radius: 14px;
            padding: 12px;
            display: grid;
            gap: 6px;
        }
        .row-top {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            font-size: 12px;
            color: var(--muted);
        }
        .title { font-size: 16px; font-weight: 700; }
        .meta { font-size: 13px; color: var(--muted); }
        .badge {
            font-size: 11px;
            border-radius: 999px;
            border: 1px solid rgba(126,252,91,0.45);
            color: var(--accent);
            padding: 3px 8px;
            white-space: nowrap;
        }
        .empty {
            border: 1px dashed rgba(126,252,91,0.4);
            border-radius: 14px;
            padding: 20px 14px;
            text-align: center;
            color: var(--muted);
        }
        .pager {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
        .pager button {
            border: 1px solid var(--line);
            background: #0f0f12;
            color: var(--text);
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 12px;
        }
        .pager button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
@endpush

<div>
    <div class="appointments-loading" wire:loading.flex wire:target="search,scope,previousPage,nextPage">
        <div class="appointments-loading-spinner" aria-label="Caricamento"></div>
    </div>
    <main class="page-appointments">
        <div class="appointments-shell">
            <div class="toolbar">
                <input
                    class="search-input"
                    type="text"
                    placeholder="Cerca per lezione, trainer, sede, data..."
                    wire:model.live.debounce.300ms="search"
                    wire:loading.attr="disabled"
                    wire:target="search">
                <select class="scope-select" wire:model.live="scope" wire:loading.attr="disabled" wire:target="scope">
                    <option value="all">Tutti</option>
                    <option value="future">Futuri</option>
                    <option value="past">Passati</option>
                </select>
            </div>

            <div class="appointment-list">
                @forelse ($appointments as $item)
                    <article class="appointment-item">
                        <div class="row-top">
                            <span>{{ $item['date'] }} · {{ $item['time'] }}</span>
                            <span class="badge">{{ $item['status'] }}</span>
                        </div>
                        <div class="title">{{ $item['title'] }}</div>
                        <div class="meta">Trainer: {{ $item['trainer'] }}</div>
                        <div class="meta">Sede: {{ $item['branch'] }}</div>
                    </article>
                @empty
                    <div class="empty">Nessun appuntamento trovato con i filtri attuali.</div>
                @endforelse
            </div>

            @if ($appointments->hasPages())
                <div class="pager">
                    <button type="button" wire:click="previousPage" @disabled($appointments->onFirstPage())>Indietro</button>
                    <button type="button" wire:click="nextPage" @disabled(!$appointments->hasMorePages())>Avanti</button>
                </div>
            @endif
        </div>
    </main>
</div>
