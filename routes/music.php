<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\TrackController;
use Illuminate\Support\Facades\Route;

Route::controller(MusicController::class)->group(function () {
    Route::get('/albums', 'album');
    Route::get('/albums/detail/{slug}', 'detail');
    Route::get('/artistes', 'artiste');
    Route::get('/favories', 'favorie')->middleware('auth');
});

<<<<<<< HEAD
Route::get('/tracks/create', [TrackController::class, 'create'])->name('tracks.create');
Route::post('/tracks/store', [TrackController::class, 'store'])->name('tracks.store');
=======
Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->middleware('auth');

Route::get('/tracks/create', [TrackController::class, 'create'])->name('tracks.create')->middleware('auth');
Route::post('/tracks/store', [TrackController::class, 'store'])->name('tracks.store')->middleware('auth');
>>>>>>> dc3e1bc (favorite music tao am develop)
