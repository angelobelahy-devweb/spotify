<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    // Affiche le choix des formules (index.vue fusionné)
    public function index()
    {
        return Inertia::render('subscription/Index');
    }

    // Traite le vrai paiement avec Stripe Checkout
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'Utilisateur non connecté']);
        }

        $plan = $request->input('plan', 'premium');

        $plansPricing = [
            'premium' => 'price_1TmdheRbkDcc1FxK3AhBhh7D',
            'vip'     => 'price_1TmdjLRbkDcc1FxKBoh10sIg',
        ];

        $stripePriceId = $plansPricing[$plan] ?? $plansPricing['premium'];

        // On crée la session Checkout
        $checkoutSession = $user->newSubscription($plan, $stripePriceId)
            ->checkout([
                // 🟢 MODIFICATION ICI : On redirige vers une route de traitement local au retour
                'success_url' => route('subscription.success') . '?plan=' . $plan,
                'cancel_url'  => route('subscription.index'),
            ]);

        return Inertia::location($checkoutSession->url);
    }

    public function handleSuccess(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $plan = $request->query('plan', 'premium');

        if ($user) {
            // 1. Nettoyage de sécurité pour éviter les conflits locaux
            $oldSubscriptionIds = DB::table('subscriptions')
                ->where('user_id', $user->id)
                ->pluck('id');

            DB::table('subscription_items')->whereIn('subscription_id', $oldSubscriptionIds)->delete();
            DB::table('subscriptions')->where('user_id', $user->id)->delete();

            // 2. Détermination du Price ID Stripe
            $plansPricing = [
                'premium' => 'price_1TmdheRbkDcc1FxK3AhBhh7D',
                'vip'     => 'price_1TmdjLRbkDcc1FxKBoh10sIg',
            ];
            $stripePriceId = $plansPricing[$plan] ?? $plansPricing['premium'];

            // 3. Insertion dans la table parente 'subscriptions'
            $subscriptionId = DB::table('subscriptions')->insertGetId([
                'user_id'       => $user->id,
                'type'          => 'default',
                'stripe_id'     => 'sub_test_simulation_' . time(),
                'stripe_status' => 'active',
                'stripe_price'  => $stripePriceId, // Optionnel selon ta version, mais plus sûr
                'quantity'      => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // 4. 🟢 LA PIÈCE MANQUANTE : Insertion dans la table 'subscription_items'
            // C'est cette table qui valide officiellement le ->subscribed() de Cashier !
            DB::table('subscription_items')->insert([
                'subscription_id' => $subscriptionId,
                'stripe_id'       => 'si_test_simulation_' . time(),
                'stripe_product'  => 'prod_test_' . $plan,
                'stripe_price'    => $stripePriceId,
                'quantity'        => 1,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        // On redirige vers la page de création d'artiste
        return redirect()->route('artists.create')->with('success', 'Abonnement activé avec succès !');
    }
}
