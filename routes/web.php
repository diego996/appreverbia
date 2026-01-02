<?php

use App\Livewire\Pages\Calendar;
use App\Livewire\Pages\Dashboard;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('calendar', Calendar::class)
    ->middleware(['auth', 'verified'])
    ->name('calendar');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
