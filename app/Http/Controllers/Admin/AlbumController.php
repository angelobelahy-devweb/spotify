<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Album;
use Inertia\Inertia;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
    {
        $per_page = $request->input('per_page', 5);
        $search = $request->input('search');
        $query = Album::with('artist');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $albums = $query->latest()->paginate($per_page)->withQueryString();

        return Inertia::render('admin/album/AlbumLists', [
            'albums' => $albums,
            'filters' => [
                'search' => $search,
                'per_page' => $per_page
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
            $album = Album::findOrFail($id);
            $album->delete();

            return redirect()->back()->with('success', 'L\'album a été supprimé avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Impossible de supprimer cet album.');
        }
    }
}
