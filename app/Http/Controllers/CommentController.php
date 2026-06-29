<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Track;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Track $track)
    {

        $request->validate([
            'content' => 'required|string|max:65535',
        ]);
        
        Comment::create([
            'user_id' => auth()->id(),
            'track_id' => $track->id,
            'content' => $request->content,
        ]);

        return redirect()->back();
    //     dd(
    //     $track,
    //     $request->all(),
    //     auth()->id()
    // );
        // $request->validate([
        //     'content' => 'required|string|max:65535',
        // ]);

        // $user = $request->user();
        // if (! $user) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }

        // $comment = $track->comments()->create([
        //     'user_id' => $user->id,
        //     'content' => $request->content,
        // ]);

        // $comment->load('user');
        

        // return response()->json([
        //     'comment' => [
        //         'id' => $comment->id,
        //         'content' => $comment->content,
        //         'created_at' => $comment->created_at->toDateTimeString(),
        //         'user' => [
        //             'id' => $comment->user->id,
        //             'name' => $comment->user->name ?? $comment->user->surname ?? 'Utilisateur',
        //         ],
        //     ],
        // ]);
    }
}
