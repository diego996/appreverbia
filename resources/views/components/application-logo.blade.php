@php
    $logoPath = public_path('images/logo-bianco.png');
@endphp

@if (file_exists($logoPath))
    <img src="{{ asset('images/logo-bianco.png') }}" alt="Reverbia" {{ $attributes->merge(['class' => 'app-logo']) }}>
@else
    <span {{ $attributes->merge(['class' => 'app-logo app-logo-text']) }}>REVERBIA</span>
@endif
