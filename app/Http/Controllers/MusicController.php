<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Album;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MusicController extends Controller
{
    //
    public function album() {
        $albums = Album::with('artist')->latest()->get();

        $purchasedAlbumIds = [];
        if (Auth::check()) {
            $purchasedAlbumIds = DB::table('purchases')
                ->where('user_id', Auth::id())
                ->pluck('album_id')
                ->toArray();
        }

        return Inertia::render("music/album/AlbumList", [
            'albums' => $albums,
            'purchasedAlbumIds' => $purchasedAlbumIds,
        ]);
    }

    public function detail(string $slug) {
        $album = Album::where('slug', $slug)->with(['artist', 'tracks'])->first();
        // $album = Album::with(['artist', 'tracks'])->findOrFail($slug);
        return Inertia::render("music/album/AlbumDetails", [
            'album' => $album
        ]);
    }

    public function artiste() {
        $user = Auth::user();

        $subscription = $user?->subscriptions()
        ->where('stripe_status', 'active')
        ->where(function ($q) {
            $q->whereNull('ends_at')
              ->orWhere('ends_at', '>', now());
        })
        ->latest()
        ->first();

        return Inertia::render("music/artiste/ArtisteList", [
            'artists' => Artist::with('user')->latest()->get(),
            'isArtist' => $user?->artist !== null,
            'isSubscriptionActive' => !is_null($subscription),
        ]);
    }
    public function favorie() {
        $favorites = auth()->user()->favorites()
            ->with(['track.album.artist'])
            ->get()
            ->map(function ($favorite) {
                $track = $favorite->track;
                return [
                    'id' => $track?->id,
                    'title' => $track?->title,
                    'artist' => $track?->album?->artist?->surname ?? 'Artiste inconnu',
                    'duration' => $track?->duration,
                    'file_path' => $track?->file_path,
                    'image' => $track?->album?->image ? "/storage/{$track->album->image}" : '/assets/images/album.JPG',
                ];
            });

        return Inertia::render("music/Favorie", [
            'favorites' => $favorites,
        ]);
    }
}
