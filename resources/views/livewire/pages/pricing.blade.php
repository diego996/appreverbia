<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold display-5 mb-3">Scegli il tuo Piano</h1>
        <p class="text-light fs-5">Acquista token per accedere ai corsi e servizi esclusivi.</p>
    </div>

    <div class="row g-4 justify-content-center">
        @foreach($items as $item)
        <div class="col-md-4 col-sm-6 col-12">
            <div class="card h-100" style="background: var(--panel); border: 1px solid var(--line); border-radius: 16px;">
                <div class="card-body p-4 d-flex flex-column text-center">
                    <h5 class="card-title fw-bold text-uppercase mb-3" style="color: var(--accent); letter-spacing: 1px;">
                        {{ $item->descrizione }}
                    </h5>
                    
                    <div class="mb-4">
                        <span class="display-4 fw-bold text-white">{{ number_format($item->costo, 2, ',', '.') }}</span>
                        <span class="fs-5 text-white">€</span>
                    </div>

                    <ul class="list-unstyled mb-4 text-start mx-auto" style="color: var(--muted);">
                        <li class="mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-coin text-warning"></i>
                            <span class="text-white fw-semibold">{{ $item->token }} Token</span>
                        </li>
                        @if($item->validity_months)
                        <li class="mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-calendar-check"></i>
                            Validità {{ $item->validity_months }} mesi
                        </li>
                        @endif
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check2-circle"></i>
                            Accesso completo ai corsi
                        </li>
                    </ul>

                    <div class="mt-auto">
                        <a href="{{ route('payment.checkout', $item) }}" 
                           class="btn w-100 py-3 fw-bold" 
                           style="background: var(--text); color: var(--bg); border-radius: 30px; transition: transform 0.2s;">
                           Acquista Ora
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <style>
        .card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            border-color: var(--accent);
        }
        .card { transition: all 0.3s ease; }
    </style>
</div>
