<?php

use App\Http\Controllers\Admin\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->controller(SubscriptionController::class)
    ->group(function () {
        Route::get('/subscriptions', 'index');
        //Route::get('/subscriptions/{id}/update', 'show');
        //Route::put('/subscriptions/{id}', 'update');
        //Route::delete('/subscriptions/{id}', 'delete');
});
