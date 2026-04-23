<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('layouts.reverbia-guest')] #[Title('Reverbia - Cambia Password')] class extends Component
{
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(): void
    {
        $user = Auth::user();

        if (!$user) {
            $this->redirectRoute('login', navigate: true);
            return;
        }

        if (!(bool) $user->force_password_reset) {
            $this->redirectRoute('home', navigate: true);
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Auth::user();

        if (!$user) {
            $this->redirectRoute('login', navigate: true);
            return;
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'force_password_reset' => false,
            'remember_token' => Str::random(60),
        ])->save();

        $this->reset('password', 'password_confirmation');

        $this->redirectRoute('home', navigate: true);
    }
}; ?>

<div>
    <div class="panel" aria-label="Cambio password obbligatorio">
        <div class="logo" aria-hidden="true">
            <x-application-logo class="app-logo" />
        </div>

        <h1>Cambia la password</h1>
        <div class="status-text">Primo accesso: devi impostare una nuova password per continuare.</div>

        <form wire:submit="save">
            <div class="field">
                <label for="password">Nuova password</label>
                <input wire:model="password" id="password" type="password" name="password" placeholder="********" required autocomplete="new-password">
                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Conferma password</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" placeholder="********" required autocomplete="new-password">
                @error('password_confirmation')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn" type="submit">Salva e continua</button>
        </form>
    </div>
</div>
