@push('styles')
    <style>
        main.page-membership { padding: 16px 16px 90px; }
        .section-block { margin-bottom: 24px; }
        .section-title {
            font-size: 13px;
            letter-spacing: 0.08em;
            color: var(--muted);
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 14px;
        }
        
        /* Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
            transition: color 0.2s ease;
        }
        .back-button:hover {
            color: var(--text);
        }
        .back-button i {
            font-size: 16px;
        }
        
        /* Membership Status Card */
        .membership-status-card {
            background: linear-gradient(135deg, rgba(126,252,91,0.08) 0%, rgba(126,252,91,0.02) 100%);
            border: 1.5px solid rgba(126,252,91,0.3);
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        }
        .membership-status-card.inactive {
            background: linear-gradient(135deg, rgba(252,91,91,0.08) 0%, rgba(252,91,91,0.02) 100%);
            border-color: rgba(252,91,91,0.3);
        }
        .status-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }
        .status-icon {
            width: 56px;
            height: 56px;
            background: rgba(126,252,91,0.15);
            border: 1px solid rgba(126,252,91,0.3);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: var(--accent);
        }
        .status-icon.inactive {
            background: rgba(252,91,91,0.15);
            border-color: rgba(252,91,91,0.3);
            color: #fc5b5b;
        }
        .status-info h2 {
            font-size: 24px;
            font-weight: 800;
            margin: 0 0 4px 0;
        }
        .status-info p {
            font-size: 13px;
            color: var(--muted);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }
        .status-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--line);
        }
        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .detail-label {
            font-size: 11px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
        }
        .detail-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--text);
        }
        
        /* Membership Items Grid */
        .membership-items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }
        .membership-item-card {
            background: linear-gradient(150deg, #1b1b20, #0f0f12);
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 24px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        .membership-item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
            border-color: rgba(126,252,91,0.3);
        }
        .item-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .item-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 12px;
        }
        .item-price {
            font-size: 42px;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, var(--accent) 0%, #fff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .item-price-currency {
            font-size: 24px;
            margin-left: 4px;
        }
        .item-features {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
            flex: 1;
        }
        .item-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid var(--line);
            font-size: 14px;
            color: var(--muted);
        }
        .item-features li:last-child {
            border-bottom: none;
        }
        .item-features li i {
            color: var(--accent);
            font-size: 16px;
        }
        .item-features li strong {
            color: var(--text);
        }
        .btn-purchase {
            background: linear-gradient(135deg, var(--accent) 0%, #8fff6b 100%);
            color: #000;
            border: none;
            border-radius: 16px;
            padding: 16px 24px;
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(126,252,91,0.2);
            transition: all 0.3s ease;
            cursor: pointer;
            display: block;
        }
        .btn-purchase:hover {
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
            .status-details {
                grid-template-columns: 1fr;
            }
            .membership-items-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

<div>
    <main class="page-membership">
        {{-- Back Button --}}
        <a href="{{ route('pricing') }}" class="back-button" wire:navigate>
            <i class="bi bi-arrow-left"></i>
            Torna agli acquisti
        </a>

        {{-- Current Membership Status --}}
        <section class="section-block">
            <div class="section-title">Il tuo abbonamento</div>
            @if($currentMembership)
                <div class="membership-status-card">
                    <div class="status-header">
                        <div class="status-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="status-info">
                            <h2>Abbonamento Attivo</h2>
                            <p>Stato: {{ ucfirst($currentMembership->status) }}</p>
                        </div>
                    </div>
                    <div class="status-details">
                        <div class="detail-item">
                            <span class="detail-label">Data Inizio</span>
                            <span class="detail-value">{{ $currentMembership->start_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Scadenza</span>
                            <span class="detail-value">{{ $currentMembership->end_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Giorni Rimanenti</span>
                            <span class="detail-value">{{ $currentMembership->end_date->diffInDays(now()) }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="membership-status-card inactive">
                    <div class="status-header">
                        <div class="status-icon inactive">
                            <i class="bi bi-shield-x"></i>
                        </div>
                        <div class="status-info">
                            <h2>Nessun Abbonamento Attivo</h2>
                            <p>Acquista un abbonamento per iniziare</p>
                        </div>
                    </div>
                </div>
            @endif
        </section>

        {{-- Membership Renewal Options --}}
        <section class="section-block">
            <div class="section-title">
                @if($currentMembership)
                    Rinnova il tuo abbonamento
                @else
                    Scegli il tuo abbonamento
                @endif
            </div>
            @if($membershipItems->count() > 0)
                <div class="membership-items-grid">
                    @foreach($membershipItems as $item)
                        <article class="membership-item-card">
                            <div class="item-header">
                                <div class="item-title">{{ $item->descrizione }}</div>
                                <div class="item-price">
                                    {{ number_format($item->costo, 2, ',', '.') }}
                                    <span class="item-price-currency">€</span>
                                </div>
                            </div>
                            <ul class="item-features">
                                <li>
                                    <i class="bi bi-coin"></i>
                                    <strong>{{ $item->token }} Lezioni</strong> incluse
                                </li>
                                <li>
                                    <i class="bi bi-calendar-check"></i>
                                    Validità <strong>{{ $item->validity_months }} {{ $item->validity_months == 1 ? 'mese' : 'mesi' }}</strong>
                                </li>
                                <li>
                                    <i class="bi bi-check2-circle"></i>
                                    Accesso completo ai corsi
                                </li>
                            </ul>
                            <a href="{{ route('payment.checkout', $item) }}" class="btn-purchase">
                                @if($currentMembership)
                                    Rinnova Ora
                                @else
                                    Acquista Ora
                                @endif
                            </a>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h3>Nessun abbonamento disponibile</h3>
                    <p>Al momento non ci sono abbonamenti disponibili</p>
                </div>
            @endif
        </section>
    </main>
</div>
