<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Artist;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    public function index()
    {
        // On récupère les abonnements ainsi que les artistes 'pending' avec leurs infos utilisateur (nom, email...)
        return Inertia::render('admin/subscription/SubscriptionLists', [
            'subscriptions'   => Subscription::all(),
            'pending_artists' => Artist::where('status', 'pending')->with('user')->get(),
        ]);
    }

    public function approveArtist($id)
    {
        $artist = \App\Models\Artist::findOrFail($id);
        $artist->update(['status' => 'approved']);

        // On s'assure d'envoyer à la fois 'success' pour ton watch et de rediriger explicitement
        return redirect()->route('admin.subscriptions.index')
            ->with('success', "L'artiste a été validé avec succès.");
    }

    public function rejectArtist($id)
    {
        $artist = \App\Models\Artist::findOrFail($id);
        $artist->update(['status' => 'rejected']);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', "Le profil de l'artiste a été rejeté.");
    }
}
