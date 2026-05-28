<?php

use App\Http\Controllers\MusicController;
use App\Http\Controllers\TrackController;
use Illuminate\Support\Facades\Route;

Route::controller(MusicController::class)->group(function () {
    Route::get('/albums', 'album');
    Route::get('/albums/details', 'detail');
    Route::get('/artistes', 'artiste');
    Route::get('/favories', 'favorie');
});



Route::get('/tracks/create', [TrackController::class, 'create'])->name('tracks.create');
Route::post('/tracks', [TrackController::class, 'store'])->name('tracks.store');
