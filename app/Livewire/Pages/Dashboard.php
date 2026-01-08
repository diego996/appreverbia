<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.reverbia-shell')]
#[Title('Reverbia - Home')]
class Dashboard extends Component
{
    public array $bookedLessons = [];
    public array $walletSummary = [];

    public function mount(): void
    {
        $this->walletSummary = [
            'available' => 12,
            'label' => 'Lezioni disponibili',
        ];

        $this->bookedLessons = [
            [
                'date' => 'Mar 12',
                'time' => '18:30',
                'title' => 'Reverbia Strength',
                'coach' => 'Sofia L.',
                'room' => 'Sala North',
                'status' => 'Confermato',
            ],
            [
                'date' => 'Gio 14',
                'time' => '07:45',
                'title' => 'Power Pilates',
                'coach' => 'Marco V.',
                'room' => 'Studio Flow',
                'status' => 'In attesa',
            ],
            [
                'date' => 'Sab 16',
                'time' => '10:15',
                'title' => 'Functional Core',
                'coach' => 'Giulia P.',
                'room' => 'Sala Studio',
                'status' => 'Confermato',
            ],
        ];

    }

    public function render()
    {
        return view('livewire.pages.dashboard');
    }
}
