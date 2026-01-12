<?php

namespace App\Livewire\Pages;

use App\Models\Branch;
use App\Models\Course;
use App\Models\CourseBooking;
use App\Models\CourseOccurrence;
use App\Models\CourseWaitlist;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Component;

#[Layout('layouts.reverbia-shell')]
#[Title('Reverbia - Calendario')]
class Calendar extends Component
{
    public array $calendar = [];
    public array $lessonCards = [];
    public array $lessonCardsByTrainer = [];
    public array $trainerIndicators = [];
    public array $menuLinks = [];
    public array $branches = [];
    public array $courses = [];
    public array $trainers = [];
    
    public int $currentMonth;
    public int $currentYear;
    public ?string $selectedDate = null;
    public ?int $selectedBranch = null;
    public ?int $selectedTrainer = null;
    public ?string $selectedCourse = null;
    public ?string $selectedWeekday = null;
    
    public ?int $confirmingOccurrenceId = null;
    public ?string $confirmingAction = null;
    public bool $confirmDuetto = false;
    public array $confirmingDetails = [];
    
    public int $availableTokens = 0;
    public ?int $duettoTokens = null;
    public string $bookingError = '';
    public bool $hasDuetto = false;
    public ?string $duettoName = null;

    private ?int $cachedUserId = null;
    private ?int $cachedWalletBalance = null;

    public function mount(): void
    {
        $today = now();
        
        $requestedDate = request()->query('date');
        if ($requestedDate) {
            try {
                $date = Carbon::parse($requestedDate);
                $this->currentMonth = $date->month;
                $this->currentYear = $date->year;
                $this->selectedDate = $date->toDateString();
            } catch (\Exception $e) {
                $this->currentMonth = $today->month;
                $this->currentYear = $today->year;
                $this->selectedDate = $today->toDateString();
            }
        } else {
            $this->currentMonth = $today->month;
            $this->currentYear = $today->year;
            $this->selectedDate = $today->toDateString();
        }
        
        $user = auth()->user();
        $this->selectedBranch = $user?->branch_id;
        $this->hasDuetto = (bool) $user?->duetto_id;
        $this->duettoName = $user && $user->duetto_id 
            ? User::query()->find($user->duetto_id)?->name 
            : null;
        $this->availableTokens = $user ? $this->getWalletBalance($user->id) : 0;

        $this->menuLinks = [
            ['icon' => 'bi-house-door', 'label' => 'Home', 'url' => route('home')],
            ['icon' => 'bi-calendar4-week', 'label' => 'Calendario', 'url' => route('calendar')],
            ['icon' => 'bi-bag-plus', 'label' => 'Acquista', 'url' => '#'],
            ['icon' => 'bi-chat-dots', 'label' => 'Supporto', 'url' => '#'],
        ];

        $this->loadFilters();
        $this->loadCalendar();

        // Check for booking request from other pages
        $bookId = request()->query('book');
        if ($bookId) {
            $this->openBookingModal((int) $bookId, 'book');
        }
    }

    public function updatedSelectedBranch(): void
    {
        $this->loadFilters();
        $this->loadCalendar();
    }

    public function updatedSelectedTrainer(): void
    {
        $this->loadCalendar();
    }

    public function updatedSelectedWeekday(): void
    {
        $this->loadCalendar();
    }

    public function updatedSelectedCourse(): void
    {
        if ($this->selectedCourse) {
            $this->selectedCourse = strtolower($this->selectedCourse);
        }
        $this->loadCalendar();
    }

    public function updatedConfirmDuetto(): void
    {
        if (!$this->hasDuetto || $this->confirmingAction !== 'book') {
            $this->confirmDuetto = false;
        }
    }

