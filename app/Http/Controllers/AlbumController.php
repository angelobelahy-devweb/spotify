<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    private function isSubscriptionActive($user)
    {
        if (!$user) return false;

        return $user->subscriptions()
            ->where('stripe_status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>', now());
            })
            ->exists();
    }

    public function create()
    {
        $user = Auth::user();
        $artist = $user?->artist;

        $isSubscriptionActive = $this->isSubscriptionActive($user);

        // Récupération dynamique des autorisations depuis le modèle User
        $canCreateAlbum = $user ? $user->canCreateAlbum() : false;
        $albumCount = $artist ? $artist->albums()->count() : 0;

        // Définition de la limite uniquement pour affichage informatif sur le front-end
        $plan = $user ? $user->getActivePlanName() : 'basic';
        $limits = ['basic' => 1, 'premium' => 10, 'vip' => 'Illimité'];
        $albumLimit = $limits[$plan] ?? 1;

        return Inertia::render('music/album/Create', [
            'isArtist'             => !is_null($artist) && $artist->status === 'approved',
            'isSubscriptionActive' => $isSubscriptionActive,
            'canCreateAlbum'       => $canCreateAlbum,
            'albumCount'           => $albumCount,
            'albumLimit'           => $albumLimit,
            'artists'              => Artist::select('id', 'surname')->get()
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $artist = $user?->artist;

        // Sécurité 1 : Est-ce un artiste approuvé ?
        if (!$artist || $artist->status !== 'approved') {
            return redirect()->back()->withErrors(['error' => 'Vous devez avoir un profil artiste approuvé pour créer un album.']);
        }

        // Sécurité 2 : L'abonnement est-il actif ?
        if (!$this->isSubscriptionActive($user)) {
            return redirect()->back()->withErrors(['error' => 'Votre abonnement a expiré. Veuillez le renouveler.']);
        }

        // 🔥 Sécurité 3 : Utilisation de la méthode dynamique centralisée
        if (!$user->canCreateAlbum()) {
            return redirect()->back()->withErrors([
                'error' => "Action refusée : Vous avez atteint la limite maximale d'albums allouée à votre formule actuelle."
            ]);
        }

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'release_year' => 'required|date',
            'image'        => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
            'is_free'      => 'required|boolean',
            'price'        => 'nullable|required_if:is_free,false|numeric|min:0',
        ]);

        $validated['slug'] = Str::slug($request->title) . '-' . uniqid();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('albums', 'public');
            $validated['image'] = $path;
        }

        $validated['is_free'] = $request->boolean('is_free');
        $validated['artist_id'] = $artist->id;

        if ($validated['is_free']) {
            $validated['price'] = null;
        }

        Album::create($validated);

        return redirect("/albums")->with('success', 'Album créé !');
    }
}