<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CourseOccurrence;
use Carbon\Carbon;

echo "=== CALENDAR DEBUG ===" . PHP_EOL . PHP_EOL;

$today = now();
echo "Today: " . $today->format('Y-m-d H:i:s') . PHP_EOL;
echo "Current month: " . $today->month . "/" . $today->year . PHP_EOL . PHP_EOL;

$allOccs = CourseOccurrence::orderBy('date')->get();
echo "Total occurrences in database: " . $allOccs->count() . PHP_EOL . PHP_EOL;

if ($allOccs->count() > 0) {
    echo "Date range:" . PHP_EOL;
    echo "  First: " . $allOccs->first()->date->format('Y-m-d') . PHP_EOL;
    echo "  Last: " . $allOccs->last()->date->format('Y-m-d') . PHP_EOL . PHP_EOL;
    
    echo "Occurrences by month:" . PHP_EOL;
    $grouped = $allOccs->groupBy(fn($o) => $o->date->format('Y-m'));
    foreach ($grouped as $month => $items) {
        echo "  $month: " . $items->count() . " occurrences" . PHP_EOL;
    }
    echo PHP_EOL;
}

// Test the calendar query
$month = Carbon::create($today->year, $today->month, 1)->startOfMonth();
echo "Calendar query for current month:" . PHP_EOL;
echo "  Start: " . $month->copy()->startOfMonth()->format('Y-m-d H:i:s') . PHP_EOL;
echo "  End: " . $month->copy()->endOfMonth()->format('Y-m-d H:i:s') . PHP_EOL;

$monthOccs = CourseOccurrence::whereBetween('date', [
    $month->copy()->startOfMonth(),
    $month->copy()->endOfMonth()
])->get();

echo "  Found: " . $monthOccs->count() . " occurrences" . PHP_EOL . PHP_EOL;

if ($monthOccs->count() > 0) {
    echo "Dates found in current month:" . PHP_EOL;
    foreach ($monthOccs as $occ) {
        echo "  - " . $occ->date->format('Y-m-d') . " at " . $occ->start_time . PHP_EOL;
    }
} else {
    echo "NO OCCURRENCES FOUND IN CURRENT MONTH!" . PHP_EOL;
    echo "This is why the calendar is empty." . PHP_EOL;
}
