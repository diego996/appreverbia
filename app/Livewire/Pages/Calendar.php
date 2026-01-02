<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.reverbia')]
#[Title('Reverbia - Calendario')]
class Calendar extends Component
{
    public array $trainers = [];
    public array $calendar = [];
    public array $lessonCards = [];
    public array $menuLinks = [];

    public function mount(): void
    {
        $this->trainers = [
            ['id' => 'trainer-1', 'name' => 'Claudia R.', 'specialty' => 'Pilates'],
            ['id' => 'trainer-2', 'name' => 'Luca S.', 'specialty' => 'Strength'],
            ['id' => 'trainer-3', 'name' => 'Marta G.', 'specialty' => 'Yoga'],
            ['id' => 'trainer-4', 'name' => 'Andrea P.', 'specialty' => 'Functional'],
        ];

        $this->calendar = [
            'selectedLabel' => 'Martedi 12 Marzo',
            'monthLabel' => 'Marzo 2026',
            'weekdays' => ['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom'],
            'weeks' => [
                [null, null, 1, 2, 3, 4, 5],
                [6, 7, 8, 9, 10, 11, 12],
                [13, 14, 15, 16, 17, 18, 19],
                [20, 21, 22, 23, 24, 25, 26],
                [27, 28, 29, 30, 31, null, null],
            ],
            'selectedDay' => 12,
            'specialDays' => [
                15 => true,
                18 => true,
                24 => true,
            ],
        ];

        $this->lessonCards = [
            [
                'id' => 'session-1',
                'category' => 'Strength',
                'trainer' => 'Luca S.',
                'title' => 'Reverbia Circuit',
                'tags' => ['45 min', 'Max 8 persone', 'Sala Nord'],
                'cta' => 'Prenota ora',
            ],
            [
                'id' => 'session-2',
                'category' => 'Mind',
                'trainer' => 'Marta G.',
                'title' => 'Morning Flow',
                'tags' => ['50 min', 'Livello base', 'Sala Zen'],
                'cta' => 'Vedi slot',
            ],
            [
                'id' => 'session-3',
                'category' => 'Core',
                'trainer' => 'Claudia R.',
                'title' => 'Pilates Reformer',
                'tags' => ['40 min', 'Attrezzi', 'Sala Studio'],
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
        return view('livewire.pages.calendar');
    }
}
