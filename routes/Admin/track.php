<?php

use App\Http\Controllers\Admin\TrackController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->controller(TrackController::class)
    ->group(function () {
        Route::get('/tracks', 'index');
        //Route::get('/albums/{id}/update', 'show');
        //Route::put('/albums/{id}', 'update');
        Route::delete('/tracks/{id}', 'destroy')->name('tracks.destroy');
});
