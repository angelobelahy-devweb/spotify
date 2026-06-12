<?php

use App\Http\Controllers\Admin\FavoriteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->controller(FavoriteController::class)
    ->group(function () {
        Route::get('/favorites', 'index');
        //Route::get('/albums/{id}/update', 'show');
        //Route::put('/albums/{id}', 'update');
        //Route::delete('/albums/{id}', 'delete');
});
