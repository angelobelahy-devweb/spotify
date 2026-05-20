<?php

<<<<<<< HEAD
use App\Http\Controllers\angelo\AccueilController;
use App\Http\Controllers\ProfileUserController;
=======
>>>>>>> 09b65892d1ddd7941915996c670e9b0df245adca
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Accueil', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

<<<<<<< HEAD
Route::get('/', [AccueilController::class, 'accueil']);
Route::get('/settings/ProfileUser', [ProfileUserController::class, 'index']);

require __DIR__.'/settings.php';

=======


require __DIR__.'/settings.php';
require __DIR__ . '/music.php';
>>>>>>> 09b65892d1ddd7941915996c670e9b0df245adca
