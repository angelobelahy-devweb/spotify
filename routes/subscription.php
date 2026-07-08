<?php

use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    // 1. Affichage du tableau des tarifs (Index)
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');

    // 2. Soumission du formulaire (Bouton s'abonner) -> POST uniquement
    Route::post('/subscription/checkout', [SubscriptionController::class, 'store'])->name('subscription.checkout');

    // 3. Page de succès (Redirection finale après Stripe ou Plan Free) -> GET uniquement
    // ⚠️ ATTENTION : L'URL doit être différente et bien être '/subscription/success'
    Route::get('/subscription/success', [SubscriptionController::class, 'handleSuccess'])->name('subscription.success');

    // 4. Autres pages d'états de compte
    Route::get('/subscription/pending', [SubscriptionController::class, 'pending'])->name('subscription.pending');
    Route::get('/subscription/rejected', [SubscriptionController::class, 'rejected'])->name('subscription.rejected');
});
