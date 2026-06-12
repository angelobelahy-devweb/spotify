<?php

use App\Http\Controllers\AlbumController;
use Illuminate\Support\Facades\Route;

<<<<<<< HEAD
Route::get('/albums/create', [AlbumController::class, 'create'])->name('albums.create');
Route::post('/albums/store', [AlbumController::class, 'store'])->name('albums.store');
=======
Route::get('/albums/create', [AlbumController::class, 'create'])->name('albums.create')->middleware('auth');
Route::post('/albums/store', [AlbumController::class, 'store'])->name('albums.store')->middleware('auth');
>>>>>>> develop
