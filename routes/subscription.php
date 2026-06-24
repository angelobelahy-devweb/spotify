<?php

use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::get('/subscription/payment', [SubscriptionController::class, 'payment'])->name('subscription.payment');
    Route::post('/subscription/checkout', [SubscriptionController::class, 'store'])->name('subscription.checkout');
});
