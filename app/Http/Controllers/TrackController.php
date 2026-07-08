<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\Album;
use App\Models\Genre;
use App\Models\Artist;
use App\Models\GenreTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TrackController extends Controller
{
    /**
     * Helper réutilisable pour valider le statut de l'abonnement Stripe
     */
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

    public function index()
    {
        $tracks = Track::with(['album.artist.user', 'genres'])->get();
        $genres = Genre::select('id', 'name')->get();
        
        return Inertia::render('music/tracks/TrackList', [
            'tracks' => $tracks,
            'genres' => $genres,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        $artist = $user?->artist;

        $isSubscriptionActive = $this->isSubscriptionActive($user);

        // Récupération uniquement les albums créés par cet artiste spécifique
        $albums = $artist ? Album::where('artist_id', $artist->id)->select('id', 'title')->get() : [];
        $genres = Genre::select('id', 'name')->get();

        // 📊 Données statistiques et de limites pour l'interface Vue.js
        $trackCount = $artist ? Track::whereIn('album_id', $albums->pluck('id'))->count() : 0;
        $plan = $user ? $user->getActivePlanName() : 'basic';
        $limits = ['basic' => 5, 'premium' => 50, 'vip' => 'Illimité'];
        $trackLimit = $limits[$plan] ?? 5;

        return Inertia::render('music/tracks/Create', [
            'albums'               => $albums,
            'genres'               => $genres,
            'isArtist'             => !is_null($artist) && $artist->status === 'approved',
            'isSubscriptionActive' => $isSubscriptionActive,
            'canCreateTrack'       => $user ? $user->canCreateTrack() : false,
            'trackCount'           => $trackCount,
            'trackLimit'           => $trackLimit,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $artist = $user?->artist;

        // Sécurité 1 : Est-ce un artiste approuvé ?
        if (!$artist || $artist->status !== 'approved') {
            return redirect()->back()->withErrors(['error' => 'Vous devez disposer d\'un compte artiste approuvé pour ajouter des morceaux.']);
        }

        // Sécurité 2 : L'abonnement est-il actif ?
        if (!$this->isSubscriptionActive($user)) {
            return redirect()->back()->withErrors(['error' => 'Votre abonnement a expiré. Veuillez le renouveler.']);
        }

        // 🛡️ Sécurité 3 : Contrôle de quota dynamique (Basic: 5, Premium: 50, VIP: Illimité)
        if (!$user->canCreateTrack()) {
            return redirect()->back()->withErrors([
                'error' => 'Action refusée : Limite maximale de morceaux atteinte pour votre forfait actuel.'
            ]);
        }

        // 1. Validation des métadonnées
        $request->validate([
            'genre_id' => 'required|exists:genres,id',
            'album_id' => 'required|exists:albums,id',
            'title'    => 'required|string|max:255',
            'duration' => 'nullable|integer',
            'is_free'  => 'required|boolean',
        ]);

        // 🛡️ Sécurité 4 : S'assurer que l'album sélectionné appartient bien à cet artiste
        $albumOwnership = Album::where('id', $request->album_id)->where('artist_id', $artist->id)->exists();
        if (!$albumOwnership) {
            return redirect()->back()->withErrors(['album_id' => 'L\'album sélectionné ne vous appartient pas.']);
        }

        // 2. Vérification manuelle du fichier audio
        if (!$request->hasFile('audio_file') || !$request->file('audio_file')->isValid()) {
            return redirect()->back()->withErrors([
                'audio_file' => 'Le fichier est manquant ou invalide.'
            ]);
        }

        // 3. Préparation des données
        $data = [
            'album_id' => $request->album_id,
            'title'    => $request->title,
            'duration' => $request->input('duration') ?? 0,
            'is_free'  => $request->boolean('is_free'),
            'slug'     => Str::slug($request->title) . '-' . uniqid(),
        ];

        // 4. Stockage du fichier (Maintien de l'extension originale pour contourner le bug .bin)
        $file = $request->file('audio_file');
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('tracks', $fileName, 'public');

        $data['file_path'] = $path;

        // 5. Création en base de données
        $track = Track::create($data);

        // 6. Association du genre via la table pivot
        GenreTrack::create([
            "genre_id" => (int) $request->genre_id,
            "track_id" => $track->id
        ]);

        return redirect()->back()->with('success', 'La musique a été ajoutée avec succès !');
    }
}