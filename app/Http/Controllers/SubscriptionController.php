<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    // 1. Afficher la page d'abonnement avec Inertia
    public function index()
    {
        // Indique à Laravel de charger resources/js/pages/subscription/Index.vue
        return Inertia::render('subscription/Index');
    }

    public function payment(Request $request)
    {
        $plan = $request->query('plan', 'premium');

        $availablePlans = ['basic', 'premium', 'vip'];
        if (!in_array($plan, $availablePlans, true)) {
            $plan = 'premium';
        }

        return Inertia::render('subscription/Payment', [
            'plan' => $plan,
        ]);
    }

    // 2. Traiter la simulation de paiement
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'Utilisateur non connecté']);
        }

        // Sécurisation de la valeur reçue
        $plan = $request->input('plan', 'premium');

        // Mettons à jour l'utilisateur avec la formule spécifique
        $user->stripe_id = 'sub_demo_' . $plan . '_' . time();
        $user->pm_type = $plan; // Stocke 'basic', 'premium', ou 'vip' pour savoir quel est son rôle actuel !
        $user->pm_last_four = '4242';
        $user->save();

        // Message personnalisé selon l'offre choisie
        $planNames = [
            'basic' => 'Mélomane Basic',
            'premium' => 'Mélomane Pro',
            'vip' => 'Mélomane VIP'
        ];

        $chosenPlanName = $planNames[$plan] ?? 'Premium';

        return redirect()->route('artists.create')
            ->with('success', "Paiement reçu. Votre formule {$chosenPlanName} est active. Complétez votre profil artiste.");
    }
}
