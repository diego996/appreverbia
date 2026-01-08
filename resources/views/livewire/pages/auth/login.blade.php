<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('layouts.reverbia-guest')] #[Title('Reverbia - Login')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('home', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="panel" aria-label="Accesso clienti Reverbia">
        <div class="logo" aria-hidden="true">
            <x-application-logo class="app-logo" />
        </div>
        <h1>I tuoi Personal Trainer a Milano</h1>

        @if (session('status'))
            <div class="status-text">{{ session('status') }}</div>
        @endif

        <form wire:submit="login">
            <div class="field">
                <label for="email">Inserisci la tua email</label>
                <input wire:model="form.email" type="email" id="email" name="email" placeholder="nome@domain.com" required autofocus autocomplete="username">
                @error('form.email')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input wire:model="form.password" type="password" id="password" name="password" placeholder="********" required autocomplete="current-password">
                @error('form.password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="checkbox">
                <input wire:model="form.remember" type="checkbox" id="remember" name="remember">
                <label for="remember">Ricordami</label>
            </div>

            <button class="btn" type="submit">Entra ora</button>
        </form>

        <div class="links">
            @if (Route::has('password.request'))
                Non ricordi le tue credenziali?<br>
                Puoi fare un nuovo <a href="{{ route('password.request') }}" wire:navigate>reset</a> o contattare il <a href="#">supporto</a>.
            @else
                Hai bisogno di aiuto? Contatta il <a href="#">supporto</a>.
            @endif
        </div>
    </div>
</div>
