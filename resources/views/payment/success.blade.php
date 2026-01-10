@extends('layouts.reverbia-shell')

@section('title', 'Pagamento Completato')

@section('slot')
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 80vh; padding: 20px; text-align: center;">
        <div style="font-size: 64px; color: var(--accent); margin-bottom: 20px;">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <h1 class="h2 fw-bold mb-3 text-white">Pagamento Riuscito!</h1>
        <p class="text-light mb-4" style="max-width: 400px;">
            Grazie per il tuo acquisto. Il tuo pagamento è stato elaborato con successo e i crediti sono stati aggiunti al tuo wallet.
        </p>
        <a href="{{ route('home') }}" class="btn btn-primary" style="background: var(--accent); border: none; color: #000; font-weight: 600; padding: 12px 30px; border-radius: 30px;">
            Torna alla Home
        </a>
    </div>
@endsection
