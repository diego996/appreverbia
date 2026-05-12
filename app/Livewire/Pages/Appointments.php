<?php

namespace App\Livewire\Pages;

use App\Models\CourseBooking;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.reverbia-shell')]
#[Title('Reverbia - Appuntamenti')]
class Appointments extends Component
{
    use WithPagination;

    public string $search = '';
    public string $scope = 'all';
    public int $perPage = 12;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingScope(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        $query = CourseBooking::query()
            ->where('user_id', $user->id)
            ->with(['occurrence.course.trainer', 'occurrence.course.branch'])
            ->whereHas('occurrence')
            ->orderByDesc('booked_at');

        if ($this->scope === 'future') {
            $query->whereHas('occurrence', fn ($q) => $q->where('date', '>=', now()->toDateString()));
        } elseif ($this->scope === 'past') {
            $query->whereHas('occurrence', fn ($q) => $q->where('date', '<', now()->toDateString()));
        }

        if (trim($this->search) !== '') {
            $search = trim($this->search);
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', '%' . $search . '%')
                    ->orWhereHas('occurrence.course', fn ($qq) => $qq->where('title', 'like', '%' . $search . '%'))
                    ->orWhereHas('occurrence.course.trainer', fn ($qq) => $qq->where('name', 'like', '%' . $search . '%'))
                    ->orWhereHas('occurrence.course.branch', fn ($qq) => $qq->where('name', 'like', '%' . $search . '%'))
                    ->orWhereHas('occurrence', fn ($qq) => $qq->where('date', 'like', '%' . $search . '%'));
            });
        }

        $statusLabels = [
            'booked' => 'Confermato',
            'confirmed' => 'Confermato',
            'confirmed_duetto' => 'Confermato',
            'pending_duetto' => 'Confermato',
            'waiting' => 'In attesa',
            'cancelled' => 'Annullato',
            'canceled' => 'Annullato',
        ];

        $appointments = $query
            ->paginate($this->perPage)
            ->through(function (CourseBooking $booking) use ($statusLabels): array {
                $occurrence = $booking->occurrence;
                $course = $occurrence?->course;

                return [
                    'id' => $booking->id,
                    'date' => $occurrence?->date?->format('d/m/Y') ?? '-',
                    'time' => $occurrence?->start_time ? substr($occurrence->start_time, 0, 5) : '--:--',
                    'title' => $course?->title ?? 'Lezione',
                    'trainer' => $course?->trainer?->name ?? 'Trainer',
                    'branch' => $course?->branch?->name ?? 'Sede',
                    'status' => $statusLabels[$booking->status] ?? ucfirst($booking->status ?? 'Prenotato'),
                ];
            });

        return view('livewire.pages.appointments', [
            'appointments' => $appointments,
        ]);
    }
}

