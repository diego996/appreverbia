<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}" wire:navigate>
            <x-application-logo class="text-secondary" style="width: 2rem; height: 2rem;" />
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate>
                        {{ __('Home') }}
                    </x-nav-link>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <x-dropdown align="right">
                    <x-slot name="trigger">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if (Route::has('profile'))
                            <x-dropdown-link :href="route('profile')" wire:navigate>
                                {{ __('Profilo') }}
                            </x-dropdown-link>

                            <div class="dropdown-divider"></div>
                        @endif

                        <button wire:click="logout" class="dropdown-item">
                            {{ __('Esci') }}
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
