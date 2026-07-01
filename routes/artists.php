<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtistController;

Route::get('/artistes', [ArtistController::class, 'index'])->name('artists.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/artistes/create', [ArtistController::class, 'create'])->name('artists.create');
    Route::post('/artistes/store', [ArtistController::class, 'store'])->name('artists.store');
});
