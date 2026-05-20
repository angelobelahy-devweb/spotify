<?php

use App\Http\Controllers\MusicController;
use Illuminate\Support\Facades\Route;

Route::controller(MusicController::class)->group(function () {
    Route::get('/albums', 'album');
    Route::get('/albums/details', 'detail');
    Route::get('/artistes', 'artiste');
    Route::get('/favories', 'favorie');
});