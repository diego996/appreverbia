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
    public ?string $selectedStartTime = null;
    public ?string $selectedEndTime = null;
    
    public ?int $confirmingOccurrenceId = null;
    public ?string $confirmingAction = null;
    public string $selectedBookingType = 'functional';
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
        // No-op: apply via applyFilters()
    }

    public function updatedSelectedTrainer(): void
    {
        // No-op: apply via applyFilters()
    }

    public function updatedSelectedWeekday(): void
    {
        // No-op: apply via applyFilters()
    }

    public function updatedSelectedCourse(): void
    {
        // No-op: apply via applyFilters()
    }

    public function updatedSelectedStartTime(): void
    {
        // No-op: apply via applyFilters()
    }

    public function updatedSelectedEndTime(): void
    {
        // No-op: apply via applyFilters()
    }

    public function applyFilters(): void
    {
        if ($this->selectedCourse) {
            $this->selectedCourse = strtolower($this->selectedCourse);
        }

        $this->selectedStartTime = $this->selectedStartTime ?: null;
        $this->selectedEndTime = $this->selectedEndTime ?: null;

        if ($this->selectedStartTime && $this->selectedEndTime && $this->selectedStartTime > $this->selectedEndTime) {
            [$this->selectedStartTime, $this->selectedEndTime] = [$this->selectedEndTime, $this->selectedStartTime];
        }

        if (!$this->selectedBranch) {
            $this->selectedTrainer = null;
        }

        $this->loadFilters();
        $this->loadCalendar();
    }

    public function setTrainerFilter(?int $trainerId): void
    {
        if (!$this->selectedBranch) {
            return;
        }

        $this->selectedTrainer = $this->selectedTrainer === $trainerId ? null : $trainerId;
        $this->applyFilters();
    }

    public function updatedConfirmDuetto(): void
    {
        if (!$this->hasDuetto || $this->confirmingAction !== 'book') {
            $this->confirmDuetto = false;
        }

        if ($this->confirmingOccurrenceId && $this->selectedBookingType === 'pilates') {
            $occurrence = CourseOccurrence::query()
                ->withCount([
                    'bookings as active_bookings_count' => fn ($query) => $query
                        ->whereNotIn('status', ['cancelled', 'canceled']),
                    'bookings as pilates_bookings_count' => fn ($query) => $query
                        ->whereRaw("LOWER(IFNULL(notes, '')) LIKE ?", ['%pilates%'])
                        ->whereNotIn('status', ['cancelled', 'canceled']),
                ])
                ->find($this->confirmingOccurrenceId);

            if ($occurrence) {
                $availability = $this->getOccurrenceAvailability($occurrence);
                $requiredPilatesSeats = $this->confirmDuetto ? 2 : 1;
                if ($availability['pilates_available'] < $requiredPilatesSeats) {
                    $this->selectedBookingType = 'functional';
                }
            }
        }
    }

    public function updatedSelectedBookingType(): void
    {
        if ($this->confirmingAction !== 'book' || !$this->confirmingOccurrenceId) {
            $this->selectedBookingType = 'functional';
            return;
        }

        if (!in_array($this->selectedBookingType, ['functional', 'pilates'], true)) {
            $this->selectedBookingType = 'functional';
        }

        if ($this->selectedBookingType === 'pilates') {
            $occurrence = CourseOccurrence::query()
                ->withCount([
                    'bookings as active_bookings_count' => fn ($query) => $query
                        ->whereNotIn('status', ['cancelled', 'canceled']),
                    'bookings as pilates_bookings_count' => fn ($query) => $query
                        ->whereRaw("LOWER(IFNULL(notes, '')) LIKE ?", ['%pilates%'])
                        ->whereNotIn('status', ['cancelled', 'canceled']),
                ])
                ->find($this->confirmingOccurrenceId);

            if ($occurrence) {
                $availability = $this->getOccurrenceAvailability($occurrence);
                $requiredPilatesSeats = $this->confirmDuetto ? 2 : 1;
                if ($availability['pilates_available'] < $requiredPilatesSeats) {
                    $this->selectedBookingType = 'functional';
                }
            }
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
            ->withCount([
                'bookings as active_bookings_count' => fn ($query) => $query
                    ->whereNotIn('status', ['cancelled', 'canceled']),
                'bookings as pilates_bookings_count' => fn ($query) => $query
                    ->whereRaw("LOWER(IFNULL(notes, '')) LIKE ?", ['%pilates%'])
                    ->whereNotIn('status', ['cancelled', 'canceled']),
            ])
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

        $availability = $this->getOccurrenceAvailability($occurrence);

        // Check if full
        $isFull = $occurrence->max_participants > 0
            && $availability['general_remaining'] <= 0;

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
            'booked' => $availability['active_bookings'],
            'pilates_booked' => $availability['pilates_booked'],
            'pilates_limit' => $availability['pilates_limit'],
            'pilates_available' => $availability['pilates_available'],
            'trainer_initials' => $this->getTrainerInitials($occurrence->course?->trainer?->name ?? 'Trainer'),
            'trainer_color' => $this->getTrainerColor($occurrence->course?->trainer?->id),
        ];

        if ($action === 'book') {
            $this->selectedBookingType = in_array($this->selectedCourse, ['pilates', 'functional'], true)
                ? $this->selectedCourse
                : 'functional';

            if ($this->selectedBookingType === 'pilates' && $availability['pilates_available'] < 1) {
                $this->selectedBookingType = 'functional';
            }
        }

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
        $bookingType = $action === 'book' && $this->selectedBookingType === 'pilates'
            ? 'pilates'
            : 'functional';
        $isDuetto = $action === 'book' && $this->confirmDuetto && $user->duetto_id;
        $requiredSeats = $isDuetto ? 2 : 1;

        try {
            DB::transaction(function () use ($user, $action, $bookingType, $isDuetto, $requiredSeats) {
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
                    ->whereNotIn('status', ['cancelled', 'canceled'])
                    ->lockForUpdate()
                    ->count();

                if ($occurrence->max_participants > 0 
                    && $bookingsCount + $requiredSeats > $occurrence->max_participants) {
                    throw new \RuntimeException('Posti esauriti per questa lezione.');
                }

                if ($bookingType === 'pilates') {
                    $pilatesBooked = CourseBooking::query()
                        ->where('occurrence_id', $occurrence->id)
                        ->whereRaw("LOWER(IFNULL(notes, '')) LIKE ?", ['%pilates%'])
                        ->whereNotIn('status', ['cancelled', 'canceled'])
                        ->lockForUpdate()
                        ->count();

                    if ($pilatesBooked + $requiredSeats > $this->getPilatesLimit()) {
                        throw new \RuntimeException('Postazioni Pilates esaurite per questo orario.');
                    }
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
                    'notes' => $bookingType === 'pilates' ? 'pilates' : null,
                ]);

                if ($duettoId) {
                    CourseBooking::query()->create([
                        'occurrence_id' => $occurrence->id,
                        'user_id' => $duettoId,
                        'booked_at' => now(),
                        'status' => 'confirmed_duetto',
                        'notes' => $bookingType === 'pilates' ? 'pilates' : null,
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

        $trainersQuery = User::query()
            ->where('role', 'trainer')
            ->whereHas('courses', function ($query) {
                if ($this->selectedBranch) {
                    $query->where('branch_id', $this->selectedBranch);
                }
            })
            ->orderBy('name');

        $this->trainers = $trainersQuery
            ->get()
            ->map(function (User $trainer): array {
                return [
                    'id' => $trainer->id,
                    'name' => $trainer->name,
                ];
            })
            ->all();

        if ($this->selectedTrainer && !collect($this->trainers)->contains(fn ($trainer) => $trainer['id'] === $this->selectedTrainer)) {
            $this->selectedTrainer = null;
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
    }

    protected function getOccurrencesForMonth(Carbon $month): Collection
    {
        $query = CourseOccurrence::query()
            ->with(['course.trainer', 'course.branch'])
            ->withCount([
                'bookings as active_bookings_count' => fn ($q) => $q
                    ->whereNotIn('status', ['cancelled', 'canceled']),
                'bookings as pilates_bookings_count' => fn ($q) => $q
                    ->whereRaw("LOWER(IFNULL(notes, '')) LIKE ?", ['%pilates%'])
                    ->whereNotIn('status', ['cancelled', 'canceled']),
            ])
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

        if ($this->selectedStartTime) {
            $query->whereTime('start_time', '>=', $this->selectedStartTime);
        }

        if ($this->selectedEndTime) {
            $query->whereTime('start_time', '<=', $this->selectedEndTime);
        }

        $occurrences = $query->get();

        if ($this->selectedCourse === 'pilates') {
            return $occurrences
                ->filter(fn (CourseOccurrence $occurrence) => $this->isActivityAvailable($occurrence, 'pilates'))
                ->values();
        }

        if ($this->selectedCourse === 'functional') {
            return $occurrences
                ->filter(fn (CourseOccurrence $occurrence) => $this->isActivityAvailable($occurrence, 'functional'))
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
            ->filter(function (CourseOccurrence $occurrence): bool {
                if (!in_array($this->selectedCourse, ['pilates', 'functional'], true)) {
                    return true;
                }

                return $this->isActivityAvailable($occurrence, $this->selectedCourse);
            })
            ->map(function (CourseOccurrence $occurrence) use ($userBookings, $userWaitlist): array {
                $course = $occurrence->course;
                $trainer = $course?->trainer;
                $branch = $course?->branch;
                $availability = $this->getOccurrenceAvailability($occurrence);
                $bookingsCount = $availability['active_bookings'];
                $maxParticipants = (int) ($occurrence->max_participants ?? 0);
                $remaining = $maxParticipants > 0 ? $availability['general_remaining'] : null;

                $tags = $this->buildLessonTags($occurrence, $remaining, $branch, $availability);
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

    protected function buildLessonTags(
        CourseOccurrence $occurrence,
        ?int $remaining,
        ?Branch $branch,
        array $availability
    ): array
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

        $tags[] = 'Pilates disponibili ' . $availability['pilates_available'] . '/' . $availability['pilates_limit'];

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

    protected function getOccurrenceAvailability(CourseOccurrence $occurrence): array
    {
        $activeBookings = (int) ($occurrence->active_bookings_count ?? $occurrence->bookings_count ?? 0);
        $pilatesBooked = (int) ($occurrence->pilates_bookings_count ?? 0);
        $pilatesLimit = $this->getPilatesLimit();
        $generalRemaining = $occurrence->max_participants > 0
            ? max(0, $occurrence->max_participants - $activeBookings)
            : PHP_INT_MAX;

        return [
            'active_bookings' => $activeBookings,
            'pilates_booked' => $pilatesBooked,
            'pilates_limit' => $pilatesLimit,
            'general_remaining' => $generalRemaining,
            'pilates_available' => max(0, min($pilatesLimit - $pilatesBooked, $generalRemaining)),
            'functional_available' => max(0, $generalRemaining),
        ];
    }

    protected function isActivityAvailable(CourseOccurrence $occurrence, string $activity): bool
    {
        $availability = $this->getOccurrenceAvailability($occurrence);

        if ($activity === 'pilates') {
            return $availability['pilates_available'] > 0;
        }

        return $availability['functional_available'] > 0;
    }

    protected function getPilatesLimit(): int
    {
        return 2;
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
        $this->selectedBookingType = 'functional';
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
