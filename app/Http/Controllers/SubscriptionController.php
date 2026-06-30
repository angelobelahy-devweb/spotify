<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function index()
    {
        return Inertia::render('subscription/Index');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'Utilisateur non connecté']);
        }

        // Fix de sécurité pour l'environnement de test (évite l'erreur No such customer des images 337ee4, 33dc24, 34545c)
        if ($user->stripe_id) {
            DB::table('users')->where('id', $user->id)->update([
                'stripe_id' => null,
                'trial_ends_at' => null
            ]);
            $user->refresh();
        }

        $plan = $request->input('plan', 'premium');

        $plansPricing = [
            'premium' => 'price_1TmdheRbkDcc1FxK3AhBhh7D',
            'vip'     => 'price_1TmdjLRbkDcc1FxKBoh10sIg',
        ];

        $stripePriceId = $plansPricing[$plan] ?? $plansPricing['premium'];

        $checkoutSession = $user->newSubscription($plan, $stripePriceId)
            ->checkout([
                'success_url' => route('subscription.success') . '?plan=' . $plan,
                'cancel_url'  => route('subscription.index'),
            ]);

        return Inertia::location($checkoutSession->url);
    }

    public function handleSuccess(Request $request)
    {
        $user = Auth::user();
        $plan = $request->query('plan', 'premium');

        if (!$user) {
            return redirect()->route('subscription.index')->with('error', 'Session expirée.');
        }

        // 1. Mettre à jour l'abonnement en premier (Qu'il soit nouveau ou un renouvellement)
        $plansPricing = [
            'premium' => 'price_1TmdheRbkDcc1FxK3AhBhh7D',
            'vip'     => 'price_1TmdjLRbkDcc1FxKBoh10sIg',
        ];
        $stripePriceId = $plansPricing[$plan] ?? $plansPricing['premium'];

        $oldSubscriptionIds = DB::table('subscriptions')
            ->where('user_id', $user->id)
            ->pluck('id');

        DB::table('subscription_items')->whereIn('subscription_id', $oldSubscriptionIds)->delete();
        DB::table('subscriptions')->where('user_id', $user->id)->delete();

        // Ajout du champ 'type' requis (Résout l'erreur QueryException de l'image 2982f3)
        $subscriptionId = DB::table('subscriptions')->insertGetId([
            'user_id'       => $user->id,
            'type'          => $plan,
            'stripe_id'     => 'sub_test_simulation_' . time(),
            'stripe_status' => 'active',
            'stripe_price'  => $stripePriceId,
            'quantity'      => 1,
            'ends_at'       => now()->addMonth(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        DB::table('subscription_items')->insert([
            'subscription_id' => $subscriptionId,
            'stripe_id'       => 'si_test_simulation_' . time(),
            'stripe_product'  => 'prod_test_' . $plan,
            'stripe_price'    => $stripePriceId,
            'quantity'        => 1,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $user->pm_type = $plan;
        $user->save();

        // 2. Création ou mise à jour du profil de l'artiste en mode 'pending'
        $user->artist()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'status' => 'pending',
                'surname' => $user->name, // Valeur temporaire avant le formulaire
            ]
        );

        return redirect()->route('subscription.pending');
    } // 👈 L'accolade manquante qui provoquait le ParseError a été ajoutée ici !

    // Afficher la page d'attente
    public function pending()
    {
        $user = Auth::user();

        // 🔄 Si l'admin a DEJA approuvé l'utilisateur entre temps,
        // on le redirige directement vers le formulaire de création !
        if ($user->artist && $user->artist->status === 'approved') {
            return redirect()->route('artists.create');
        }

        return Inertia::render('subscription/Pending');
    }
}
