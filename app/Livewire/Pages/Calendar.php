<?php

namespace App\Livewire\Pages;

use App\Models\CourseOccurrence;
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
    public int $currentMonth;
    public int $currentYear;
    public ?string $selectedDate = null;

    public function mount(): void
    {
        $today = now();
        $this->currentMonth = $today->month;
        $this->currentYear = $today->year;
        $this->selectedDate = $today->toDateString();

        $this->menuLinks = [
            ['icon' => 'bi-house-door', 'label' => 'Home', 'url' => route('home')],
            ['icon' => 'bi-calendar4-week', 'label' => 'Calendario', 'url' => route('calendar')],
            ['icon' => 'bi-bag-plus', 'label' => 'Acquista', 'url' => '#'],
            ['icon' => 'bi-chat-dots', 'label' => 'Supporto', 'url' => '#'],
        ];

        $this->loadCalendar();
    }

    protected function loadCalendar(): void
    {
        $user = auth()->user();

        $branchId = $user?->branch_id;
        $month = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
        $occurrences = CourseOccurrence::query()
            ->with(['course.trainer', 'course.branch'])
            ->when($branchId, function ($query) use ($branchId) {
                $query->whereHas('course', function ($courseQuery) use ($branchId) {
                    $courseQuery->where('branch_id', $branchId);
                });
            })
            ->whereBetween('date', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $specialDays = $occurrences
            ->groupBy(fn (CourseOccurrence $occurrence) => $occurrence->date->day)
            ->map(fn () => true)
            ->all();

        $selectedDate = $this->selectedDate ? Carbon::parse($this->selectedDate)->startOfDay() : now()->startOfDay();
        if ($selectedDate->month !== $month->month || $selectedDate->year !== $month->year) {
            $selectedDate = $month->copy();
        }
        if ($occurrences->isNotEmpty()) {
            $hasOccurrence = $occurrences->contains(fn (CourseOccurrence $occurrence) => $occurrence->date->isSameDay($selectedDate));
            if (!$hasOccurrence) {
                $selectedDate = $occurrences->first()->date;
            }
        }

        $this->selectedDate = $selectedDate->toDateString();

        $this->calendar = $this->buildCalendarPayload($month, $selectedDate, $specialDays);
        $this->lessonCards = $this->buildLessonCards($occurrences, $selectedDate);
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

    protected function buildLessonCards(Collection $occurrences, Carbon $selectedDate): array
    {
        return $occurrences
            ->filter(fn (CourseOccurrence $occurrence) => $occurrence->date->isSameDay($selectedDate))
            ->map(function (CourseOccurrence $occurrence): array {
                $course = $occurrence->course;
                $trainer = $course?->trainer;
                $branch = $course?->branch;
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

                if ($branch?->name) {
                    $tags[] = $branch->name;
                }

                if (!$tags) {
                    $tags[] = 'Dettagli disponibili';
                }

                return [
                    'id' => 'occurrence-' . $occurrence->id,
                    'category' => $branch?->name ?? 'Corso',
                    'trainer' => $trainer?->name ?? 'Trainer',
                    'title' => $course?->title ?? 'Lezione',
                    'tags' => $tags,
                    'cta' => 'Prenota ora',
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
                'id' => 'trainer-' . $trainer->id,
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
                    'id' => 'trainer-' . $trainer->id,
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
