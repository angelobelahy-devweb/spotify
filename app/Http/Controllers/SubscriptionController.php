<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Artist;

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

        if ($user->artist && $user->artist->status === 'rejected') {
            DB::table('artists')->where('user_id', $user->id)->delete();
            $user->refresh();
        }

        if ($user->stripe_id) {
            DB::table('users')->where('id', $user->id)->update([
                'stripe_id' => null,
                'trial_ends_at' => null
            ]);
            $user->refresh();
        }

        $plan = $request->input('plan', 'premium');

        if ($plan === 'free') {
            $oldSubscriptionIds = DB::table('subscriptions')
                ->where('user_id', $user->id)
                ->pluck('id');

            DB::table('subscription_items')->whereIn('subscription_id', $oldSubscriptionIds)->delete();
            DB::table('subscriptions')->where('user_id', $user->id)->delete();

            $subscriptionId = DB::table('subscriptions')->insertGetId([
                'user_id'       => $user->id,
                'type'          => 'free',
                'stripe_id'     => 'sub_free_simulation_' . time(),
                'stripe_status' => 'active',
                'stripe_price'  => 'price_free_0000',
                'quantity'      => 1,
                'ends_at'       => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            DB::table('subscription_items')->insert([
                'subscription_id' => $subscriptionId,
                'stripe_id'       => 'si_free_simulation_' . time(),
                'stripe_product'  => 'prod_free',
                'stripe_price'    => 'price_free_0000',
                'quantity'        => 1,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            $user->pm_type = 'free';
            $user->save();

            // 🔥 FIX : ON NE CRÉE PLUS L'ARTISTE ICI EN 'approved' !

            return redirect()->route('subscription.success', ['plan' => 'free']);
        }

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

        if ($plan !== 'free') {
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

            // 🔥 FIX : ON NE CRÉE PLUS L'ARTISTE ICI EN 'approved' !

            $user->pm_type = $plan;
            $user->save();
        }

        return Inertia::render('subscription/Accepted', [
            'plan' => $plan
        ]);
    }

    public function pending()
    {
        $user = Auth::user();

        if ($user->artist && $user->artist->status === 'approved') {
            return redirect('/artistes')->with('success', 'Votre compte artiste est désormais actif !');
        }

        if ($user->artist && $user->artist->status === 'rejected') {
            return redirect()->route('subscription.rejected');
        }

        return Inertia::render('subscription/Pending');
    }

    public function rejected()
    {
        $user = Auth::user();

        if (!$user->artist || $user->artist->status !== 'rejected') {
            return redirect()->route('subscription.index');
        }

        return Inertia::render('subscription/Rejected');
    }
}
