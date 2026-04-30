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

        if ((bool) auth()->user()?->force_password_reset) {
            $this->redirectRoute('password.first-change', navigate: true);
            return;
        }

        $this->redirectIntended(default: route('home', absolute: false));
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
                <div class="password-wrap">
                    <input wire:model="form.password" type="password" id="password" name="password" placeholder="********" required autocomplete="current-password">
                    <button type="button" class="password-toggle" data-toggle-password aria-label="Mostra password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('form.password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="checkbox">
                <input wire:model="form.remember" type="checkbox" id="remember" name="remember">
                <label for="remember">Ricordami</label>
            </div>

            <button class="btn" type="submit" wire:loading.attr="disabled" wire:target="login">
                <span wire:loading.remove wire:target="login">Entra ora</span>
                <span wire:loading wire:target="login">Caricamento...</span>
            </button>
        </form>

        <div class="links">
            @if (Route::has('password.request'))
                Non ricordi le tue credenziali?<br>
                Puoi fare un nuovo <a href="{{ route('password.request') }}" wire:navigate>reset</a>.
            @else
                Hai bisogno di aiuto? Effettua un nuovo reset.
            @endif
        </div>
    </div>
</div>

@push('styles')
    <style>
        .password-wrap {
            position: relative;
        }
        .password-wrap input {
            padding-right: 44px;
        }
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            border: 0;
            border-radius: 50%;
            background: transparent;
            color: #cfd2d5;
            display: grid;
            place-items: center;
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            const bindPasswordToggle = () => {
                const input = document.getElementById('password');
                const toggle = document.querySelector('[data-toggle-password]');
                if (!input || !toggle || toggle.dataset.bound === '1') return;

                toggle.dataset.bound = '1';
                toggle.addEventListener('click', () => {
                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';
                    toggle.innerHTML = isHidden ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
                    toggle.setAttribute('aria-label', isHidden ? 'Nascondi password' : 'Mostra password');
                });
            };

            document.addEventListener('DOMContentLoaded', bindPasswordToggle);
            document.addEventListener('livewire:navigated', bindPasswordToggle);
        }());
    </script>
@endpush
