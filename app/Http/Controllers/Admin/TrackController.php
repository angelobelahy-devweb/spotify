<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Track;
use Inertia\Inertia;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 5);
        $search = $request->input('search');
        $query = Track::with(['album.artist'])->withCount('comments');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $tracks = $query->latest()->paginate($per_page)->withQueryString();

        return Inertia::render('admin/track/TrackLists', [
            'tracks' => $tracks,
            'filters' => [
                'search' => $search,
                'per_page' => $per_page,
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // 1. Trouver le morceau ou renvoyer une erreur 404 si introuvable
            $track = Track::findOrFail($id);

            // 2. Supprimer la ligne de la base de données
            $track->delete();

            // 3. Rediriger l'utilisateur vers la page précédente avec un message de succès flash
            return redirect()->back()->with('success', 'Le morceau a été supprimé avec succès !');

        } catch (\Exception $e) {
            // En cas d'erreur imprévue, renvoyer un message d'erreur flash
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression.');
        }
    }
}
