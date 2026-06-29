<?php

use App\Http\Controllers\AccueilController;
use App\Http\Controllers\PremiumController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AccueilController::class, 'index'])->name('home');
Route::get('/explore-premium', [PremiumController::class, 'index'])->name('explore.premium');
Route::get('/comment/{slug}', [AccueilController::class, 'comment'])->name('comment.list');
// Route::middleware('auth')->group(function () {
//     Route::post('/tracks/comments/{slug}', [AccueilController::class, 'store']);
// });
