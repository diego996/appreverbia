<?php

namespace App\Livewire\Pages;

use App\Models\CourseBooking;
use App\Models\CourseOccurrence;
use App\Models\CourseWaitlist;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.reverbia-shell')]
#[Title('Reverbia - Profilo')]
class Profile extends Component
{
    use WithPagination;

    public array $userInfo = [];
    public array $historyLessons = [];
    public array $duetto = [];
    public array $usefulLinks = [];
    public array $walletSummary = [];
    public ?int $confirmingCancelId = null;
    public array $confirmingLesson = [];
    public string $cancelError = '';
    public ?int $confirmingDuettoId = null;
    public array $confirmingDuettoLesson = [];
    public string $duettoError = '';
    public int $upcomingPerPage = 3;

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
            'branch' => $user->branch?->nome,
            'status' => $user->status,
        ];

        $this->historyLessons = $this->buildHistoryLessons($user);

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

        $this->walletSummary = [
            'balance' => $this->calculateWalletBalance($user->id),
            'next_expiry' => Wallet::query()
                ->where('user_id', $user->id)
                ->whereNotNull('expires_at')
                ->where('expires_at', '>=', now()->toDateString())
                ->orderBy('expires_at')
                ->first()?->expires_at,
        ];
    }

    protected function buildHistoryLessons(User $user): array
    {
        $bookings = CourseBooking::query()
            ->where('user_id', $user->id)
            ->with(['occurrence.course.trainer', 'occurrence.course.branch'])
            ->orderByDesc('booked_at')
            ->get();

        $today = now()->startOfDay();

        $history = [];

        $statusLabels = [
            'booked' => 'Confermato',
            'confirmed_duetto' => 'In attesa duetto',
            'pending_duetto' => 'Richiesta duetto',
            'waiting' => 'In attesa',
            'cancelled' => 'Annullato',
        ];

        foreach ($bookings as $booking) {
            $occurrence = $booking->occurrence;
            if (!$occurrence || !$occurrence->date) {
                continue;
            }

            $course = $occurrence->course;
            $trainer = $course?->trainer;
            $branch = $course?->branch;

            $status = $booking->status ?? 'booked';
            $item = [
                'booking_id' => $booking->id,
                'occurrence_id' => $occurrence->id,
                'date' => $occurrence->date->format('d M'),
                'time' => $occurrence->start_time ? substr($occurrence->start_time, 0, 5) : '--:--',
                'title' => $course?->title ?? 'Lezione',
                'trainer' => $trainer?->name ?? 'Trainer',
                'location' => $branch?->name ?? 'Sede',
                'status' => $statusLabels[$status] ?? ucfirst($status),
                'can_confirm_duetto' => $status === 'pending_duetto',
            ];

            if ($occurrence->date->greaterThanOrEqualTo($today)) {
            } else {
                $history[] = $item;
            }
        }

        return array_slice($history, 0, 6);
    }

    protected function resolveOccurrenceStartAt(CourseOccurrence $occurrence): Carbon
    {
        $date = $occurrence->date ? $occurrence->date->format('Y-m-d') : now()->toDateString();
        $time = $occurrence->start_time ?? '00:00';

        return Carbon::parse("{$date} {$time}");
    }

    protected function canCancelBooking(CourseBooking $booking): bool
    {
        if (!$booking->occurrence || !$booking->occurrence->date) {
            return false;
        }

        $startAt = $this->resolveOccurrenceStartAt($booking->occurrence);

        return $startAt->greaterThanOrEqualTo(now()->addHours(12));
    }

    public function getUpcomingBookingsProperty()
    {
        $user = auth()->user();
        if (!$user) {
            return collect();
        }

        $paginator = CourseBooking::query()
            ->where('user_id', $user->id)
            ->whereHas('occurrence', function ($query) {
                $query->where('date', '>=', now()->toDateString());
            })
            ->with(['occurrence.course.trainer', 'occurrence.course.branch'])
            ->orderByDesc('booked_at')
            ->paginate($this->upcomingPerPage, ['*'], 'bookingsPage');

        $paginator->setCollection($paginator->getCollection()->map(function ($booking) {
            $occurrence = $booking->occurrence;
            if (!$occurrence || !$occurrence->date) {
                return null;
            }

            $course = $occurrence->course;
            $trainer = $course?->trainer;
            $branch = $course?->branch;

            $statusLabels = [
                'booked' => 'Confermato',
                'confirmed_duetto' => 'In attesa duetto',
                'pending_duetto' => 'Richiesta duetto',
                'waiting' => 'In attesa',
                'cancelled' => 'Annullato',
            ];

            $status = $booking->status ?? 'booked';
            $canCancel = $this->canCancelBooking($booking);
            $startAt = $this->resolveOccurrenceStartAt($occurrence);

            return [
                'booking_id' => $booking->id,
                'occurrence_id' => $occurrence->id,
                'date' => $occurrence->date->format('d M'),
                'time' => $occurrence->start_time ? substr($occurrence->start_time, 0, 5) : '--:--',
                'title' => $course?->title ?? 'Lezione',
                'trainer' => $trainer?->name ?? 'Trainer',
                'location' => $branch?->name ?? 'Sede',
                'status' => $statusLabels[$status] ?? ucfirst($status),
                'can_confirm_duetto' => $status === 'pending_duetto',
                'can_cancel' => $canCancel,
                'cancel_hint' => $canCancel ? null : 'Disdetta possibile fino a 12 ore prima.',
                'start_at' => $startAt,
            ];
        })->filter());

        return $paginator;
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
            $this->dispatch('open-modal', name: 'cancel-booking');
            return;
        }

        if (!$this->canCancelBooking($booking)) {
            $this->cancelError = 'Puoi disdire solo fino a 12 ore prima della lezione.';
            $this->dispatch('open-modal', name: 'cancel-booking');
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

        $this->dispatch('open-modal', name: 'cancel-booking');
    }

    public function openDuettoConfirmModal(int $bookingId): void
    {
        $user = auth()->user();
        if (!$user) {
            return;
        }

        $this->duettoError = '';
        $this->confirmingDuettoLesson = [];
        $this->confirmingDuettoId = $bookingId;

        $booking = CourseBooking::query()
            ->where('id', $bookingId)
            ->where('user_id', $user->id)
            ->where('status', 'pending_duetto')
            ->with(['occurrence.course.trainer', 'occurrence.course.branch'])
            ->first();

        if (!$booking || !$booking->occurrence) {
            $this->duettoError = 'Richiesta duetto non disponibile.';
            $this->dispatch('open-modal', name: 'confirm-duetto');
            return;
        }

        $occurrence = $booking->occurrence;
        $course = $occurrence->course;

        $this->confirmingDuettoLesson = [
            'title' => $course?->title ?? 'Lezione',
            'date' => $occurrence->date->translatedFormat('D d M'),
            'time' => substr($occurrence->start_time ?? '--:--', 0, 5),
            'trainer' => $course?->trainer?->name ?? 'Trainer',
            'branch' => $course?->branch?->name ?? 'Sede',
        ];

        $this->dispatch('open-modal', name: 'confirm-duetto');
    }

    public function confirmDuettoBooking(): void
    {
        $user = auth()->user();
        if (!$user || !$this->confirmingDuettoId) {
            return;
        }

        $this->duettoError = '';

        try {
            DB::transaction(function () use ($user) {
                $booking = CourseBooking::query()
                    ->where('id', $this->confirmingDuettoId)
                    ->where('user_id', $user->id)
                    ->where('status', 'pending_duetto')
                    ->lockForUpdate()
                    ->first();

                if (!$booking) {
                    throw new \RuntimeException('Richiesta duetto non disponibile.');
                }

                $occurrenceId = $booking->occurrence_id;
                $duettoId = $user->duetto_id;

                $partnerBooking = null;
                if ($duettoId) {
                    $partnerBooking = CourseBooking::query()
                        ->where('occurrence_id', $occurrenceId)
                        ->where('user_id', $duettoId)
                        ->whereIn('status', ['pending_duetto', 'confirmed_duetto'])
                        ->lockForUpdate()
                        ->first();
                }

                if (!$partnerBooking) {
                    throw new \RuntimeException('Duetto non disponibile.');
                }

                $booking->update([
                    'status' => 'confirmed_duetto',
                ]);

                if ($partnerBooking && $partnerBooking->status === 'confirmed_duetto') {
                    CourseBooking::query()
                        ->whereIn('id', [$booking->id, $partnerBooking->id])
                        ->update([
                            'status' => 'booked',
                        ]);
                }
            });
        } catch (\Throwable $exception) {
            $this->duettoError = $exception->getMessage();
            return;
        }

        $this->dispatch('close-modal', name: 'confirm-duetto');
        $this->mount();
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

                if (!$this->canCancelBooking($booking)) {
                    throw new \RuntimeException('Puoi disdire solo fino a 12 ore prima della lezione.');
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

                if ($duettoId) {
                    $duettoWallet = DB::table('wallets')
                        ->where('user_id', $duettoId)
                        ->where('model_type', CourseOccurrence::class)
                        ->where('model_id', $occurrenceId)
                        ->where('reason', 'booking')
                        ->first();

                    if ($duettoWallet) {
                        $duettoRefundExists = DB::table('wallets')
                            ->where('user_id', $duettoId)
                            ->where('model_type', CourseOccurrence::class)
                            ->where('model_id', $occurrenceId)
                            ->where('reason', 'refund')
                            ->exists();

                        if (!$duettoRefundExists) {
                            $tokenDelta = (int) ($duettoWallet->token_delta ?? -1);
                            DB::table('wallets')->insert([
                                'user_id' => $duettoId,
                                'model_type' => CourseOccurrence::class,
                                'model_id' => $occurrenceId,
                                'token_delta' => abs($tokenDelta),
                                'reason' => 'refund',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
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

        $this->dispatch('close-modal', name: 'cancel-booking');
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
        return view('livewire.pages.profile', [
            'upcomingBookings' => $this->upcomingBookings,
        ]);
    }
}
