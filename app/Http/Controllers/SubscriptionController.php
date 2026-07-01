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

        $user->pm_type = $plan;
        $user->save();

        $user->artist()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'status'  => 'pending',
                'surname' => $user->name,
            ]
        );

        return redirect()->route('subscription.pending');
    }

    public function pending()
    {
        $user = Auth::user();

        if ($user->artist && $user->artist->status === 'approved') {
            return redirect()->route('artists.create');
        }

        if ($user->artist && $user->artist->status === 'rejected') {
            return redirect()->route('subscription.rejected');
        }

        return Inertia::render('subscription/Pending');
    }

    public function rejected()
    {
        $user = Auth::user();

        // If they somehow land here without being rejected, redirect appropriately
        if (!$user->artist || $user->artist->status !== 'rejected') {
            return redirect()->route('subscription.index');
        }

        return Inertia::render('subscription/Rejected');
    }
}
