<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.reverbia')]
#[Title('Reverbia - Home')]
class Dashboard extends Component
{
    public array $tokens = [];
    public array $bookedLessons = [];
    public array $availableCourses = [];
    public array $menuLinks = [];

    public function mount(): void
    {
        $this->tokens = [
            'percentage' => 62,
            'total' => 20,
            'booked' => 8,
            'available' => 12,
        ];

        $this->bookedLessons = [
            [
                'date' => 'Mar 12',
                'time' => '18:30',
                'title' => 'Reverbia Strength',
                'coach' => 'Sofia L.',
                'status' => 'Confermato',
            ],
            [
                'date' => 'Gio 14',
                'time' => '07:45',
                'title' => 'Power Pilates',
                'coach' => 'Marco V.',
                'status' => 'In attesa',
            ],
            [
                'empty' => true,
                'message' => 'Nessuna lezione prenotata',
                'hint' => 'Scegli un corso dal calendario',
            ],
        ];

        $this->availableCourses = [
            [
                'tag' => 'Posturale',
                'time' => '19:00',
                'title' => 'Functional Core',
                'coach' => 'Giulia P.',
                'cta' => 'Prenota ora',
            ],
            [
                'tag' => 'Forza',
                'time' => '20:30',
                'title' => 'Reverbia HIIT',
                'coach' => 'Luca R.',
                'cta' => 'Vedi dettagli',
            ],
            [
                'tag' => 'Mind',
                'time' => '08:15',
                'title' => 'Yoga Flow',
                'coach' => 'Serena M.',
                'cta' => 'Prenota ora',
            ],
        ];

        $this->menuLinks = [
            ['icon' => 'bi-house-door', 'label' => 'Home', 'url' => route('dashboard')],
            ['icon' => 'bi-calendar4-week', 'label' => 'Calendario', 'url' => route('calendar')],
            ['icon' => 'bi-calendar-check', 'label' => 'Prenota', 'url' => '#'],
            ['icon' => 'bi-heart', 'label' => 'Allenamenti', 'url' => '#'],
            ['icon' => 'bi-chat-dots', 'label' => 'Supporto', 'url' => '#'],
            ['icon' => 'bi-person', 'label' => 'Profilo', 'url' => '#'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.dashboard');
    }
}
