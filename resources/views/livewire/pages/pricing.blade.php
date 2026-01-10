@push('styles')
    <style>
        main.page-pricing { padding: 16px 16px 90px; }
        .section-block { margin-bottom: 24px; }
        .section-title {
            font-size: 13px;
            letter-spacing: 0.08em;
            color: var(--muted);
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 14px;
        }
        
        /* Membership Button */
        .membership-button {
            display: block;
            background: linear-gradient(135deg, rgba(91,150,252,0.15) 0%, rgba(91,150,252,0.05) 100%);
            border: 1.5px solid rgba(91,150,252,0.4);
            border-radius: 20px;
            padding: 20px 24px;
            text-decoration: none;
            color: var(--text);
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .membership-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            border-color: rgba(91,150,252,0.6);
            color: var(--text);
        }
        .membership-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .membership-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .membership-icon {
            width: 48px;
            height: 48px;
            background: rgba(91,150,252,0.2);
            border: 1px solid rgba(91,150,252,0.4);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #5b96fc;
        }
        .membership-info h3 {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 4px 0;
        }
        .membership-info p {
            font-size: 13px;
            color: var(--muted);
            margin: 0;
        }
        .membership-arrow {
            color: #5b96fc;
            font-size: 20px;
        }
        
        /* Token Groups Grid */
        .token-groups-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .token-group-card {
            background: linear-gradient(150deg, #1b1b20, #0f0f12);
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 24px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }
        .token-group-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
            border-color: rgba(126,252,91,0.3);
        }
        .token-group-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--line);
        }
        .token-icon {
            font-size: 40px;
            margin-bottom: 12px;
        }
        .token-amount {
            font-size: 36px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 4px;
            background: linear-gradient(135deg, var(--accent) 0%, #fff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .token-label {
            font-size: 12px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
        }
        .token-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .token-option {
            background: rgba(126,252,91,0.05);
            border: 1.5px solid var(--line);
            border-radius: 16px;
            padding: 16px;
            transition: all 0.3s ease;
        }
        .token-option:hover {
            border-color: rgba(126,252,91,0.3);
            background: rgba(126,252,91,0.08);
        }
        .option-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .option-type {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
        }
        .option-type i {
            font-size: 18px;
            color: var(--accent);
        }
        .option-price {
            font-size: 24px;
            font-weight: 800;
            color: var(--text);
        }
        .option-price-currency {
            font-size: 16px;
            margin-left: 2px;
        }
        .option-description {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 12px;
        }
        .btn-buy-option {
            background: linear-gradient(135deg, var(--accent) 0%, #8fff6b 100%);
            color: #000;
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(126,252,91,0.2);
            transition: all 0.3s ease;
            cursor: pointer;
            display: block;
            width: 100%;
        }
        .btn-buy-option:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(126,252,91,0.3);
            background: linear-gradient(135deg, #8fff6b 0%, var(--accent) 100%);
            color: #000;
        }
        
        /* Empty State */
        .empty-state {
            background: rgba(126,252,91,0.05);
            border: 1.5px dashed rgba(126,252,91,0.3);
            border-radius: 20px;
            text-align: center;
            padding: 40px 20px;
        }
        .empty-state i {
            font-size: 48px;
            color: var(--accent);
            margin-bottom: 16px;
        }
        .empty-state h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .empty-state p {
            color: var(--muted);
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .token-groups-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .membership-content {
                flex-direction: column;
                align-items: flex-start;
            }
            .membership-arrow {
                align-self: flex-end;
            }
        }
    </style>
@endpush

<div>
    <main class="page-pricing">
        {{-- Membership Section --}}
        <section class="section-block">
            <div class="section-title">Abbonamento</div>
            <a href="{{ route('membership') }}" class="membership-button" wire:navigate>
                <div class="membership-content">
                    <div class="membership-left">
                        <div class="membership-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="membership-info">
                            <h3>Gestisci Abbonamento</h3>
                            <p>Visualizza stato e rinnova la tua membership</p>
                        </div>
                    </div>
                    <i class="bi bi-arrow-right membership-arrow"></i>
                </div>
            </a>
        </section>

        {{-- Token Items Section --}}
        <section class="section-block">
            <div class="section-title">Acquista Token</div>
            @if($groupedItems->count() > 0)
                <div class="token-groups-grid">
                    @foreach($groupedItems as $tokenAmount => $items)
                        <article class="token-group-card">
                            <div class="token-group-header">
                                <div class="token-amount">{{ $tokenAmount }}</div>
                                <div class="token-label">Lezioni</div>
                            </div>
                            <div class="token-options">
                                @foreach($items as $item)
                                    <div class="token-option">
                                        <div class="option-header">
                                            <div class="option-type">
                                                @if(str_contains(strtolower($item->descrizione), 'duetto'))
                                                    <i class="bi bi-people-fill"></i>
                                                    <span>Duetto</span>
                                                @else
                                                    <i class="bi bi-person-fill"></i>
                                                    <span>Singolo</span>
                                                @endif
                                            </div>
                                            <div class="option-price">
                                                {{ number_format($item->costo, 2, ',', '.') }}
                                                <span class="option-price-currency">€</span>
                                            </div>
                                        </div>
                                        <div class="option-description">
                                            {{ $item->descrizione }}
                                        </div>
                                        <a href="{{ route('payment.checkout', $item) }}" class="btn-buy-option">
                                            Acquista
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h3>Nessun pacchetto disponibile</h3>
                    <p>Al momento non ci sono pacchetti token disponibili</p>
                </div>
            @endif
        </section>
    </main>
</div>
