@push('styles')
    <style>
        main.page-dashboard { padding: 12px 14px 90px; }
        .section-block { margin-bottom: 22px; }
        .section-title {
            font-size: 12px;
            letter-spacing: 0.08em;
            color: var(--muted);
            text-transform: uppercase;
            font-weight: 700;
        }
        .lesson-rail {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(240px, 1fr);
            gap: 14px;
            overflow-x: auto;
            padding: 14px 2px 6px;
            scroll-snap-type: x mandatory;
        }
        .lesson-card {
            background: linear-gradient(150deg, #1b1b20, #0f0f12);
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 16px;
            min-height: 168px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 8px;
            scroll-snap-align: start;
        }
        .lesson-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: var(--muted);
        }
        .lesson-title {
            font-size: 18px;
            font-weight: 700;
        }
        .lesson-meta { font-size: 13px; color: var(--muted); }
        .badge-status {
            background: rgba(126,252,91,0.12);
            border: 1px solid rgba(126,252,91,0.4);
            color: var(--accent);
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }
        .cta-card {
            background: radial-gradient(circle at 20% 20%, rgba(126,252,91,0.15), transparent 55%), #0b0b0e;
            border-style: dashed;
            justify-content: center;
            text-decoration: none;
            color: var(--text);
        }
        .cta-card .cta-title {
            font-size: 17px;
            font-weight: 700;
        }
        .cta-card .cta-sub { color: var(--muted); font-size: 13px; }
        .wallet-card {
            background: #0b0b0e;
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow);
        }
        .wallet-card .amount {
            font-size: 26px;
            font-weight: 700;
        }
        .wallet-card .label {
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
    </style>
@endpush

<div>
    <main class="page-dashboard">
        <section class="section-block">
            <div class="section-title">Prossime lezioni</div>
            <div class="lesson-rail">
                @foreach ($bookedLessons as $lesson)
                    <article class="lesson-card">
                        <div class="lesson-top">
                            <span>{{ strtoupper($lesson['date']) }} - {{ $lesson['time'] }}</span>
                            <span class="badge-status">{{ $lesson['status'] }}</span>
                        </div>
                        <div class="lesson-title">{{ $lesson['title'] }}</div>
                        <div class="lesson-meta">Coach: {{ $lesson['coach'] }}</div>
                        <div class="lesson-meta">Sala: {{ $lesson['room'] }}</div>
                    </article>
                @endforeach

                <a class="lesson-card cta-card" href="#">
                    <div class="cta-title">I tuoi corsi</div>
                    <div class="cta-sub">Rivedi le prenotazioni attive</div>
                    <div class="mt-auto text-success fw-semibold">Apri elenco</div>
                </a>
                <a class="lesson-card cta-card" href="{{ route('calendar') }}" wire:navigate>
                    <div class="cta-title">Vai al calendario</div>
                    <div class="cta-sub">Scopri nuovi slot disponibili</div>
                    <div class="mt-auto text-success fw-semibold">Apri calendario</div>
                </a>
            </div>
        </section>

        <section class="section-block">
            <div class="section-title">Wallet lezioni</div>
            <div class="wallet-card">
                <div>
                    <div class="amount">{{ (int) $walletSummary['available'] }}</div>
                    <div class="label">{{ $walletSummary['label'] }}</div>
                </div>
                <div class="text-success fw-semibold">Acquista</div>
            </div>
        </section>
    </main>
</div>
