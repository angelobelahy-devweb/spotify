<?php
use App\Http\Controllers\ArtistController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/artistes', [ArtistController::class, 'index'])->name('artists.index');       // ✅ add this
    Route::get('/artistes/create', [ArtistController::class, 'create'])->name('artists.create');
    Route::post('/artistes/store', [ArtistController::class, 'store'])->name('artists.store'); // ✅ remove duplicate
});
