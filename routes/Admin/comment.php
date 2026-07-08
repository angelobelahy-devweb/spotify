<?php

use App\Http\Controllers\Admin\CommentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','admin'])
    ->prefix('admin')
    ->name('admin.')
    ->controller(CommentController::class)
    ->group(function () {
        Route::get('/comments', 'index');
        //Route::get('/albums/{id}/update', 'show');
        //Route::put('/albums/{id}', 'update');
        //Route::delete('/albums/{id}', 'delete');
});
