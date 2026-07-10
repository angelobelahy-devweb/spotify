<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Track;

class AccueilController extends Controller
{
    //
    public function index()
    {
            // Role::create(
            //     ['name' => 'admin']
            // );
        $tracks = Track::with(['album.artist.user', 'genres'])
            ->withCount('favorites')
            ->orderByDesc('created_at')
            ->get();

        $genres = Genre::select('id', 'name')->get();

        $stats = [
            'tracks' => Track::count(),
            'artists' => Artist::count(),
            'albums' => Album::count(),
            'genres' => Genre::count(),
        ];

        return Inertia::render('Accueil', [
            'genres' => $genres,
            'tracks' => $tracks,
            'stats' => $stats,
        ]);
    }
    public function comment(string $slug)
    {
        $track = Track::with(['album.artist.user', 'genres', 'comments.user'])
            ->where('slug', $slug)
            ->firstOrFail();
    // dd($track);
        return Inertia::render('music/comment/CommentList', [
            'track' => $track,
        ]);
    }

    // public function store(Request $request, string $slug)
    // {
    //     dd(
    //     $slug,
    //     $request->all(),
    //     auth()->id()
    // );
    //     $request->validate([
    //         'content' => ['required', 'string', 'max:1000']
    //     ]);

    //     $track = Track::where('slug', $slug)->firstOrFail();

    //     $comment = Comment::create([
    //         'user_id' => auth()->id(),
    //         'track_id' => $track->id,
    //         'content' => $request->content,
    //     ]);

    //     $comment->load('user');

    //     return response()->json([
    //         'success' => true,
    //         'comment' => $comment,
    //     ]);
    // }

}
