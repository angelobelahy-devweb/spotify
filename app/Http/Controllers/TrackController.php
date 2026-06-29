<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\Album;
use App\Models\Genre;
use App\Models\Artist;
use App\Models\GenreTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TrackController extends Controller
{
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
        // $genres = [
        //     'Pop', 'Rock', 'Hip-Hop / Rap', 'R&B / Soul', 'Electronic / EDM',
        //     'Dance / Club', 'Reggae', 'Reggaeton / Urban', 'Afrobeats', 'Amapiano',
        //     'Jazz', 'Blues', 'Classical', 'Country', 'Folk / Acoustic',
        //     'Metal', 'Punk', 'Alternative', 'Indie', 'Gospel / Christian',
        //     'Funk / Disco', 'Latin', 'Salsa', 'Bachata', 'K-Pop',
        //     'J-Pop', 'Trap', 'Drill', 'Boom Bap', 'Lo-Fi',
        //     'House', 'Techno', 'Trance', 'Dubstep', 'Drum & Bass',
        //     'Ambient', 'Synthwave', 'Ska', 'Gothic', 'Grime',
        //     'Dancehall', 'Zouk', 'Kizomba', 'Sega / Maloya', 'Coupe-Decale',
        //     'Makossa', 'Highlife', 'Bossa Nova', 'Flamenco', 'Cinematic'
        // ];

        // foreach ($genres as $genreName) {
        //     Genre::updateOrCreate(
        //         ['slug' => Str::slug($genreName)],
        //         ['name' => $genreName]
        //     );
        // }
        $albums = Album::select('id', 'title')->get();
        $genres = Genre::select('id', 'name')->get();
        return Inertia::render('music/tracks/Create', [
            'albums' => $albums,
            'genres' => $genres,
        ]);
    }

    public function store(Request $request)
{
    // 1. Validation des champs textuels
    $request->validate([
        'genre_id' => 'required|exists:genres,id',
        'album_id' => 'required|exists:albums,id',
        'title'    => 'required|string|max:255',
        'duration' => 'nullable|integer',
        'is_free'  => 'required|boolean',
    ]);

    // 2. Vérification manuelle du fichier
    if (!$request->hasFile('audio_file') || !$request->file('audio_file')->isValid()) {
        return redirect()->back()->withErrors([
            'audio_file' => 'Le fichier est manquant ou invalide.'
        ]);
    }

    // 3. Préparation des données pour la base de données
    $data = [
        'album_id' => $request->album_id,
        'title'    => $request->title,
        'duration' => $request->input('duration') ?? 0,
        'is_free'  => $request->boolean('is_free'),
        'slug'     => Str::slug($request->title) . '-' . uniqid(),
    ];


    // 4. RÉSOLUTION DU PROBLÈME .BIN : On force la conservation de l'extension d'origine
    $file = $request->file('audio_file');

    // On génère un nom unique tout en gardant la bonne extension (ex: 65f3a2b1c4d5e.mp3)
    $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

    // On utilise storeAs au lieu de store pour imposer notre nom de fichier
    $path = $file->storeAs('tracks', $fileName, 'public');

    $data['file_path'] = $path;

    // 5. Création en base de données
    $track = Track::create($data);
    // ID du track
    $trackId = $track->id;
    $data['genre_id'] = (int) $request->genre_id;

    GenreTrack::create([
        "genre_id" => $data['genre_id'],
        "track_id" => $trackId
    ]);

    return redirect()->back()->with('success', 'La musique a été ajoutée avec succès !');
}



//     public function store(Request $request)
// {
//     // 1. Validation de tous les champs SANS le fichier pour éviter le blocage automatique
//     $request->validate([
//         'album_id' => 'required|exists:albums,id',
//         'title'    => 'required|string|max:255',
//         'duration' => 'nullable|integer',
//         'is_free'  => 'required|boolean',
//     ]);

//     // 2. Vérification manuelle du fichier pour renvoyer une erreur personnalisée si Laragon bloque
//     if (!$request->hasFile('audio_file') || !$request->file('audio_file')->isValid()) {
//         return redirect()->back()->withErrors([
//             'audio_file' => 'Le fichier est manquant ou dépasse les capacités actuelles du serveur Laragon.'
//         ]);
//     }

//     // 3. Préparation des données si le fichier est valide
//     $data = [
//         'album_id' => $request->album_id,
//         'title'    => $request->title,
//         'duration' => $request->input('duration') ?? 0,
//         'is_free'  => $request->boolean('is_free'),
//         'slug'     => \Illuminate\Support\Str::slug($request->title) . '-' . uniqid(),
//     ];

//     // 4. Stockage du fichier
//     $path = $request->file('audio_file')->store('tracks', 'public');
//     $data['file_path'] = $path;

//     // 5. Création en base de données
//     \App\Models\Track::create($data);

//     return redirect()->back()->with('success', 'La musique a été ajoutée avec succès !');
// }



}
