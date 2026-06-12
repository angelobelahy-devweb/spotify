<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'track_id' => 'required|exists:tracks,id',
        ]);

        $user = $request->user();
        if (! $user) {
            abort(401);
        }

        $track = Track::with(['album.artist'])->findOrFail($request->track_id);
        $favorite = $user->favorites()->where('track_id', $track->id)->first();

        $isFavorite = false;
        if ($favorite) {
            $favorite->delete();
            $isFavorite = false;
        } else {
            $user->favorites()->create([
                'track_id' => $track->id,
                'artist_id' => $track->album?->artist?->id,
            ]);
            $isFavorite = true;
        }

        return response()->json([
            'favorite' => $isFavorite,
            'track' => [
                'id' => $track->id,
                'title' => $track->title,
                'artist' => $track->album?->artist?->surname ?? 'Artiste inconnu',
                'file_path' => $track->file_path,
                'duration' => $track->duration,
                'image' => $track->album?->image ?? '/assets/images/album.JPG',
            ],
        ]);
    }
}
