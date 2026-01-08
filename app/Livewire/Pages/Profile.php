<?php

namespace App\Livewire\Pages;

use App\Models\CourseBooking;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.reverbia-shell')]
#[Title('Reverbia - Profilo')]
class Profile extends Component
{
    public array $userInfo = [];
    public array $upcomingLessons = [];
    public array $historyLessons = [];
    public array $duetto = [];
    public array $usefulLinks = [];

    public function mount(): void
    {
        $user = auth()->user();

        if (!$user) {
            return;
        }

        $this->userInfo = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'branch' => $user->branch?->name,
            'status' => $user->status,
        ];

        [$this->upcomingLessons, $this->historyLessons] = $this->buildLessons($user);

        if ($user->duetto_id) {
            $duettoUser = User::query()->find($user->duetto_id);
            if ($duettoUser) {
                $this->duetto = [
                    'name' => $duettoUser->name,
                    'email' => $duettoUser->email,
                    'phone' => $duettoUser->phone,
                ];
            }
        }

        $this->usefulLinks = [
            ['label' => 'Supporto', 'url' => '#'],
            ['label' => 'FAQ', 'url' => '#'],
            ['label' => 'Contatti', 'url' => '#'],
            ['label' => 'Regolamento', 'url' => '#'],
        ];
    }

    protected function buildLessons(User $user): array
    {
        $bookings = CourseBooking::query()
            ->where('user_id', $user->id)
            ->with(['occurrence.course.trainer', 'occurrence.course.branch'])
            ->orderByDesc('booked_at')
            ->get();

        $today = now()->startOfDay();

        $upcoming = [];
        $history = [];

        foreach ($bookings as $booking) {
            $occurrence = $booking->occurrence;
            if (!$occurrence || !$occurrence->date) {
                continue;
            }

            $course = $occurrence->course;
            $trainer = $course?->trainer;
            $branch = $course?->branch;

            $item = [
                'date' => $occurrence->date->format('d M'),
                'time' => $occurrence->start_time ? substr($occurrence->start_time, 0, 5) : '--:--',
                'title' => $course?->title ?? 'Lezione',
                'trainer' => $trainer?->name ?? 'Trainer',
                'location' => $branch?->name ?? 'Sede',
                'status' => $booking->status,
            ];

            if ($occurrence->date->greaterThanOrEqualTo($today)) {
                $upcoming[] = $item;
            } else {
                $history[] = $item;
            }
        }

        return [
            array_slice($upcoming, 0, 4),
            array_slice($history, 0, 6),
        ];
    }

    public function render()
    {
        return view('livewire.pages.profile');
    }
}
