<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Genre;



class AccueilController extends Controller
{
    //
    public function index()
    {
        $genres = Genre::select('id', 'name')->get();
        return Inertia::render('Accueil', [
            'genres' => $genres,
        ]);
    }
}
