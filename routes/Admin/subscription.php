<?php

use App\Http\Controllers\Admin\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->controller(SubscriptionController::class) // 👈 Définit le contrôleur pour tout le groupe
    ->group(function () {

        // Liste des abonnements et artistes en attente
        Route::get('/subscriptions', 'index')->name('subscriptions.index');

        // 🟢 CORRIGÉ : Plus besoin de réécrire [SubscriptionController::class, ...]
        Route::post('/subscriptions/artists/{id}/approve', 'approveArtist')->name('subscriptions.artists.approve');
        Route::post('/subscriptions/artists/{id}/reject', 'rejectArtist')->name('subscriptions.artists.reject');

        // Routes commentées nettoyées au cas où tu en aurais besoin plus tard :
        // Route::get('/subscriptions/{id}/update', 'show')->name('subscriptions.show');
        // Route::put('/subscriptions/{id}', 'update')->name('subscriptions.update');
        // Route::delete('/subscriptions/{id}', 'destroy')->name('subscriptions.destroy');
});
