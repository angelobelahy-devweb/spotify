<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Role;

class ArtistController extends Controller
{
    // Afficher le formulaire
    public function create()
    {
        $user = Auth::user();

        // SÉCURITÉ : Si l'utilisateur n'est pas connecté ou est en formule "basic" / gratuite
        if (!$user || !$user->pm_type || $user->pm_type === 'basic') {
            return redirect()->route('subscription.index')
                ->with('error', 'Vous devez souscrire à une offre Premium ou VIP pour créer un profil artiste.');
        }

        return Inertia::render('music/artiste/Create');
    }

    // Enregistrer l'artiste
    public function store(Request $request)
    {
        $user = Auth::user();

        // SÉCURITÉ DOUBLE CHECK : Bloquer aussi la requête POST si l'abonnement n'est pas bon
        if (!$user || !$user->pm_type || $user->pm_type === 'basic') {
            return redirect()->route('subscription.index')
                ->with('error', 'Action non autorisée. Veuillez mettre à niveau votre forfait.');
        }

        $artistRole = Role::whereRaw('LOWER(name) = ?', [strtolower(Role::ARTIST)])->first();

        $validated = $request->validate([
            'surname' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image',
        ]);

        // Upload image
        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')
                ->store('artists', 'public');
        }

        // Empêche double création d'artiste
        if ($user->artist) {
            return back();
        }

        // Upload user image
        $user->update([
            'pdp' => $image
        ]);

        if ($artistRole) {
            $user->update([
                'pdp' => $image,
                'role_id' => $artistRole->id
            ]);
        }

        // Create artist
        Artist::create([
            'user_id' => $user->id,
            'surname' => $request->surname,
            'description' => $request->description,
        ]);

        // Astuce : Au lieu de rediriger vers 'artists.create', redirigez plutôt vers la liste
        // globale pour voir le résultat ou gardez votre route actuelle si besoin.
        return redirect()->route('artists.create')->with('success', 'Artiste créé !');
    }
}
