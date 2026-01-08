<?php

use App\Livewire\Pages\Calendar;
use App\Livewire\Pages\Dashboard;
use App\Livewire\Pages\Profile;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', Dashboard::class)
        ->name('home');

    Route::get('calendar', Calendar::class)
        ->name('calendar');

    Route::get('profilo', Profile::class)
        ->name('profile');
});

require __DIR__.'/auth.php';
