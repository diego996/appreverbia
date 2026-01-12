<?php

use App\Livewire\Pages\Calendar;
use App\Livewire\Pages\Dashboard;
use App\Livewire\Pages\Profile;
use Illuminate\Support\Facades\Route;

Route::post('stripe/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', Dashboard::class)
        ->name('home');

    Route::get('calendar', Calendar::class)
        ->name('calendar');

    Route::get('profilo', Profile::class)
        ->name('profile');

    Route::get('acquisti', \App\Livewire\Pages\Pricing::class)
        ->name('pricing');

    Route::get('membership', \App\Livewire\Pages\Membership::class)
        ->name('membership');

    Route::get('supporto', \App\Livewire\Pages\Support::class)
        ->name('support');

    Route::get('checkout/{item}', [\App\Http\Controllers\PaymentController::class, 'checkout'])
        ->name('payment.checkout');
    Route::get('payment/success', [\App\Http\Controllers\PaymentController::class, 'success'])
        ->name('payment.success');
    Route::get('payment/cancel', [\App\Http\Controllers\PaymentController::class, 'cancel'])
        ->name('payment.cancel');
});

require __DIR__ . '/auth.php';
