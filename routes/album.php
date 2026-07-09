<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/albums/create', [AlbumController::class, 'create'])->name('albums.create')->middleware('auth');
Route::post('/albums/store', [AlbumController::class, 'store'])->name('albums.store')->middleware('auth');

Route::get('/checkout', [CartController::class, 'index'])->name('cart.index');

Route::middleware(['auth'])->group(function () {
    // Current checkout submission route
    Route::post('/purchase', [CartController::class, 'store'])->name('cart.purchase');

    // 🔥 NEW: Stripe success redirect lander route
    Route::get('/purchase/success', [CartController::class, 'handleSuccess'])->name('cart.success');
});
