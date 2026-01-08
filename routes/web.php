<?php

use App\Livewire\Pages\Calendar;
use App\Livewire\Pages\Dashboard;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', Dashboard::class)
        ->name('home');

    Route::get('calendar', Calendar::class)
        ->name('calendar');
});

require __DIR__.'/auth.php';
