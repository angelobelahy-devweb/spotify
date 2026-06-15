<?php
use App\Http\Controllers\ArtistController;
use Illuminate\Support\Facades\Route;

Route::get('/artistes/create', [ArtistController::class, 'create'])->name('artists.create');
Route::post('/artistes/store', [ArtistController::class, 'store'])->name('artists.store');
Route::post('/artistes/store', [ArtistController::class, 'store'])->name('artists.store');
