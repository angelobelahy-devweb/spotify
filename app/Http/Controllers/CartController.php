<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $albums = Album::with('artist')->get();

        return Inertia::render('music/achat/Checkout', [
            'albums' => $albums
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'album_ids' => 'required|array',
            'album_ids.*' => 'exists:albums,id'
        ]);

        $user = Auth::user();
        $albums = Album::whereIn('id', $request->album_ids)->get();

        // 1. Build line items array for Stripe
        $lineItems = [];
        foreach ($albums as $album) {
            $price = is_numeric($album->price) ? $album->price : 0;

            // Bypass Stripe checkout if the cart total happens to be completely free
            if ($price > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $album->title,
                        ],
                        'unit_amount' => (int)($price * 100), // Stripe expects amounts in cents
                    ],
                    'quantity' => 1,
                ];
            }
        }

        // If all selected albums were free, bypass stripe and unlock immediately
        if (empty($lineItems)) {
            return $this->fulfillOrder($user->id, $albums);
        }

        // 2. Fire up a one-time Stripe Checkout Session (Similar to your subscriptions setup)
        $checkoutSession = $user->checkout($lineItems, [
            'success_url' => route('cart.success') . '?albums=' . implode(',', $request->album_ids),
            'cancel_url'  => route('cart.index'),
        ]);

        // 3. Return the Interia location wrapper to redirect your frontend to Stripe
        return Inertia::location($checkoutSession->url);
    }

    // New method to handle successful redirect landing page
    public function handleSuccess(Request $request)
    {
        $user = Auth::user();
        $albumIdsString = $request->query('albums');

        if (!$user || !$albumIdsString) {
            return redirect('/albums')->with('error', 'Commande invalide.');
        }

        $albumIds = explode(',', $albumIdsString);
        $albums = Album::whereIn('id', $albumIds)->get();

        // Fixed typo here: changed "return the $this->..." to "return $this->..."
        return $this->fulfillOrder($user->id, $albums);
    }

    // Helper function to safely insert matching records into database
    private function fulfillOrder($userId, $albums)
    {
        foreach ($albums as $album) {
            $price = is_numeric($album->price) ? $album->price : 0;

            // Optional check to avoid duplicate purchase logs
            $exists = DB::table('purchases')
                ->where('user_id', $userId)
                ->where('album_id', $album->id)
                ->exists();

            if (!$exists) {
                DB::table('purchases')->insert([
                    'user_id'     => $userId,
                    'album_id'    => $album->id,
                    'amount_paid' => $price,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        return redirect('/albums')->with('success', 'Achat effectué avec succès !');
    }
}
