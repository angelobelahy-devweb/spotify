<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Role;

class ArtistController extends Controller
{
    // ✅ ADD THIS
    public function index()
    {
        $user = Auth::user();

        $subscription = $user?->subscriptions()
            ->where('stripe_status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>', now());
            })
            ->latest()
            ->first();

        return Inertia::render('music/artiste/ArtisteList', [
            'artists'              => Artist::with('user')->latest()->get(),
            'isArtist'             => $user?->artist !== null,
            'isSubscriptionActive' => !is_null($subscription),
        ]);
    }

    public function create()
    {
        $user = Auth::user();

        if (!$user || (!$user->subscribed('premium') && !$user->subscribed('vip'))) {
            return redirect()->route('subscription.index')
                ->with('error', 'Vous devez souscrire à une offre Premium ou VIP.');
        }

        return Inertia::render('music/artiste/Create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || (!$user->subscribed('premium') && !$user->subscribed('vip'))) {
            return redirect()->route('subscription.index')
                ->with('error', 'Action non autorisée. Veuillez mettre à niveau votre forfait.');
        }

        $artistRole = Role::whereRaw('LOWER(name) = ?', [strtolower(Role::ARTIST)])->first();

        $request->validate([
            'surname'     => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'required|image',
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('artists', 'public');
        }

        if ($user->artist) {
            return back();
        }

        if ($artistRole) {
            $user->update([
                'pdp'     => $image,
                'role_id' => $artistRole->id,
            ]);
        }

        Artist::create([
            'user_id'     => $user->id,
            'surname'     => $request->surname,
            'description' => $request->description,
        ]);

        return redirect()->route('artists.index')
            ->with('success', 'Artiste créé !');
    }
}
