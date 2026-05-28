<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TrackController extends Controller
{
    public function create()
    {
        $albums = Album::select('id', 'title')->get();
        return Inertia::render('music/tracks/Create', [
            'albums' => $albums
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validation du fichier (max 10 Mo ici)
        $validated = $request->validate([
            'album_id' => 'required|exists:albums,id',
            'title' => 'required|string|max:255',
            'duration' => 'nullable|integer|min:1',
            'audio_file' => 'required|file|mimes:mp3,wav,ogg,m4a|max:10240',
        ]);

        // 2. Sauvegarde du fichier dans le dossier 'storage/app/public/tracks'
        if ($request->hasFile('audio_file')) {
            $path = $request->file('audio_file')->store('tracks', 'public');
            $validated['file_path'] = $path;
        }

        // 3. Création dans la base de données
        Track::create($validated);

        return redirect()->route('tracks.index')->with('success', 'Musique ajoutée avec son fichier !');
    }
}
