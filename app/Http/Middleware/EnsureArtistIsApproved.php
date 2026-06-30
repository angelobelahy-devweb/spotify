<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureArtistIsApproved
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // 🔴 ERREUR PRÉCÉDENTE : Bloquait tous ceux qui n'avaient pas de profil artiste.
        // 🟢 CORRECTION : On bloque uniquement si l'utilisateur possède un profil artiste MAIS qu'il n'est pas encore approuvé.
        if ($user && $user->artist && $user->artist->status !== 'approved') {
            return redirect()->route('subscription.index')
                ->with('error', 'Votre compte artiste est en attente de validation administrative.');
        }

        return $next($request);
    }
}