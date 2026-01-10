    @extends('layouts.reverbia')

    @section('title', 'Pagamento Annullato')

@section('slot')
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 80vh; padding: 20px; text-align: center;">
            <div style="font-size: 64px; color: var(--accent-2); margin-bottom: 20px;">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <h1 class="h2 fw-bold mb-3 text-white">Pagamento Annullato</h1>
            <p class="text-light mb-4" style="max-width: 400px;">
                Hai annullato il processo di pagamento. Nessun addebito è stato effettuato.
            </p>
            <div class="d-flex gap-3">
                <a href="{{ route('pricing') }}" class="btn btn-outline-light" style="border-radius: 30px;">
                    Riprova
                </a>
                <a href="{{ route('home') }}" class="btn btn-link text-decoration-none text-light">
                    Torna alla Home
                </a>
            </div>
        </div>
    @endsection
