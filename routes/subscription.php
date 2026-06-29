<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::get('/subscription/success', [SubscriptionController::class, 'handleSuccess'])->name('subscription.success');
    Route::post('/subscription/checkout', [SubscriptionController::class, 'store'])->name('subscription.checkout');

    // Vérifie aussi que la route cible est bien dans ce groupe ou un groupe similaire
    Route::get('/artistes/create', [ArtistController::class, 'create'])->name('artists.create');
});
