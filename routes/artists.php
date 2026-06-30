<?php
use App\Http\Controllers\ArtistController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    // Formulaire de création de profil d'artiste
    Route::get('/artistes/create', [ArtistController::class, 'create'])->name('artists.create');

    // Sauvegarde du profil d'artiste
    Route::post('/artistes/store', [ArtistController::class, 'store'])->name('artists.store');

    // Liste publique des artistes
    Route::get('/artistes', [ArtistController::class, 'index'])->name('artists.index');
});