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
    // Afficher le formulaire avec la liste des artistes
    public function create()
    {
        // On récupère les artistes pour les envoyer au formulaire Vue
        // $artists = Artist::orderBy('surname', 'asc')->get();

        // return Inertia::render('Albums/Create', [
        //     'artists' => $artists
        // ]);
        $artists = Artist::select('id', 'surname')->get();
        return Inertia::render('music/album/Create', [
            'artists' => $artists
        ]);
    }

    // Enregistrer l'album
    public function store(Request $request)
    {

        // Validation des données reçues du formulaire
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'release_year' => 'required|date',
            'image'        => 'required|image|mimes:jpeg,jpg,png,webp|max:6144',
            'is_free'      => 'required|boolean',
            'price'        => 'nullable|required_if:is_free,false|numeric|min:0',
        ]);

        // Génération automatique du slug unique à partir du titre
        $validated['slug'] = Str::slug($request->title) . '-' . uniqid();

        // Gestion du téléversement de l'image
        if ($request->hasFile('image')) {
            // Sauvegarde dans storage/app/public/albums
            $path = $request->file('image')->store('albums', 'public');
            $validated['image'] = $path;
        }

        // Conversion de la valeur du checkbox en booléen
        $validated['is_free'] = $request->boolean('is_free');
        $artist = Artist::where('user_id', Auth::id())->first();

        if (!$artist) {
            return redirect()->back()->withError(["error" => "Vous devez avoir un profil artiste pour créer un album."]);
        }
        $validated['artist_id'] = $artist->id;

        // Si l'album est gratuit, le prix est mis à null
        if ($validated['is_free']) {
            $validated['price'] = null;
        }

        // Création de l'album dans la base de données
        Album::create($validated);

        // Redirection vers la liste des albums avec un message de succès

        return redirect()->route('albums.create')->with('success', 'Album créé !');
    }
}
