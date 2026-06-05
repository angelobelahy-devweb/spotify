<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Album;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MusicController extends Controller
{
    //
    public function album() {
        $albums = Album::with('artist')->latest()->get();
        return Inertia::render("music/album/AlbumList", [
            'albums' => $albums,
        ]);
    }

    public function detail(string $slug) {
        $album = Album::where('slug', $slug)->with(['artist', 'tracks'])->first();
        // $album = Album::with(['artist', 'tracks'])->findOrFail($slug);
        return Inertia::render("music/album/AlbumDetails", [
            'album' => $album
        ]);
    }

    public function artiste() {
        return Inertia::render("music/artiste/ArtisteList", [
            'artists' => Artist::with('user')->latest()->get()
        ]);
    }
    public function favorie() {
        return Inertia::render("music/Favorie");
    }
}
