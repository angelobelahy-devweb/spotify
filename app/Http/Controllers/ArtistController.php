<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
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
        ]);
        dd($validated);

        Artist::create($validated);

        return redirect()->route('artists.index')->with('success', 'Artiste créé !');
    }
}
