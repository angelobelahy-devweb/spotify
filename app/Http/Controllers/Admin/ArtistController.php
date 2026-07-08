<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ArtistController extends Controller
{
    //show list
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 5);
        $search = $request->input('search');

        $query = Artist::with(['user.subscriptions']);

        if ($search) {
            $query->where('surname', 'like', "%{$search}%");
        }

        $artists = $query->latest()->paginate($per_page)->withQueryString();

        // Map to add is_active and subscription_tier
        $artists->through(function ($artist) {
            $user = $artist->user;

            $subscription = $user?->subscriptions()
                ->where('stripe_status', 'active')
                ->where(function ($q) {
                    $q->whereNull('ends_at')
                      ->orWhere('ends_at', '>', now());
                })
                ->latest()
                ->first();

            if ($subscription) {
                // 1. C'est un abonné payant (Premium / VIP) via Stripe
                $artist->is_active = true;
                $artist->subscription_tier = $subscription->type;
            } else {
                // 2. Si aucune souscription Stripe (Plan Free)
                // On considère l'artiste actif si son statut global est approuvé
                $artist->is_active = ($artist->status === 'approved');

                // On récupère le pm_type (qui contient 'free') ou on met 'free' par défaut
                $artist->subscription_tier = $user?->pm_type ?? ($artist->status === 'approved' ? 'free' : 'aucun');
            }

            // L'ancien bloc d'écrasement a été supprimé d'ici !

            return $artist;
        });

        return Inertia::render('admin/artiste/ArtistLists', [
            'artists' => $artists,
            'filters' => [
                'search' => $search,
                'per_page' => $per_page,
            ]
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
            $artist = Artist::with('user')->findOrFail($id);
            $user = $artist->user;

            if ($user) {
                // 1. Remove subscription_items first (foreign key)
                $subscriptionIds = DB::table('subscriptions')
                    ->where('user_id', $user->id)
                    ->pluck('id');

                DB::table('subscription_items')
                    ->whereIn('subscription_id', $subscriptionIds)
                    ->delete();

                // 2. Remove subscriptions
                DB::table('subscriptions')
                    ->where('user_id', $user->id)
                    ->delete();

                // 3. Reset user back to basic — remove artist role and pm_type
                $basicRole = \App\Models\Role::whereRaw('LOWER(name) = ?', ['user'])->first();

                $user->update([
                    'pm_type' => null,
                    'role_id' => $basicRole?->id,
                ]);
            }

            // 4. Delete the artist profile
            $artist->delete();

            return redirect()->back()->with('success', 'L\'artiste et son abonnement ont été supprimés avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Impossible de supprimer cet artiste : ' . $e->getMessage());
        }
    }
}
