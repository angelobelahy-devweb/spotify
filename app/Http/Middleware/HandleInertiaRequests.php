<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'favorites' => function () use ($request) {
                if (! $request->user()) {
                    return [];
                }

                return $request->user()
                    ->favorites()
                    ->with(['track.album.artist'])
                    ->get()
                    ->map(function ($favorite) {
                        $track = $favorite->track;

                        return [
                            'id' => $track->id,
                            'title' => $track->title,
                            'artist' => $track->album?->artist?->surname ?? 'Artiste inconnu',
                            'file_path' => $track->file_path,
                            'duration' => $track->duration,
                            'image' => $track->album?->image ?? '/assets/images/album.JPG',
                        ];
                    })
                    ->toArray();
            },
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
