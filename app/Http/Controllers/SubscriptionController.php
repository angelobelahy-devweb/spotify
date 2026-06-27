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

        if ($user) {
            $plansPricing = [
                'premium' => 'price_1TmdheRbkDcc1FxK3AhBhh7D',
                'vip'     => 'price_1TmdjLRbkDcc1FxKBoh10sIg',
            ];
            $stripePriceId = $plansPricing[$plan] ?? $plansPricing['premium'];

            // 1. Clean up old subscriptions
            $oldSubscriptionIds = DB::table('subscriptions')
                ->where('user_id', $user->id)
                ->pluck('id');

            DB::table('subscription_items')
                ->whereIn('subscription_id', $oldSubscriptionIds)
                ->delete();
            DB::table('subscriptions')
                ->where('user_id', $user->id)
                ->delete();

            // 2. Insert new subscription
            $subscriptionId = DB::table('subscriptions')->insertGetId([
                'user_id'       => $user->id,
                'type'          => $plan, // ✅ use plan name, not 'default'
                'stripe_id'     => 'sub_test_simulation_' . time(),
                'stripe_status' => 'active',
                'stripe_price'  => $stripePriceId,
                'quantity'      => 1,
                'ends_at'       => now()->addMonth(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // 3. Insert subscription item
            DB::table('subscription_items')->insert([
                'subscription_id' => $subscriptionId,
                'stripe_id'       => 'si_test_simulation_' . time(),
                'stripe_product'  => 'prod_test_' . $plan,
                'stripe_price'    => $stripePriceId,
                'quantity'        => 1,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // 4. Update pm_type so legacy checks still work
            $user->update(['pm_type' => $plan]);

            if ($user->artist) {
            return redirect()->route('artists.index')
                    ->with('success', 'Abonnement renouvelé avec succès !');
            }
        }

        return redirect()->route('artists.create')
                ->with('success', 'Abonnement activé ! Créez votre profil artiste.');
        }
}
