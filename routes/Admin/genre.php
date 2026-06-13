<?php

use App\Http\Controllers\Admin\GenreController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->controller(GenreController::class)
    ->group(function () {
        Route::get('/genres', 'index');
        Route::get('/genres/create', 'create');
        Route::post('/genres', 'store');
        Route::get('/genres/{id}/update', 'show');
        Route::put('/genres/{id}', 'update');
        Route::delete('/genres/{id}', 'delete');
});
