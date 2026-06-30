<?php

use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/checkout', [SubscriptionController::class, 'store'])->name('subscription.checkout');

    // 🟡 La page de succès redirigera ici si le statut de l'artiste est 'pending'
    Route::get('/subscription/pending', [SubscriptionController::class, 'pending'])->name('subscription.pending');
    Route::get('/subscription/success', [SubscriptionController::class, 'handleSuccess'])->name('subscription.success');
});
