<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::controller(MusicController::class)->group(function () {
    Route::get('/albums', 'album');
    Route::get('/albums/detail/{slug}', 'detail');
    // ❌ SUPPRIMÉ : Route::get('/artistes', 'artiste'); <- Faisait doublon et écrasait ArtistController !
    Route::get('/favories', 'favorie')->middleware('auth');
});

Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->middleware('auth');

Route::get('/tracks', [TrackController::class, 'index'])->name('tracks.index');
Route::get('/tracks/create', [TrackController::class, 'create'])->name('tracks.create')->middleware('auth');
Route::post('/tracks/store', [TrackController::class, 'store'])->name('tracks.store')->middleware('auth');
Route::post('/tracks/{track:slug}/comments', [CommentController::class, 'store'])->middleware('auth');
