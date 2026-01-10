<?php

use App\Models\CourseOccurrence;
use Carbon\Carbon;

// Get current date info
$today = now();
echo "Today: " . $today->toDateString() . " (" . $today->format('Y-m-d H:i:s') . ")\n";
echo "Current month: " . $today->month . "\n";
echo "Current year: " . $today->year . "\n\n";

// Check what's in the database
$allOccurrences = CourseOccurrence::orderBy('date')->get();
echo "Total occurrences in DB: " . $allOccurrences->count() . "\n\n";

if ($allOccurrences->count() > 0) {
    echo "First 5 occurrences:\n";
    foreach ($allOccurrences->take(5) as $occ) {
        echo sprintf(
            "  ID: %d | Date: %s | Start: %s | Course ID: %d\n",
            $occ->id,
            $occ->date ? $occ->date->format('Y-m-d') : 'NULL',
            $occ->start_time ?? 'NULL',
            $occ->course_id ?? 0
        );
    }
    echo "\n";
}

// Test the query from Calendar component
$month = Carbon::create($today->year, $today->month, 1)->startOfMonth();
echo "Month range for query:\n";
echo "  Start: " . $month->copy()->startOfMonth()->format('Y-m-d H:i:s') . "\n";
echo "  End: " . $month->copy()->endOfMonth()->format('Y-m-d H:i:s') . "\n\n";

$occurrencesInMonth = CourseOccurrence::whereBetween('date', [
    $month->copy()->startOfMonth(),
    $month->copy()->endOfMonth()
])->get();

echo "Occurrences found in current month: " . $occurrencesInMonth->count() . "\n";

if ($occurrencesInMonth->count() > 0) {
    echo "Dates in current month:\n";
    foreach ($occurrencesInMonth as $occ) {
        echo "  " . ($occ->date ? $occ->date->format('Y-m-d') : 'NULL') . "\n";
    }
}
