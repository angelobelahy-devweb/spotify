<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

// Route::inertia('/', 'Accueil', [
//     'canRegister' => Features::enabled(Features::registration()),
// ])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});


<<<<<<< HEAD
require __DIR__ . '/accueil.php';
require __DIR__ . '/settings.php';
=======


require __DIR__.'/accueil.php';
require __DIR__.'/settings.php';
>>>>>>> ed03808 (initialize AdminLayout)
require __DIR__ . '/music.php';
require __DIR__ . '/artists.php';
require __DIR__ . '/album.php';
require __DIR__ . '/Admin/dashboard.php';
<<<<<<< HEAD
require __DIR__ . '/Admin/artist.php';
require __DIR__ . '/Admin/album.php';
require __DIR__ . '/Admin/track.php';
require __DIR__ . '/Admin/genre.php';
require __DIR__ . '/Admin/subscription.php';
require __DIR__ . '/Admin/comment.php';
require __DIR__ . '/Admin/favorite.php';
=======
>>>>>>> ed03808 (initialize AdminLayout)
