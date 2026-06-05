<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ArtistController extends Controller
{
    //
    // Afficher le formulaire
    public function create()
    {
        return Inertia::render('music/artiste/Create');
    }

    // Enregistrer l'artiste
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'surname' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image',
        ]);
        $user = Auth::user();

        // Upload image
        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')
                        ->store('artists', 'public');
        }

        // Empêche double création d'artist
        if ($user->artist) {
            return back();
        }
        // Upload user image
        $user->update([
            'pdp' => $image
        ]);

        // Create artist

        Artist::create([
            'user_id' => $user->id,
            'surname' => $request->surname,
            'description' => $request->description,
        ]);

        return redirect()->route('artists.create')->with('success', 'Artiste créé !');
    }
}
