<?php

namespace App\Livewire\Pages;

use App\Models\CourseBooking;
use App\Models\CourseOccurrence;
use App\Models\CourseWaitlist;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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
    public ?int $confirmingCancelId = null;
    public array $confirmingLesson = [];
    public string $cancelError = '';

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
                'booking_id' => $booking->id,
                'occurrence_id' => $occurrence->id,
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

    public function openCancelModal(int $bookingId): void
    {
        $user = auth()->user();
        if (!$user) {
            return;
        }

        $this->cancelError = '';
        $this->confirmingLesson = [];
        $this->confirmingCancelId = $bookingId;

        $booking = CourseBooking::query()
            ->where('id', $bookingId)
            ->where('user_id', $user->id)
            ->with(['occurrence.course.trainer', 'occurrence.course.branch'])
            ->first();

        if (!$booking || !$booking->occurrence) {
            $this->cancelError = 'Prenotazione non disponibile.';
            $this->dispatch('open-modal', 'cancel-booking');
            return;
        }

        $occurrence = $booking->occurrence;
        $course = $occurrence->course;

        $this->confirmingLesson = [
            'title' => $course?->title ?? 'Lezione',
            'date' => $occurrence->date->translatedFormat('D d M'),
            'time' => substr($occurrence->start_time ?? '--:--', 0, 5),
            'trainer' => $course?->trainer?->name ?? 'Trainer',
            'branch' => $course?->branch?->name ?? 'Sede',
        ];

        $this->dispatch('open-modal', 'cancel-booking');
    }

    public function confirmCancelBooking(): void
    {
        $user = auth()->user();
        if (!$user || !$this->confirmingCancelId) {
            return;
        }

        $this->cancelError = '';

        try {
            DB::transaction(function () use ($user) {
                $booking = CourseBooking::query()
                    ->where('id', $this->confirmingCancelId)
                    ->where('user_id', $user->id)
                    ->lockForUpdate()
                    ->first();

                if (!$booking) {
                    throw new \RuntimeException('Prenotazione non disponibile.');
                }

                $occurrenceId = $booking->occurrence_id;
                $duettoId = $user->duetto_id;

                $booking->delete();

                if ($duettoId) {
                    CourseBooking::query()
                        ->where('occurrence_id', $occurrenceId)
                        ->where('user_id', $duettoId)
                        ->delete();
                }

                $walletBooking = DB::table('wallets')
                    ->where('user_id', $user->id)
                    ->where('model_type', CourseOccurrence::class)
                    ->where('model_id', $occurrenceId)
                    ->where('reason', 'booking')
                    ->first();

                if ($walletBooking) {
                    $refundExists = DB::table('wallets')
                        ->where('user_id', $user->id)
                        ->where('model_type', CourseOccurrence::class)
                        ->where('model_id', $occurrenceId)
                        ->where('reason', 'refund')
                        ->exists();

                    if (!$refundExists) {
                        $tokenDelta = (int) ($walletBooking->token_delta ?? -1);
                        DB::table('wallets')->insert([
                            'user_id' => $user->id,
                            'model_type' => CourseOccurrence::class,
                            'model_id' => $occurrenceId,
                            'token_delta' => abs($tokenDelta),
                            'reason' => 'refund',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                $occurrence = CourseOccurrence::query()
                    ->lockForUpdate()
                    ->find($occurrenceId);

                if (!$occurrence || !$occurrence->max_participants) {
                    return;
                }

                $bookingsCount = CourseBooking::query()
                    ->where('occurrence_id', $occurrenceId)
                    ->lockForUpdate()
                    ->count();

                $availableSeats = max(0, $occurrence->max_participants - $bookingsCount);
                if ($availableSeats <= 0) {
                    return;
                }

                $waitlist = CourseWaitlist::query()
                    ->where('occurrence_id', $occurrenceId)
                    ->orderBy('added_at')
                    ->lockForUpdate()
                    ->get();

                foreach ($waitlist as $entry) {
                    if ($availableSeats <= 0) {
                        break;
                    }

                    $walletBalance = $this->calculateWalletBalance($entry->user_id);
                    if ($walletBalance < 1) {
                        continue;
                    }

                    $alreadyBooked = CourseBooking::query()
                        ->where('occurrence_id', $occurrenceId)
                        ->where('user_id', $entry->user_id)
                        ->exists();

                    if ($alreadyBooked) {
                        $entry->delete();
                        continue;
                    }

                    CourseBooking::query()->create([
                        'occurrence_id' => $occurrenceId,
                        'user_id' => $entry->user_id,
                        'booked_at' => now(),
                        'status' => 'booked',
                    ]);

                    $existingWallet = DB::table('wallets')
                        ->where('user_id', $entry->user_id)
                        ->where('model_type', CourseOccurrence::class)
                        ->where('model_id', $occurrenceId)
                        ->where('reason', 'booking')
                        ->first();

                    if (!$existingWallet) {
                        DB::table('wallets')->insert([
                            'user_id' => $entry->user_id,
                            'model_type' => CourseOccurrence::class,
                            'model_id' => $occurrenceId,
                            'token_delta' => -1,
                            'reason' => 'booking',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    $entry->delete();
                    $availableSeats--;
                }
            });
        } catch (\Throwable $exception) {
            $this->cancelError = $exception->getMessage();
            return;
        }

        $this->dispatch('close-modal', 'cancel-booking');
        $this->mount();
    }

    protected function calculateWalletBalance(int $userId): int
    {
        return (int) DB::table('wallets')
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now()->toDateString());
            })
            ->sum('token_delta');
    }

    public function render()
    {
        return view('livewire.pages.profile');
    }
}
