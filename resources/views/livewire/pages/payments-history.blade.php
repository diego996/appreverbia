@push('styles')
    <style>
        main.page-payments { padding: 14px 16px 90px; }
        .section-block { margin-bottom: 20px; }
        .section-title {
            font-size: 12px;
            letter-spacing: 0.08em;
            color: var(--muted);
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .back-button i {
            font-size: 16px;
        }
        .payment-list {
            display: grid;
            gap: 12px;
        }
        .payment-card {
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 14px 16px;
            background: #0f0f12;
            display: grid;
            gap: 8px;
        }
        .payment-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        .payment-amount {
            font-size: 20px;
            font-weight: 800;
        }
        .payment-meta {
            color: var(--muted);
            font-size: 12px;
        }
        .badge-status {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .badge-status.paid {
            background: rgba(126,252,91,0.15);
            border: 1px solid rgba(126,252,91,0.4);
            color: var(--accent);
        }
        .badge-status.pending {
            background: rgba(255, 196, 87, 0.16);
            border: 1px solid rgba(255, 196, 87, 0.45);
            color: #ffc457;
        }
        .badge-status.failed {
            background: rgba(252, 91, 91, 0.16);
            border: 1px solid rgba(252, 91, 91, 0.45);
            color: #fc5b5b;
        }
        .empty-state {
            color: var(--muted);
            font-size: 13px;
            text-align: center;
            padding: 18px 12px;
            border: 1px dashed var(--line);
            border-radius: 14px;
            background: #0c0c0f;
        }
    </style>
@endpush

<div>
    <main class="page-payments">
        <a href="{{ route('profile') }}" class="back-button" wire:navigate>
            <i class="bi bi-arrow-left"></i>
            Torna al profilo
        </a>

        <section class="section-block">
            <div class="section-title">Storico pagamenti</div>
            @if($payments->isNotEmpty())
                <div class="payment-list">
                    @foreach($payments as $payment)
                        <article class="payment-card">
                            <div class="payment-top">
                                <div class="payment-amount">
                                    {{ $payment['amount'] }} {{ $payment['currency'] }}
                                </div>
                                <span class="badge-status {{ $payment['status'] }}">
                                    {{ $payment['status'] }}
                                </span>
                            </div>
                            <div class="payment-meta">{{ $payment['reason'] }}</div>
                            <div class="payment-meta">
                                {{ $payment['date']?->format('d/m/Y') ?? '-' }}
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-state">Nessun pagamento registrato.</div>
            @endif
        </section>
    </main>
</div>
