<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Artist;
use App\Models\Role;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    public function index()
    {
        // On récupère les abonnements ainsi que les artistes 'pending' avec leurs infos utilisateur
        return Inertia::render('admin/subscription/SubscriptionLists', [
            'subscriptions'   => Subscription::all(),
            'pending_artists' => Artist::where('status', 'pending')->with('user')->get(),
        ]);
    }

    public function approveArtist($id)
    {
        // 1. Trouver l'artiste ou renvoyer une erreur
        $artist = Artist::with('user')->findOrFail($id);

        // 2. Passer le statut à approved
        $artist->update(['status' => 'approved']);

        // 3. Assigner le rôle "artiste" à l'utilisateur s'il existe
        if ($artist->user) {
            // Cherche le rôle 'artist' ou 'artiste' en minuscules
            $artistRole = Role::whereRaw('LOWER(name) = ?', ['artist'])
                              ->orWhereRaw('LOWER(name) = ?', ['artiste'])
                              ->first();

            if ($artistRole) {
                $artist->user->update([
                    'role_id' => $artistRole->id
                ]);
            }
        }

        return redirect()->route('admin.subscriptions.index')
            ->with('success', "L'artiste {$artist->surname} a été validé avec succès.");
    }

    public function rejectArtist($id)
    {
        $artist = Artist::findOrFail($id);

        // Passer le statut à rejected
        $artist->update(['status' => 'rejected']);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', "Le profil de l'artiste {$artist->surname} a été rejeté.");
    }
}
