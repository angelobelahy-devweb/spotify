<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ArtistController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        $subscription = $user?->subscriptions()
            ->where('stripe_status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>', now());
            })
            ->latest()
            ->first();

        $artists = Artist::with('user')
            ->where('status', 'approved')
            ->when($search, fn($q) => $q->where('surname', 'like', "%{$search}%"))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('music/artiste/ArtisteList', [
            'artists'              => $artists,
            'isArtist'             => $user?->artist !== null,
            'isSubscriptionActive' => !is_null($subscription),
            'filters'              => ['search' => $search],
        ]);
    }

    public function create()
    {
        $user = Auth::user();

        // 1. Pas encore de profil artiste => souscrire d'abord
        if (!$user->artist) {
            return redirect()->route('subscription.index');
        }

        // 2. En attente => page d'attente
        if ($user->artist->status === 'pending') {
            return redirect()->route('subscription.pending');
        }

        // 3. Approuvé => formulaire artiste
        if ($user->artist->status === 'approved') {
            return Inertia::render('music/artiste/Create');
        }

        // 4. Rejeté => retour au choix d'abonnement
        if ($user->artist->status === 'rejected') {
            return redirect()->route('subscription.rejected');
        }

        // Filet de sécurité
        return redirect()->route('subscription.index');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $hasSubscription = $user->subscriptions()->where('stripe_status', 'active')->exists();

        if (!$user || !$hasSubscription) {
            return redirect()->route('subscription.index')
                ->with('error', 'Action non autorisée. Veuillez souscrire à un forfait.');
        }

        $request->validate([
            'surname'     => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('artists', 'public');
            $user->update(['pdp' => $imagePath]);
        }

        if ($user->artist) {
            $user->artist->update([
                'surname'     => $request->surname,
                'description' => $request->description,
            ]);
        } else {
            Artist::create([
                'user_id'     => $user->id,
                'surname'     => $request->surname,
                'description' => $request->description,
                'status'      => 'approved',
            ]);
        }

        return redirect('/artistes')
            ->with('success', 'Votre profil artiste a été configuré avec succès !');
    }
}
