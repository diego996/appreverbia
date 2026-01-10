<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Course;
use App\Models\CourseOccurrence;
use Carbon\Carbon;

echo "=== CREATING JANUARY 2026 OCCURRENCES ===" . PHP_EOL . PHP_EOL;

// Get existing courses
$courses = Course::with('branch', 'trainer')->get();

if ($courses->isEmpty()) {
    echo "ERROR: No courses found in database!" . PHP_EOL;
    echo "Please run: php artisan db:seed first" . PHP_EOL;
    exit(1);
}

echo "Found " . $courses->count() . " course(s)" . PHP_EOL;
foreach ($courses as $course) {
    echo "  - " . $course->title . " (Branch: " . ($course->branch?->name ?? 'N/A') . ")" . PHP_EOL;
}
echo PHP_EOL;

// Create occurrences for January 2026
$today = now();
$startDate = Carbon::create(2026, 1, 13); // Start from Monday, Jan 13
$endDate = Carbon::create(2026, 1, 31);   // End of January

$timeslots = [
    ['start' => '09:00:00', 'end' => '10:00:00'],
    ['start' => '10:30:00', 'end' => '11:30:00'],
    ['start' => '18:00:00', 'end' => '19:00:00'],
    ['start' => '19:30:00', 'end' => '20:30:00'],
];

$created = 0;
$current = $startDate->copy();

while ($current->lte($endDate)) {
    // Skip Sundays
    if ($current->dayOfWeek === Carbon::SUNDAY) {
        $current->addDay();
        continue;
    }
    
    // Create 2-3 occurrences per day
    $slotsToday = $current->dayOfWeek === Carbon::SATURDAY ? 2 : 3;
    
    foreach ($courses as $course) {
        for ($i = 0; $i < min($slotsToday, count($timeslots)); $i++) {
            $slot = $timeslots[$i];
            
            CourseOccurrence::create([
                'course_id' => $course->id,
                'date' => $current->toDateString(),
                'start_time' => $slot['start'],
                'end_time' => $slot['end'],
                'max_participants' => rand(8, 15),
            ]);
            
            $created++;
        }
    }
    
    $current->addDay();
}

echo "Created $created new occurrences for January 2026" . PHP_EOL;
echo PHP_EOL;

// Verify
$janOccs = CourseOccurrence::whereBetween('date', ['2026-01-01', '2026-01-31'])
    ->orderBy('date')
    ->orderBy('start_time')
    ->get();

echo "Total occurrences in January 2026: " . $janOccs->count() . PHP_EOL;

if ($janOccs->count() > 0) {
    echo PHP_EOL . "Sample occurrences:" . PHP_EOL;
    foreach ($janOccs->take(10) as $occ) {
        echo "  - " . $occ->date->format('Y-m-d (D)') . " at " . substr($occ->start_time, 0, 5) . 
             " - " . ($occ->course?->title ?? 'N/A') . PHP_EOL;
    }
}

echo PHP_EOL . "✅ Done! The calendar should now show lessons." . PHP_EOL;
