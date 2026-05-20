<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class MusicController extends Controller
{
    //
    public function album() {
        return Inertia::render("music/album/AlbumList");
    }
    public function detail() {
        return Inertia::render("music/album/AlbumDetails");
    }
    public function artiste() {
        return Inertia::render("music/artiste/ArtisteList");
    }
    public function favorie() {
        return Inertia::render("music/Favorie");
    }
}
