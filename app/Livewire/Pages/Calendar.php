<?php

namespace App\Livewire\Pages;

use App\Models\Branch;
use App\Models\Course;
use App\Models\CourseBooking;
use App\Models\CourseOccurrence;
use App\Models\CourseWaitlist;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.reverbia-shell')]
#[Title('Reverbia - Calendario')]
class Calendar extends Component
{
    public array $trainers = [];
    public array $calendar = [];
    public array $lessonCards = [];
    public array $menuLinks = [];
    public array $branches = [];
    public array $courses = [];
    public int $currentMonth;
    public int $currentYear;
    public ?string $selectedDate = null;
    public ?int $selectedBranch = null;
    public ?int $selectedTrainer = null;
    public ?int $selectedCourse = null;
    public ?string $selectedWeekday = null;

    public function mount(): void
    {
        $today = now();
        $this->currentMonth = $today->month;
        $this->currentYear = $today->year;
        $this->selectedDate = $today->toDateString();
        $this->selectedBranch = auth()->user()?->branch_id;

        $this->menuLinks = [
            ['icon' => 'bi-house-door', 'label' => 'Home', 'url' => route('home')],
            ['icon' => 'bi-calendar4-week', 'label' => 'Calendario', 'url' => route('calendar')],
            ['icon' => 'bi-bag-plus', 'label' => 'Acquista', 'url' => '#'],
            ['icon' => 'bi-chat-dots', 'label' => 'Supporto', 'url' => '#'],
        ];

        $this->loadFilters();
        $this->loadCalendar();
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

        $coursesQuery = Course::query()->orderBy('title');
        if ($this->selectedBranch) {
            $coursesQuery->where('branch_id', $this->selectedBranch);
        }

        $this->courses = $coursesQuery
            ->get()
            ->map(fn (Course $course): array => [
                'id' => $course->id,
                'title' => $course->title,
            ])
            ->all();

        if ($this->selectedCourse && !collect($this->courses)->contains(fn ($course) => $course['id'] === $this->selectedCourse)) {
            $this->selectedCourse = null;
        }
    }

