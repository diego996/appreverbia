<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseOccurrence;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CourseOccurrenceSeeder extends Seeder
{
    /**
     * Seed course occurrences for the entire year 2026.
     */
    public function run(): void
    {
        echo "🌱 Seeding course occurrences for 2026...\n\n";

        // Get all courses
        $courses = Course::with('branch', 'trainer')->get();

        if ($courses->isEmpty()) {
            echo "❌ No courses found! Please seed courses first.\n";
            return;
        }

        echo "Found {$courses->count()} course(s):\n";
        foreach ($courses as $course) {
            echo "  - {$course->title} (Trainer: {$course->trainer?->name})\n";
        }
        echo "\n";

        // Clear existing 2026 occurrences
        $deleted = CourseOccurrence::whereBetween('date', ['2026-01-01', '2026-12-31'])->delete();
        echo "Deleted {$deleted} existing 2026 occurrences\n\n";

        // Define timeslots
        $timeslots = [
            ['start' => '07:00:00', 'end' => '08:00:00'],   // Early morning
            ['start' => '09:00:00', 'end' => '10:00:00'],   // Morning
            ['start' => '10:30:00', 'end' => '11:30:00'],   // Late morning
            ['start' => '12:00:00', 'end' => '13:00:00'],   // Lunch
            ['start' => '17:00:00', 'end' => '18:00:00'],   // Evening
            ['start' => '18:30:00', 'end' => '19:30:00'],   // Late evening
            ['start' => '20:00:00', 'end' => '21:00:00'],   // Night
        ];

        $startDate = Carbon::create(2026, 1, 1);
        $endDate = Carbon::create(2026, 12, 31);

        $created = 0;
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            // Skip Sundays
            if ($current->dayOfWeek === Carbon::SUNDAY) {
                $current->addDay();
                continue;
            }

            // Determine number of slots based on day of week
            $slotsPerDay = match($current->dayOfWeek) {
                Carbon::SATURDAY => 3,  // Fewer on Saturday
                Carbon::MONDAY, Carbon::WEDNESDAY, Carbon::FRIDAY => 5,  // Peak days
                default => 4,  // Tuesday, Thursday
            };

            // Rotate through courses to distribute evenly
            $coursesForDay = $courses->shuffle()->take(min($courses->count(), $slotsPerDay));

            foreach ($coursesForDay as $index => $course) {
                // Select timeslot
                $slot = $timeslots[$index % count($timeslots)];

                // Vary max participants
                $maxParticipants = match($current->dayOfWeek) {
                    Carbon::SATURDAY => rand(6, 10),  // Smaller on Saturday
                    default => rand(10, 20),  // Regular capacity
                };

                CourseOccurrence::create([
                    'course_id' => $course->id,
                    'date' => $current->toDateString(),
                    'start_time' => $slot['start'],
                    'end_time' => $slot['end'],
                    'max_participants' => $maxParticipants,
                ]);

                $created++;
            }

            $current->addDay();

            // Progress indicator every month
            if ($current->day === 1) {
                echo "✓ Completed " . $current->copy()->subMonth()->format('F Y') . "\n";
            }
        }

        echo "\n✅ Created {$created} occurrences for 2026\n";

        // Summary by month
        echo "\nOccurrences by month:\n";
        for ($month = 1; $month <= 12; $month++) {
            $count = CourseOccurrence::whereYear('date', 2026)
                ->whereMonth('date', $month)
                ->count();
            $monthName = Carbon::create(2026, $month, 1)->format('F');
            echo sprintf("  %s: %d occurrences\n", str_pad($monthName, 10), $count);
        }

        echo "\n🎉 Seeding complete!\n";
    }
}
