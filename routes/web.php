<?php

use App\Http\Controllers\angelo\AccueilController;
use App\Http\Controllers\ProfileUserController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// Route::inertia('/', 'Welcome', [
//     'canRegister' => Features::enabled(Features::registration()),
// ])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::get('/', [AccueilController::class, 'accueil']);
Route::get('/settings/ProfileUser', [ProfileUserController::class, 'index']);

require __DIR__.'/settings.php';

