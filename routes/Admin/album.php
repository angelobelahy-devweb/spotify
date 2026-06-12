<?php

use App\Http\Controllers\Admin\AlbumController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->controller(AlbumController::class)
    ->group(function () {
        Route::get('/albums', 'index');
        //Route::get('/albums/{id}/update', 'show');
        //Route::put('/albums/{id}', 'update');
        //Route::delete('/albums/{id}', 'delete');
});
