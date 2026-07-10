<?php

use App\Http\Controllers\Admin\ArtistController; // Ton contrôleur Admin
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin']) // Ajoute ton middleware admin ici si tu en as un (ex: 'admin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Routes de gestion des artistes pour le Panel Admin
        Route::get('/artists', [ArtistController::class, 'index'])->name('artists.index');
        Route::get('/artists/{id}', [ArtistController::class, 'show'])->name('artists.show');
        Route::put('/artists/{id}', [ArtistController::class, 'update'])->name('artists.update');
        Route::delete('/artists/{id}', [ArtistController::class, 'delete'])->name('artists.delete');

});