    public function previousMonth(): void
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonthNoOverflow();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->selectedDate = $date->toDateString();
        $this->loadCalendar();
    }

    public function nextMonth(): void
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonthNoOverflow();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->selectedDate = $date->toDateString();
        $this->loadCalendar();
    }

    public function selectDay(int $day): void
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->day($day);
        $this->selectedDate = $date->toDateString();
        $this->loadCalendar();
    }

    public function openBookingModal(int $occurrenceId, string $action = 'book'): void
    {
        $user = auth()->user();
        if (!$user) {
            return;
        }

        $this->resetBookingState();
        $this->confirmingOccurrenceId = $occurrenceId;

        $occurrence = CourseOccurrence::query()
            ->with(['course.trainer', 'course.branch'])
            ->withCount('bookings')
            ->find($occurrenceId);

        if (!$occurrence || !$occurrence->date) {
            $this->bookingError = 'Lezione non disponibile.';
            $this->dispatch('open-modal', 'booking-confirm');
            return;
        }
        
        // Ensure calendar is focused on the occurrence date if opened via link
        if ($occurrence->date->month !== $this->currentMonth || $occurrence->date->year !== $this->currentYear) {
            $this->currentMonth = $occurrence->date->month;
            $this->currentYear = $occurrence->date->year;
            $this->loadCalendar();
        }
        $this->selectedDate = $occurrence->date->toDateString();

        // Check existing bookings/waitlist
        $alreadyBooked = CourseBooking::query()
            ->where('user_id', $user->id)
            ->where('occurrence_id', $occurrenceId)
            ->exists();

        $alreadyWaitlisted = CourseWaitlist::query()
            ->where('user_id', $user->id)
            ->where('occurrence_id', $occurrenceId)
            ->exists();

        if ($alreadyBooked) {
            $this->bookingError = 'Risulti gia prenotato per questa lezione.';
        } elseif ($alreadyWaitlisted) {
            $this->bookingError = 'Sei gia in lista d\'attesa per questa lezione.';
        } elseif ($occurrence->start_time) {
            $startDateTime = $occurrence->date->copy()->setTimeFromTimeString($occurrence->start_time);
            $bookingCutoff = $startDateTime->copy()->subHour();
            if (now()->greaterThanOrEqualTo($bookingCutoff)) {
                $this->bookingError = now()->greaterThan($startDateTime) 
                    ? 'Lezione terminata o in corso.' 
                    : 'Le iscrizioni sono chiuse (scadenza 1 ora prima dell\'inizio).';
            }
        }

        // Check if full
        $isFull = $occurrence->max_participants > 0 
            && $occurrence->bookings_count >= $occurrence->max_participants;

        if ($isFull && $action === 'book') {
            $action = 'waitlist';
        }

        $this->confirmingAction = $action;
        $this->confirmDuetto = $this->hasDuetto && $action === 'book' ? $this->confirmDuetto : false;

        // Calculate duration
        $duration = '-- min';
        if ($occurrence->start_time && $occurrence->end_time) {
            $duration = Carbon::parse($occurrence->start_time)
                ->diffInMinutes(Carbon::parse($occurrence->end_time)) . ' min';
        }

        // Build confirmation details
        $this->confirmingDetails = [
            'title' => $occurrence->course?->title ?? 'Lezione',
            'date' => $occurrence->date->locale('it')->translatedFormat('D d M'), // Ensures Italian format like 'Lun 10 Gen'
            'time' => substr($occurrence->start_time ?? '--:--', 0, 5),
            'duration' => $duration,
            'trainer' => $occurrence->course?->trainer?->name ?? 'Trainer',
            'branch' => $occurrence->course?->branch?->name ?? 'Sede',
            'max' => $occurrence->max_participants,
            'booked' => $occurrence->bookings_count,
            'trainer_initials' => $this->getTrainerInitials($occurrence->course?->trainer?->name ?? 'Trainer'),
            'trainer_color' => $this->getTrainerColor($occurrence->course?->trainer?->id),
        ];

        // Update token counts
        $this->availableTokens = $this->getWalletBalance($user->id);
        if ($this->hasDuetto && $user->duetto_id) {
            $this->duettoTokens = $this->getWalletBalance($user->duetto_id);
        }
        
        // Validate tokens before showing modal
        if ($action === 'book' && $this->availableTokens < 1) {
            $this->bookingError = 'Token insufficienti per prenotare.';
        }
        
        // Validate duetto tokens if duetto booking is enabled
        if ($action === 'book' && $this->confirmDuetto && $this->hasDuetto && $user->duetto_id) {
            if ($this->duettoTokens < 1) {
                $this->bookingError = 'Il tuo duetto non ha token sufficienti per questa prenotazione.';
                $this->confirmDuetto = false; // Disable duetto option
            }
            
            // Check if duetto is already booked
            $duettoBooked = CourseBooking::query()
                ->where('user_id', $user->duetto_id)
                ->where('occurrence_id', $occurrenceId)
                ->exists();
                
            $duettoWaitlisted = CourseWaitlist::query()
                ->where('user_id', $user->duetto_id)
                ->where('occurrence_id', $occurrenceId)
                ->exists();
                
            if ($duettoBooked || $duettoWaitlisted) {
                $this->bookingError = 'Il tuo duetto ha già una prenotazione o è in lista d\'attesa per questa lezione.';
                $this->confirmDuetto = false; // Disable duetto option
            }
        }
        
        $this->dispatch('open-modal', 'booking-confirm');
    }

    public function confirmBooking(): void
    {
        $user = auth()->user();
        if (!$user || !$this->confirmingOccurrenceId || !$this->confirmingAction) {
            return;
        }

        $this->bookingError = '';

        $action = $this->confirmingAction;
        $isDuetto = $action === 'book' && $this->confirmDuetto && $user->duetto_id;
        $requiredSeats = $isDuetto ? 2 : 1;

        try {
            DB::transaction(function () use ($user, $action, $isDuetto, $requiredSeats) {
                $occurrence = CourseOccurrence::query()
                    ->lockForUpdate()
                    ->find($this->confirmingOccurrenceId);

                if (!$occurrence) {
                    throw new \RuntimeException('Lezione non disponibile.');
                }

                // Verify not already booked
                $alreadyBooked = CourseBooking::query()
                    ->where('user_id', $user->id)
                    ->where('occurrence_id', $occurrence->id)
                    ->exists();

                if ($alreadyBooked) {
                    throw new \RuntimeException('Risulti gia prenotato per questa lezione.');
                }

                $alreadyWaitlisted = CourseWaitlist::query()
                    ->where('user_id', $user->id)
                    ->where('occurrence_id', $occurrence->id)
                    ->exists();

                // Check Time
                if ($occurrence->start_time) {
                    $startDateTime = $occurrence->date->copy()->setTimeFromTimeString($occurrence->start_time);
                    $bookingCutoff = $startDateTime->copy()->subHour();
                    if (now()->greaterThanOrEqualTo($bookingCutoff)) {
                        throw new \RuntimeException('Il tempo utile per prenotare questa lezione è scaduto.');
                    }
                }

                // Handle waitlist
                if ($action === 'waitlist') {
                    if ($alreadyWaitlisted) {
                        throw new \RuntimeException('Sei gia in lista d\'attesa per questa lezione.');
                    }

                    CourseWaitlist::query()->create([
                        'occurrence_id' => $occurrence->id,
                        'user_id' => $user->id,
                        'added_at' => now(),
                        'status' => 'waiting',
                    ]);

                    return;
                }

                // Handle booking
                $bookingsCount = CourseBooking::query()
                    ->where('occurrence_id', $occurrence->id)
                    ->lockForUpdate()
                    ->count();

                if ($occurrence->max_participants > 0 
                    && $bookingsCount + $requiredSeats > $occurrence->max_participants) {
                    throw new \RuntimeException('Posti esauriti per questa lezione.');
                }

                $availableTokens = $this->getWalletBalance($user->id);
                if ($availableTokens < 1) {
                    throw new \RuntimeException('Token insufficienti per completare la prenotazione.');
                }

                $duettoId = $isDuetto ? $user->duetto_id : null;
                if ($duettoId) {
                    $this->validateDuettoBooking($duettoId, $occurrence->id);
                }

                // Create booking
                CourseBooking::query()->create([
                    'occurrence_id' => $occurrence->id,
                    'user_id' => $user->id,
                    'booked_at' => now(),
                    'status' => $isDuetto ? 'confirmed_duetto' : 'booked',
                ]);

                if ($duettoId) {
                    CourseBooking::query()->create([
                        'occurrence_id' => $occurrence->id,
                        'user_id' => $duettoId,
                        'booked_at' => now(),
                        'status' => 'pending_duetto',
                    ]);
                }

                // Deduct tokens
                $this->deductToken($user->id, $occurrence->id, $duettoId);
                if ($duettoId) {
                    $this->deductToken($duettoId, $occurrence->id, $user->id);
                }
            });

            $this->invalidateWalletCache();
        } catch (\Throwable $exception) {
            $this->bookingError = $exception->getMessage();
            return;
        }

        $this->dispatch('close-modal', 'booking-confirm');
        $this->loadCalendar();
    }

    protected function loadFilters(): void
    {
        $this->branches = Branch::query()
            ->orderBy('name')
            ->get()
            ->map(fn (Branch $branch): array => [
                'id' => $branch->id,
                'name' => $branch->name,
            ])
            ->all();

        $this->courses = [
            ['id' => 'pilates', 'title' => 'Pilates'],
            ['id' => 'functional', 'title' => 'Functional'],
        ];

        if ($this->selectedCourse && !collect($this->courses)->contains(fn ($course) => $course['id'] === $this->selectedCourse)) {
            $this->selectedCourse = null;
        }
    }

    protected function loadCalendar(): void
    {
        $user = auth()->user();
        if ($user) {
            $this->availableTokens = $this->getWalletBalance($user->id);
        }

        $month = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
        $occurrences = $this->getOccurrencesForMonth($month);

        // Filter by weekday if selected
        if ($this->selectedWeekday) {
            $occurrences = $this->filterByWeekday($occurrences);
        }

        $specialDays = $occurrences
            ->groupBy(fn (CourseOccurrence $occurrence) => $occurrence->date->day)
            ->map(fn () => true)
            ->all();

        $selectedDate = $this->getValidatedSelectedDate($month);
        $this->selectedDate = $selectedDate->toDateString();

        // Get user bookings and waitlist
        [$userBookings, $userWaitlist] = $this->getUserBookingsAndWaitlist($user, $occurrences);

        $this->calendar = $this->buildCalendarPayload($month, $selectedDate, $specialDays, $occurrences);
        $this->lessonCards = $this->buildLessonCards($occurrences, $selectedDate, $userBookings, $userWaitlist);
        $this->lessonCardsByTrainer = $this->groupLessonsByTrainer($this->lessonCards);
        $this->trainerIndicators = $this->buildTrainerIndicators($occurrences);
        $this->trainers = $this->buildTrainers($occurrences);
    }

    protected function getOccurrencesForMonth(Carbon $month): Collection
    {
        $query = CourseOccurrence::query()
            ->with(['course.trainer', 'course.branch'])
            ->withCount('bookings')
            ->whereBetween('date', [
                $month->copy()->startOfMonth(),
                $month->copy()->endOfMonth()
            ])
            ->orderBy('date')
            ->orderBy('start_time');

        if ($this->selectedBranch) {
            $query->whereHas('course', fn ($q) => $q->where('branch_id', $this->selectedBranch));
        }

        if ($this->selectedTrainer) {
            $query->whereHas('course', fn ($q) => $q->where('trainer_id', $this->selectedTrainer));
        }

        if ($this->selectedCourse === 'pilates') {
            $query->whereHas('course', function ($q) {
                $q->whereRaw('LOWER(title) LIKE ?', ['%pilates%']);
            });
        }

        if ($this->selectedCourse === 'functional') {
            $query->whereHas('course', function ($q) {
                $q->whereRaw('LOWER(title) NOT LIKE ?', ['%pilates%']);
            });
        }

        $occurrences = $query->get();

        if ($this->selectedCourse === 'pilates') {
            return $occurrences
                ->filter(fn (CourseOccurrence $occurrence) => str_contains(strtolower($occurrence->course?->title ?? ''), 'pilates'))
                ->values();
        }

        if ($this->selectedCourse === 'functional') {
            return $occurrences
                ->filter(fn (CourseOccurrence $occurrence) => !str_contains(strtolower($occurrence->course?->title ?? ''), 'pilates'))
                ->values();
        }

        return $occurrences;
    }

    protected function filterByWeekday(Collection $occurrences): Collection
    {
        $weekdayMap = [
            'Lun' => Carbon::MONDAY,
            'Mar' => Carbon::TUESDAY,
            'Mer' => Carbon::WEDNESDAY,
            'Gio' => Carbon::THURSDAY,
            'Ven' => Carbon::FRIDAY,
            'Sab' => Carbon::SATURDAY,
            'Dom' => Carbon::SUNDAY,
        ];

        $weekday = $weekdayMap[$this->selectedWeekday] ?? null;
        if ($weekday === null) {
            return $occurrences;
        }

        return $occurrences
            ->filter(fn (CourseOccurrence $occurrence) => $occurrence->date?->dayOfWeek === $weekday)
            ->values();
    }

    protected function getValidatedSelectedDate(Carbon $month): Carbon
    {
        $selectedDate = $this->selectedDate 
            ? Carbon::parse($this->selectedDate)->startOfDay() 
            : now()->startOfDay();

        if ($selectedDate->month !== $month->month || $selectedDate->year !== $month->year) {
            $selectedDate = $month->copy();
        }

        return $selectedDate;
    }

    protected function getUserBookingsAndWaitlist(?User $user, Collection $occurrences): array
    {
        if (!$user || $occurrences->isEmpty()) {
            return [collect(), collect()];
        }

        $occurrenceIds = $occurrences->pluck('id');
        
        $userBookings = CourseBooking::query()
            ->where('user_id', $user->id)
            ->whereIn('occurrence_id', $occurrenceIds)
            ->get()
            ->keyBy('occurrence_id');

        $userWaitlist = CourseWaitlist::query()
            ->where('user_id', $user->id)
            ->whereIn('occurrence_id', $occurrenceIds)
            ->get()
            ->keyBy('occurrence_id');

        return [$userBookings, $userWaitlist];
    }

    protected function buildCalendarPayload(Carbon $month, Carbon $selectedDate, array $specialDays, Collection $occurrences): array
    {
        $weeks = [];
        $cursor = $month->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $end = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        while ($cursor->lessThanOrEqualTo($end)) {
            $week = [];
            for ($dayIndex = 0; $dayIndex < 7; $dayIndex++) {
                $week[] = $cursor->month === $month->month ? $cursor->day : null;
                $cursor->addDay();
            }
            $weeks[] = $week;
        }

        $monthNames = [
            'Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno',
            'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre',
        ];
        $weekdayNames = [
            'Domenica', 'Lunedi', 'Martedi', 'Mercoledi', 'Giovedi', 'Venerdi', 'Sabato',
        ];

        // Build trainer indicators for each day
        $trainersByDay = $this->buildTrainerIndicators($occurrences);

        return [
            'selectedLabel' => $weekdayNames[$selectedDate->dayOfWeek] . ' ' . $selectedDate->day . ' ' . $monthNames[$selectedDate->month - 1],
            'monthLabel' => $monthNames[$month->month - 1] . ' ' . $month->year,
            'weekdays' => ['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom'],
            'weeks' => $weeks,
            'selectedDay' => $selectedDate->day,
            'specialDays' => $specialDays,
            'trainersByDay' => $trainersByDay,
        ];
    }

    protected function buildLessonCards(
        Collection $occurrences, 
        Carbon $selectedDate, 
        Collection $userBookings, 
        Collection $userWaitlist
    ): array {
        return $occurrences
            ->filter(fn (CourseOccurrence $occurrence) => $occurrence->date->isSameDay($selectedDate))
            ->map(function (CourseOccurrence $occurrence) use ($userBookings, $userWaitlist): array {
                $course = $occurrence->course;
                $trainer = $course?->trainer;
                $branch = $course?->branch;
                
                $bookingsCount = (int) ($occurrence->bookings_count ?? 0);
                $maxParticipants = (int) ($occurrence->max_participants ?? 0);
                $remaining = $maxParticipants > 0 ? max(0, $maxParticipants - $bookingsCount) : null;

                $tags = $this->buildLessonTags($occurrence, $remaining, $branch);
                [$cta, $ctaVariant, $ctaDisabled, $action] = $this->buildLessonCta(
                    $occurrence, 
                    $userBookings, 
                    $userWaitlist, 
                    $maxParticipants, 
                    $bookingsCount
                );

                return [
                    'id' => 'occurrence-' . $occurrence->id,
                    'occurrence_id' => $occurrence->id,
                    'category' => $branch?->name ?? 'Corso',
                    'trainer' => $trainer?->name ?? 'Trainer',
                    'title' => $course?->title ?? 'Lezione',
                    'time' => substr($occurrence->start_time ?? '--:--', 0, 5),
                    'tags' => $tags,
                    'cta' => $cta,
                    'cta_variant' => $ctaVariant,
                    'cta_disabled' => $ctaDisabled,
                    'action' => $action,
                    'trainer_id' => $trainer?->id,
                    'trainer_color' => $this->getTrainerColor($trainer?->id),
                ];
            })
            ->values()
            ->all();
    }

    protected function buildLessonTags(CourseOccurrence $occurrence, ?int $remaining, ?Branch $branch): array
    {
        $tags = [];

        if ($occurrence->start_time && $occurrence->end_time) {
            $duration = Carbon::parse($occurrence->start_time)
                ->diffInMinutes(Carbon::parse($occurrence->end_time));
            $tags[] = $duration . ' min';
            $tags[] = substr($occurrence->start_time, 0, 5) . ' - ' . substr($occurrence->end_time, 0, 5);
        }

        if ($occurrence->max_participants) {
            $tags[] = 'Max ' . $occurrence->max_participants . ' persone';
        }

        if ($remaining !== null) {
            $tags[] = 'Posti disponibili ' . $remaining;
        }

        if ($branch?->name) {
            $tags[] = $branch->name;
        }

        return $tags ?: ['Dettagli disponibili'];
    }

    protected function buildLessonCta(
        CourseOccurrence $occurrence,
        Collection $userBookings,
        Collection $userWaitlist,
        int $maxParticipants,
        int $bookingsCount
    ): array {
        $booking = $userBookings->get($occurrence->id);
        $isBooked = $booking !== null;
        $isWaitlisted = $userWaitlist->has($occurrence->id);
        $isFull = $maxParticipants > 0 && $bookingsCount >= $maxParticipants;

        if ($isBooked && in_array($booking->status, ['pending_duetto', 'confirmed_duetto'], true)) {
            return ['In attesa duetto', 'is-secondary', true, null];
        }
        
        if ($isBooked) {
            return ['Prenotato', 'is-secondary', true, null];
        }
        
        if ($isWaitlisted) {
            return ['In lista', 'is-secondary', true, null];
        }
        
        // Time validation: prevent booking if in the past or within 1 hour of start
        if ($occurrence->start_time) {
            $startDateTime = $occurrence->date->copy()->setTimeFromTimeString($occurrence->start_time);
            $bookingCutoff = $startDateTime->copy()->subHour();

            if (now()->greaterThanOrEqualTo($bookingCutoff)) {
                if (now()->greaterThan($startDateTime)) {
                    return ['Terminata', 'is-disabled', true, null];
                }
                return ['Iscrizioni chiuse', 'is-disabled', true, null];
            }
        }

        if ($isFull) {
            return ['Lista d\'attesa', 'is-waitlist', false, 'waitlist'];
        }
        
        return ['Prenota ora', '', false, 'book'];
    }

    protected function buildTrainers(Collection $occurrences): array
    {
        $trainers = [];
        foreach ($occurrences as $occurrence) {
            $trainer = $occurrence->course?->trainer;
            if (!$trainer || isset($trainers[$trainer->id])) {
                continue;
            }
            $trainers[$trainer->id] = [
                'id' => $trainer->id,
                'name' => $trainer->name,
                'specialty' => $occurrence->course?->title ?? 'Trainer',
            ];
        }

        if ($trainers) {
            return array_values($trainers);
        }

        return User::query()
            ->where('role', 'trainer')
            ->with('courses')
            ->orderBy('name')
            ->get()
            ->map(function (User $trainer): array {
                return [
                    'id' => $trainer->id,
                    'name' => $trainer->name,
                    'specialty' => $trainer->courses->first()?->title ?? 'Trainer',
                ];
            })
            ->all();
    }

    protected function validateDuettoBooking(int $duettoId, int $occurrenceId): void
    {
        $duettoTokens = $this->getWalletBalance($duettoId);
        if ($duettoTokens < 1) {
            throw new \RuntimeException('Il tuo duetto non ha token disponibili.');
        }

        $duettoBooked = CourseBooking::query()
            ->where('user_id', $duettoId)
            ->where('occurrence_id', $occurrenceId)
            ->exists();

        $duettoWaitlisted = CourseWaitlist::query()
            ->where('user_id', $duettoId)
            ->where('occurrence_id', $occurrenceId)
            ->exists();

        if ($duettoBooked || $duettoWaitlisted) {
            throw new \RuntimeException('Il tuo duetto ha gia una prenotazione o e in attesa.');
        }
    }

    protected function deductToken(int $userId, int $occurrenceId, ?int $duettoId): void
    {
        $existingWallet = DB::table('wallets')
            ->where('user_id', $userId)
            ->where('model_type', CourseOccurrence::class)
            ->where('model_id', $occurrenceId)
            ->where('reason', 'booking')
            ->first();

        if (!$existingWallet) {
            DB::table('wallets')->insert([
                'user_id' => $userId,
                'model_type' => CourseOccurrence::class,
                'model_id' => $occurrenceId,
                'token_delta' => -1,
                'reason' => 'booking',
                'meta' => json_encode(['duetto_id' => $duettoId]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    protected function getWalletBalance(int $userId): int
    {
        if ($this->cachedUserId === $userId && $this->cachedWalletBalance !== null) {
            return $this->cachedWalletBalance;
        }

        $balance = (int) DB::table('wallets')
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now()->toDateString());
            })
            ->sum('token_delta');

        $this->cachedUserId = $userId;
        $this->cachedWalletBalance = $balance;

        return $balance;
    }

    protected function invalidateWalletCache(): void
    {
        $this->cachedUserId = null;
        $this->cachedWalletBalance = null;
    }

    protected function resetBookingState(): void
    {
        $this->bookingError = '';
        $this->confirmingDetails = [];
        $this->duettoTokens = null;
    }

    protected function groupLessonsByTrainer(array $lessonCards): array
    {
        $grouped = [];
        
        foreach ($lessonCards as $lesson) {
            $trainerId = $lesson['trainer_id'] ?? 0;
            $trainerName = $lesson['trainer'] ?? 'Trainer';
            $courseTitle = $lesson['title'] ?? 'Corso';
            
            // Initialize trainer group if not exists
            if (!isset($grouped[$trainerId])) {
                $grouped[$trainerId] = [
                    'trainer_id' => $trainerId,
                    'trainer_name' => $trainerName,
                    'trainer_color' => $lesson['trainer_color'] ?? '#7efc5b',
                    'courses' => [],
                ];
            }
            
            // Initialize course group if not exists
            if (!isset($grouped[$trainerId]['courses'][$courseTitle])) {
                $grouped[$trainerId]['courses'][$courseTitle] = [
                    'title' => $courseTitle,
                    'category' => $lesson['category'] ?? 'Corso',
                    'time_slots' => [],
                ];
            }
            
            // Add time slot to course
            $grouped[$trainerId]['courses'][$courseTitle]['time_slots'][] = [
                'occurrence_id' => $lesson['occurrence_id'],
                'time' => $lesson['time'] ?? '--:--',
                'tags' => $lesson['tags'] ?? [],
                'cta' => $lesson['cta'],
                'cta_variant' => $lesson['cta_variant'],
                'cta_disabled' => $lesson['cta_disabled'],
                'action' => $lesson['action'],
            ];
        }
        
        // Convert courses associative array to indexed array and sort
        foreach ($grouped as &$trainerGroup) {
            $trainerGroup['courses'] = array_values($trainerGroup['courses']);
            // Sort courses by title
            usort($trainerGroup['courses'], fn($a, $b) => strcmp($a['title'], $b['title']));
        }
        
        // Sort by trainer name
        usort($grouped, fn($a, $b) => strcmp($a['trainer_name'], $b['trainer_name']));
        
        return array_values($grouped);
    }

    protected function buildTrainerIndicators(Collection $occurrences): array
    {
        $indicators = [];
        
        // Group occurrences by day
        $byDay = $occurrences->groupBy(fn (CourseOccurrence $occ) => $occ->date->day);
        
        foreach ($byDay as $day => $dayOccurrences) {
            $trainers = [];
            $seenTrainers = [];
            
            foreach ($dayOccurrences as $occurrence) {
                $trainer = $occurrence->course?->trainer;
                if ($trainer && !isset($seenTrainers[$trainer->id])) {
                    $trainers[] = [
                        'id' => $trainer->id,
                        'name' => $trainer->name,
                        'initials' => $this->getTrainerInitials($trainer->name),
                        'color' => $this->getTrainerColor($trainer->id),
                    ];
                    $seenTrainers[$trainer->id] = true;
                }
            }
            
            if (!empty($trainers)) {
                $indicators[$day] = $trainers;
            }
        }
        
        return $indicators;
    }

    protected function getTrainerColor(?int $trainerId): string
    {
        if (!$trainerId) {
            return '#7efc5b';
        }
        
        // Predefined color palette for trainers
        $colors = [
            '#7efc5b', // Green
            '#f35aa7', // Pink
            '#5b9cfc', // Blue
            '#fcb45b', // Orange
            '#a45bfc', // Purple
            '#5bfcdb', // Cyan
            '#fc5b5b', // Red
            '#fcfc5b', // Yellow
        ];
        
        return $colors[$trainerId % count($colors)];
    }

    protected function getTrainerInitials(string $name): string
    {
        $words = explode(' ', trim($name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    }

    public function render()
    {
        return view('livewire.pages.calendar');
    }
}
