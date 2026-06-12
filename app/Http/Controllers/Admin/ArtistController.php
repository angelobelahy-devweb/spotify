<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class ArtistController extends Controller
{
    //show list
    public function index()
    {
        return Inertia::render('admin/artiste/ArtistLists', [
            'artists' => Artist::with('user')->latest()->get()
        ]);
    }

    // show form
    public function show($id)
    {
        $artist = Artist::with('user')->findOrFail($id);

        return Inertia::render('admin/artiste/ArtisteUpdate', [
            'artist' => $artist
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'surname' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image', // Changed to nullable so users don't HAVE to re-upload an image every time
        ]);

        $artist = Artist::findOrFail($id);
        $user = $artist->user; // Update the artist's linked user profile, not the currently logged-in admin!

        // Upload image if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('artists', 'public');
            $user->update(['pdp' => $imagePath]);
        }

        // Update artist details
        $artist->update([
            'surname' => $request->surname,
            'description' => $request->description,
        ]);

        return redirect('/admin/artists')->with('success', 'Modification réussie !');
    }

    public function delete($id)
    {
        try {
            $artist = Artist::findOrFail($id);
            $artist->delete();

            return redirect()->back()->with('success', 'L\'artiste a été supprimé avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Impossible de supprimer cet artiste.');
        }
    }
}
