<?php

use App\Http\Controllers\Admin\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->controller(SubscriptionController::class)
    ->group(function () {
        Route::get('/subscriptions', 'index')->name('subscriptions.index');
        Route::post('/subscriptions/artists/{id}/approve', 'approveArtist')->name('subscriptions.artists.approve');
        Route::post('/subscriptions/artists/{id}/reject', 'rejectArtist')->name('subscriptions.artists.reject');
        // Route::get('/subscriptions/{id}/update', 'show')->name('subscriptions.show');
        // Route::put('/subscriptions/{id}', 'update')->name('subscriptions.update');
        // Route::delete('/subscriptions/{id}', 'destroy')->name('subscriptions.destroy');
});
