<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ArtistController extends Controller
{
    /**
     * Helper réutilisable pour vérifier l'abonnement actif de l'utilisateur
     */
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

        // Utilisation du helper pour la cohérence
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

        // 1. Si l'abonnement n'est pas actif -> Redirection paiement
        if (!$this->checkActiveSubscription($user)) {
            return redirect()->route('subscription.index')
                ->with('error', 'Votre abonnement a expiré. Veuillez le renouveler pour accéder à vos fonctionnalités.');
        }

        // 2. Si le profil existe déjà, on gère selon le statut (Pas de réécriture du formulaire !)
        if ($user->artist) {
            if ($user->artist->status === 'pending') {
                return redirect()->route('subscription.pending');
            }

            if ($user->artist->status === 'rejected') {
                return redirect()->route('subscription.rejected');
            }

            if ($user->artist->status === 'approved') {
                return redirect()->route('artists.index')
                    ->with('info', 'Vous avez déjà un profil artiste actif.');
            }
        }

        // 3. Premier abonnement sans profil -> Formulaire initial
        return Inertia::render('music/artiste/Create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Sécurisation stricte de l'abonnement (même logique partout)
        if (!$this->checkActiveSubscription($user)) {
            return redirect()->route('subscription.index')
                ->with('error', 'Action non autorisée. Veuillez souscrire à un forfait.');
        }

        // CORRECTION VALIDATION : L'image est requise UNIQUEMENT si l'artiste n'a pas encore de profil
        $rules = [
            'surname'     => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => $user->artist ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $request->validate($rules);

        // Gestion de l'image (si une nouvelle image est téléversée)
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('artists', 'public');
            $user->update(['pdp' => $imagePath]);
        }

        // Sauvegarde ou Mise à jour
        if ($user->artist) {
            $user->artist->update([
                'surname'     => $request->surname,
                'description' => $request->description,
                // On s'assure que le statut reste approved s'il met à jour
                'status'      => 'approved',
            ]);
        } else {
            Artist::create([
                'user_id'     => $user->id,
                'surname'     => $request->surname,
                'description' => $request->description,
                'status'      => 'approved', // Devient directement approuvé après paiement initial
            ]);
        }

        return redirect('/artistes')
            ->with('success', 'Votre profil artiste a été configuré avec succès !');
    }
}
