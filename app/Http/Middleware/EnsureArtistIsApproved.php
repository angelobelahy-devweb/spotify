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

        // S'il n'a pas de profil artiste, on le laisse aller sur le formulaire d'inscription
        if (!$user || !$user->artist) {
            if ($request->routeIs('artists.create') || $request->routeIs('artists.store')) {
                return $next($request);
            }
            return redirect()->route('artists.create');
        }

        // Si l'artiste est en attente, on le redirige vers la page d'attente
        if ($user->artist->status === 'pending') {
            if ($request->routeIs('subscription.pending')) {
                return $next($request);
            }
            return redirect()->route('subscription.pending');
        }

        // Si le profil a été rejeté
        if ($user->artist->status === 'rejected') {
            return redirect()->route('subscription.index')->with('error', 'Votre demande a été rejetée.');
        }

        return $next($request);
    }
}
