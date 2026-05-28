<?php
namespace App\Http\Controllers;
use App\Models\Album;
use App\Models\Artist;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlbumController extends Controller
{
    // Afficher le formulaire avec la liste des artistes
    public function create()
    {
        $artists = Artist::select('id', 'surname')->get();
        return Inertia::render('music/album/Create', [
            'artists' => $artists
        ]);
    }

    // Enregistrer l'album
    public function store(Request $request)
    {
        $validated = $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'title' => 'required|string|max:255',
            'release_year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        Album::create($validated);

        return redirect()->route('albums.index')->with('success', 'Album créé !');
    }
}
