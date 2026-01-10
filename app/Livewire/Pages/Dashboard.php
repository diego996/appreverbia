<?php

namespace App\Livewire\Pages;

use App\Models\CourseBooking;
use App\Models\Wallet;
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
        $user = auth()->user();

        if (!$user) {
            $this->walletSummary = [
                'available' => 0,
                'label' => 'Lezioni disponibili',
            ];

            return;
        }

        $walletBalance = Wallet::query()
            ->where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now()->toDateString());
            })
            ->sum('token_delta');

        $this->walletSummary = [
            'available' => max(0, (int) $walletBalance),
            'label' => 'Lezioni disponibili',
        ];

        $today = now()->startOfDay();
        $bookings = CourseBooking::query()
            ->where('user_id', $user->id)
            ->with(['occurrence.course.trainer', 'occurrence.course.branch'])
            ->get()
            ->filter(function (CourseBooking $booking) use ($today) {
                return $booking->occurrence?->date && $booking->occurrence->date->greaterThanOrEqualTo($today);
            })
            ->sortBy(function (CourseBooking $booking) {
                $occurrence = $booking->occurrence;
                if (!$occurrence || !$occurrence->date) {
                    return '9999-12-31 23:59:59';
                }
                return $occurrence->date->format('Y-m-d') . ' ' . ($occurrence->start_time ?? '23:59:59');
            });

        $statusLabels = [
            'booked' => 'Confermato',
            'confirmed' => 'Confermato',
            'confirmed_duetto' => 'In attesa duetto',
            'pending_duetto' => 'Richiesta duetto',
            'waiting' => 'In attesa',
            'cancelled' => 'Annullato',
        ];

        $this->bookedLessons = $bookings
            ->map(function (CourseBooking $booking) use ($statusLabels): array {
                $occurrence = $booking->occurrence;
                $course = $occurrence?->course;
                $trainer = $course?->trainer;
                $branch = $course?->branch;

                $dateLabel = $occurrence?->date
                    ? ucfirst($occurrence->date->locale('it')->translatedFormat('D d'))
                    : '-- --';

                return [
                    'date' => $dateLabel,
                    'time' => $occurrence?->start_time ? substr($occurrence->start_time, 0, 5) : '--:--',
                    'title' => $course?->title ?? 'Lezione',
                    'coach' => $trainer?->name ?? 'Trainer',
                    'room' => $branch?->name ?? 'Sede',
                    'status' => $statusLabels[$booking->status] ?? ucfirst($booking->status ?? 'Prenotato'),
                ];
            })
            ->values()
            ->take(4)
            ->all();
    }

    public function render()
    {
        return view('livewire.pages.dashboard');
    }
}
