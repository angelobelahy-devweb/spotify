<?php

use App\Http\Controllers\Admin\ArtistController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->controller(ArtistController::class)
    ->group(function () {
        Route::get('/artists', 'index');
        Route::get('/artists/{id}/update', 'show');
        Route::put('/artists/{id}', 'update');
        Route::delete('/artists/{id}', 'delete');
});
