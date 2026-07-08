<?php

use App\Http\Controllers\Admin\ArtistController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/artists', [ArtistController::class, 'index'])->name('artists.index');
        Route::get('/artists/{id}', [ArtistController::class, 'show'])->name('artists.show');
        Route::put('/artists/{id}', [ArtistController::class, 'update'])->name('artists.update');
        Route::delete('/artists/{id}', [ArtistController::class, 'delete'])->name('artists.delete');

});
