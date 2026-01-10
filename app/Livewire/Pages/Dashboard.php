<?php

namespace App\Livewire\Pages;

use App\Models\CourseBooking;
use App\Models\CourseOccurrence;
use App\Models\Wallet;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.reverbia-shell')]
#[Title('Reverbia - Home')]
class Dashboard extends Component
{
    public array $bookedLessons = [];
    public array $availableLessons = [];
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
                    ? ucfirst($occurrence->date->locale('it')->translatedFormat('D d M'))
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
            ->all();

        // Load available lessons
        $this->loadAvailableLessons($user);
    }

    protected function loadAvailableLessons($user): void
    {
        if (!$user) {
            $this->availableLessons = [];
            return;
        }

        $today = now()->startOfDay();
        
        // Get user's booked occurrence IDs
        $bookedOccurrenceIds = CourseBooking::query()
            ->where('user_id', $user->id)
            ->pluck('occurrence_id')
            ->toArray();

        // Query available lessons
        $occurrences = CourseOccurrence::query()
            ->with(['course.trainer', 'course.branch'])
            ->withCount('bookings')
            ->where('date', '>=', $today)
            ->whereNotIn('id', $bookedOccurrenceIds)
            ->orderBy('date')
            ->orderBy('start_time')
            ->limit(20) // Increase limit to account for filtered items
            ->get()
            ->filter(function (CourseOccurrence $occurrence) {
                // Filter out lessons starting in less than 1 hour
                if ($occurrence->start_time && $occurrence->date) {
                    $startDateTime = $occurrence->date->copy()->setTimeFromTimeString($occurrence->start_time);
                    if (now()->addHour()->greaterThan($startDateTime)) {
                        return false;
                    }
                }
                return true;
            })
            ->take(6) // Take 6 after filtering
            ->filter(function (CourseOccurrence $occurrence) {
                // Filter out full lessons
                if ($occurrence->max_participants > 0) {
                    return $occurrence->bookings_count < $occurrence->max_participants;
                }
                return true;
            });

        $this->availableLessons = $occurrences
            ->map(function (CourseOccurrence $occurrence): array {
                $course = $occurrence->course;
                $trainer = $course?->trainer;
                $branch = $course?->branch;

                $dateLabel = $occurrence->date
                    ? ucfirst($occurrence->date->locale('it')->translatedFormat('D d M'))
                    : '-- --';

                $bookingsCount = (int) ($occurrence->bookings_count ?? 0);
                $maxParticipants = (int) ($occurrence->max_participants ?? 0);
                $spotsLeft = $maxParticipants > 0 ? max(0, $maxParticipants - $bookingsCount) : null;

                return [
                    'occurrence_id' => $occurrence->id,
                    'date' => $dateLabel,
                    'time' => $occurrence->start_time ? substr($occurrence->start_time, 0, 5) : '--:--',
                    'title' => $course?->title ?? 'Lezione',
                    'coach' => $trainer?->name ?? 'Trainer',
                    'room' => $branch?->name ?? 'Sede',
                    'spots_left' => $spotsLeft,
                    'full_date' => $occurrence->date?->toDateString(),
                ];
            })
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.pages.dashboard');
    }
}