    protected function loadCalendar(): void
    {
        $user = auth()->user();

        $month = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
        $occurrences = CourseOccurrence::query()
            ->with(['course.trainer', 'course.branch'])
            ->withCount('bookings')
            ->when($this->selectedBranch, function ($query) {
                $query->whereHas('course', function ($courseQuery) {
                    $courseQuery->where('branch_id', $this->selectedBranch);
                });
            })
            ->when($this->selectedTrainer, function ($query) {
                $query->whereHas('course', function ($courseQuery) {
                    $courseQuery->where('trainer_id', $this->selectedTrainer);
                });
            })
            ->when($this->selectedCourse, function ($query) {
                $query->where('course_id', $this->selectedCourse);
            })
            ->whereBetween('date', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        if ($this->selectedWeekday) {
            $weekdayMap = $this->weekdayMap();
            $weekday = $weekdayMap[$this->selectedWeekday] ?? null;
            if ($weekday !== null) {
                $occurrences = $occurrences
                    ->filter(fn (CourseOccurrence $occurrence) => $occurrence->date?->dayOfWeek === $weekday)
                    ->values();
            }
        }

        $specialDays = $occurrences
            ->groupBy(fn (CourseOccurrence $occurrence) => $occurrence->date->day)
            ->map(fn () => true)
            ->all();

        $selectedDate = $this->selectedDate ? Carbon::parse($this->selectedDate)->startOfDay() : now()->startOfDay();
        if ($selectedDate->month !== $month->month || $selectedDate->year !== $month->year) {
            $selectedDate = $month->copy();
        }

        $this->selectedDate = $selectedDate->toDateString();

        $userBookings = collect();
        $userWaitlist = collect();
        if ($user && $occurrences->isNotEmpty()) {
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
        }

        $this->calendar = $this->buildCalendarPayload($month, $selectedDate, $specialDays);
        $this->lessonCards = $this->buildLessonCards($occurrences, $selectedDate, $userBookings, $userWaitlist);
        $this->trainers = $this->buildTrainers($occurrences);
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

    public function bookOccurrence(int $occurrenceId): void
    {
        $user = auth()->user();
        if (!$user) {
            return;
        }

        $occurrence = CourseOccurrence::query()
            ->withCount('bookings')
            ->find($occurrenceId);

        if (!$occurrence) {
            return;
        }

        $alreadyBooked = CourseBooking::query()
            ->where('user_id', $user->id)
            ->where('occurrence_id', $occurrenceId)
            ->exists();

        if ($alreadyBooked) {
            return;
        }

        $alreadyWaitlisted = CourseWaitlist::query()
            ->where('user_id', $user->id)
            ->where('occurrence_id', $occurrenceId)
            ->exists();

        if ($alreadyWaitlisted) {
            return;
        }

        $isFull = $occurrence->max_participants > 0 && $occurrence->bookings_count >= $occurrence->max_participants;

        if ($isFull) {
            CourseWaitlist::query()->create([
                'occurrence_id' => $occurrenceId,
                'user_id' => $user->id,
                'added_at' => now(),
                'status' => 'waiting',
            ]);
        } else {
            CourseBooking::query()->create([
                'occurrence_id' => $occurrenceId,
                'user_id' => $user->id,
                'booked_at' => now(),
                'status' => 'booked',
            ]);
        }

        $this->loadCalendar();
    }

    protected function weekdayMap(): array
    {
        return [
            'Lun' => Carbon::MONDAY,
            'Mar' => Carbon::TUESDAY,
            'Mer' => Carbon::WEDNESDAY,
            'Gio' => Carbon::THURSDAY,
            'Ven' => Carbon::FRIDAY,
            'Sab' => Carbon::SATURDAY,
            'Dom' => Carbon::SUNDAY,
        ];
    }

    protected function buildCalendarPayload(Carbon $month, Carbon $selectedDate, array $specialDays): array
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

        return [
            'selectedLabel' => $weekdayNames[$selectedDate->dayOfWeek] . ' ' . $selectedDate->day . ' ' . $monthNames[$selectedDate->month - 1],
            'monthLabel' => $monthNames[$month->month - 1] . ' ' . $month->year,
            'weekdays' => ['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom'],
            'weeks' => $weeks,
            'selectedDay' => $selectedDate->day,
            'specialDays' => $specialDays,
        ];
    }

    protected function buildLessonCards(Collection $occurrences, Carbon $selectedDate, Collection $userBookings, Collection $userWaitlist): array
    {
        return $occurrences
            ->filter(fn (CourseOccurrence $occurrence) => $occurrence->date->isSameDay($selectedDate))
            ->map(function (CourseOccurrence $occurrence) use ($userBookings, $userWaitlist): array {
                $course = $occurrence->course;
                $trainer = $course?->trainer;
                $branch = $course?->branch;
                $tags = [];
                $bookingsCount = (int) ($occurrence->bookings_count ?? 0);
                $maxParticipants = (int) ($occurrence->max_participants ?? 0);
                $remaining = $maxParticipants > 0 ? max(0, $maxParticipants - $bookingsCount) : null;

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

                if (!$tags) {
                    $tags[] = 'Dettagli disponibili';
                }

                $isBooked = $userBookings->has($occurrence->id);
                $isWaitlisted = $userWaitlist->has($occurrence->id);
                $isFull = $maxParticipants > 0 && $bookingsCount >= $maxParticipants;

                if ($isBooked) {
                    $cta = 'Prenotato';
                    $ctaVariant = 'is-secondary';
                    $ctaDisabled = true;
                } elseif ($isWaitlisted) {
                    $cta = 'In lista';
                    $ctaVariant = 'is-secondary';
                    $ctaDisabled = true;
                } elseif ($isFull) {
                    $cta = 'Lista d\'attesa';
                    $ctaVariant = 'is-waitlist';
                    $ctaDisabled = false;
                } else {
                    $cta = 'Prenota ora';
                    $ctaVariant = '';
                    $ctaDisabled = false;
                }

                return [
                    'id' => 'occurrence-' . $occurrence->id,
                    'occurrence_id' => $occurrence->id,
                    'category' => $branch?->name ?? 'Corso',
                    'trainer' => $trainer?->name ?? 'Trainer',
                    'title' => $course?->title ?? 'Lezione',
                    'tags' => $tags,
                    'cta' => $cta,
                    'cta_variant' => $ctaVariant,
                    'cta_disabled' => $ctaDisabled,
                ];
            })
            ->values()
            ->all();
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
                $courseTitle = $trainer->courses->first()?->title;

                return [
                    'id' => $trainer->id,
                    'name' => $trainer->name,
                    'specialty' => $courseTitle ?? 'Trainer',
                ];
            })
            ->all();
    }

    public function render()
    {
        return view('livewire.pages.calendar');
    }
}
