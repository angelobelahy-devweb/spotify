<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ArtistController extends Controller
{
    private function checkActiveSubscription($user)
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

    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        $isSubscriptionActive = $this->checkActiveSubscription($user);

        $artists = Artist::with('user')
            ->where('status', 'approved')
            ->when($search, fn($q) => $q->where('surname', 'like', "%{$search}%"))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('music/artiste/ArtisteList', [
            'artists'              => $artists,
            'isArtist'             => $user?->artist !== null,
            'isSubscriptionActive' => $isSubscriptionActive,
            'filters'              => ['search' => $search],
        ]);
    }

    public function create()
    {
        $user = Auth::user();

        if (!$this->checkActiveSubscription($user)) {
            return redirect()->route('subscription.index')
                ->with('error', 'Votre abonnement a expiré. Veuillez le renouveler.');
        }

        if ($user->artist) {
            if ($user->artist->status === 'pending') {
                return redirect()->route('subscription.pending');
            }

            if ($user->artist->status === 'rejected') {
                return redirect()->route('subscription.rejected');
            }

            if ($user->artist->status === 'approved') {
                return redirect('/artistes')
                    ->with('info', 'Vous avez déjà un profil artiste actif.');
            }
        }

        return Inertia::render('music/artiste/Create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$this->checkActiveSubscription($user)) {
            return redirect()->route('subscription.index')
                ->with('error', 'Action non autorisée. Veuillez souscrire à un forfait.');
        }

        $rules = [
            'surname'     => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => $user->artist ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $request->validate($rules);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('artists', 'public');
            $user->update(['pdp' => $imagePath]);
        }

        if ($user->artist) {
            $user->artist->update([
                'surname'     => $request->surname,
                'description' => $request->description,
                'status'      => 'pending',
            ]);
        } else {
            Artist::create([
                'user_id'     => $user->id,
                'surname'     => $request->surname,
                'description' => $request->description,
                'status'      => 'pending',
            ]);
        }

        return redirect()->route('subscription.pending')
            ->with('success', 'Profil soumis ! En attente de validation administrative.');
    }

    public function showProfile($slug)
    {
        // 1. Récupérer l'artiste via le slug de son utilisateur avec toutes les relations nécessaires
        $artist = Artist::whereHas('user', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->with([
            'user',
            'albums' => function($query) {
                // Charge les compteurs de relations pour optimiser les performances
                $query->withCount(['tracks']);
            },
            'tracks' => function($query) {
                // Récupère l'album associé et les commentaires avec l'auteur du commentaire
                $query->with(['album', 'comments.user']);
            }
        ])->firstOrFail();

        // 2. Déterminer le forfait (Tier d'abonnement)
        $artistUser = $artist->user;
        $subscription = $artistUser?->subscriptions()
            ->where('stripe_status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->latest()
            ->first();

        $artist->subscription_tier = $subscription ? $subscription->type : ($artistUser?->pm_type ?? 'free');

        // 3. Calculer les statistiques globales réelles basées sur la base de données
        $stats = [
            'albums_count' => $artist->albums->count(),
            'tracks_count' => $artist->tracks->count(),
        ];

        // 4. Envoyer le tout à la vue Inertia
        return Inertia::render('music/artiste/ArtistProfile', [
            'artist' => $artist,
            'albums' => $artist->albums,
            'tracks' => $artist->tracks,
            'stats'  => $stats
        ]);
    }
}
