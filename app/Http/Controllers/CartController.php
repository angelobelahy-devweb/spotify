<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        // On récupère tous les albums avec leurs artistes pour que la page Checkout puisse filtrer localement
        $albums = Album::with('artist')->get();

        return Inertia::render('music/achat/Checkout', [
            'albums' => $albums
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'album_ids' => 'required|array',
            'album_ids.*' => 'exists:albums,id'
        ]);

        $user = Auth::user();

        // 1. Récupérer les détails des albums envoyés pour obtenir leur prix
        $albums = Album::whereIn('id', $request->album_ids)->get();

        // 2. Enregistrement des achats dans ta table 'purchases'
        foreach ($albums as $album) {
            // Sécurité : si le prix n'est pas numérique (ex: "gratuit" ou null), on met 0
            $price = is_numeric($album->price) ? $album->price : 0;

            DB::table('purchases')->insert([
                'user_id'     => $user->id,
                'album_id'    => $album->id,
                'amount_paid' => $price, // 🔥 Fix : Ajout de la valeur obligatoire manquante
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        return redirect('/albums')->with('success', 'Achat effectué avec succès !');
    }
}
