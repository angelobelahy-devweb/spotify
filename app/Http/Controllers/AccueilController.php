<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Genre;
use App\Models\Role;
use App\Models\Track;



class AccueilController extends Controller
{
    //
    public function index()
    {
        

            // Role::create(
            //     ['name' => 'admin']
            // );
        $tracks = Track::with(['album.artist.user', 'genres'])->get();
        $genres = Genre::select('id', 'name')->get();
        return Inertia::render('Accueil', [
            'genres' => $genres,
            'tracks' => $tracks,
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
