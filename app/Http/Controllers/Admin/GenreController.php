<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genre;
use Inertia\Inertia;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/genre/GenreLists', [
            'genres' => Genre::latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/genre/GenreCreate');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => ['required','min:2', 'max:20', 'unique:genres,name']
        ]);

        Genre::create([
            "name" => $validated['name'],
            "slug" => Str::slug($validated['name'])
        ]);

        return redirect('/admin/genres')->with('success', 'Genre created successfully!');
    }

    public function show(string $id)
    {
        return Inertia::render('admin/genre/GenreUpdate', [
            'genre' => Genre::findOrFail($id)
        ]);
    }

    public function update(Request $request, string $id)
    {
        $genre = Genre::findOrFail($id);

        $validated = $request->validate([
            "name" => ['required', 'min:2', 'max:20', 'unique:genres,name,' . $genre->id]
        ]);

        $genre->update([
            "name" => $validated['name'],
            "slug" => Str::slug($validated['name'])
        ]);

        return redirect('/admin/genres')->with('success', 'Genre updated successfully!');
    }

    public function delete(string $id)
    {
        $genre = Genre::findOrFail($id);
        $genre->delete();

        return redirect('/admin/genres')->with('success', 'Genre deleted successfully!');
    }
}
