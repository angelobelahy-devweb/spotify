<script setup lang="ts">
import { ref, computed } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/vue3'
// Ajout des icônes Lock, Sparkles, Crown et Music
import { ArrowLeft, Check, CreditCard, Lock, Sparkles, Crown, Music } from 'lucide-vue-next'
import { useToast } from 'primevue/usetoast'
import Toast from 'primevue/toast'

const toast = useToast()
const page = usePage()

const selectedPlan = ref('premium')
const currentPlan = computed(() => String(page.props.auth?.user?.plan || 'basic').toLowerCase())
const isArtistEligible = computed(() => ['premium', 'vip'].includes(currentPlan.value))

// Propriété "icon" pour afficher dynamiquement les icônes dans la grille
const plans: Record<string, any> = {
    basic: {
        name: 'Plan Free',
        price: '0 €',
        period: 'Gratuit',
        badge: 'Essentiel',
        icon: Music,
        color: 'from-gray-800 to-gray-900',
        borderColor: 'border-gray-700',
        features: ['Écoute avec publicités', 'Qualité audio standard (160 kbps)', 'Mode écoute uniquement']
    },
    premium: {
        name: 'Plan Premium',
        price: '9,99 €',
        period: 'par mois',
        badge: 'Recommandé',
        icon: Sparkles,
        color: 'from-[#1e295d] to-[#111632]',
        borderColor: 'border-[#33437e]',
        features: ['Écoute illimitée sans publicités', 'Qualité audio haute fidélité (320 kbps)', 'Accès aux fonctionnalités Artiste & Distribution']
    },
    vip: {
        name: 'Plan VIP',
        price: '19,99 €',
        period: 'par mois',
        badge: 'Privilège',
        icon: Crown,
        color: 'from-[#3a1c5c] to-[#1a0b2e]',
        borderColor: 'border-[#6328a0]',
        features: ['Tous les avantages du plan Premium', 'Badge VIP exclusif sur votre profil', 'Support prioritaire 24/7 & Événements privés']
    }
}

// Initialisé avec le plan sélectionné par défaut ('premium')
const form = useForm({
    plan: 'premium'
})

const selectPlan = (planKey: string) => {
    selectedPlan.value = planKey
    form.plan = planKey
}

// 🟢 FUSIONNÉ : On envoie directement la requête POST vers l'action Checkout de Laravel
const submit = () => {
    // Si l'utilisateur clique sur le plan gratuit, tu peux adapter la logique ou bloquer l'envoi vers Stripe
    if (form.plan === 'basic') {
        toast.add({ severity: 'info', summary: 'Plan Free', detail: 'Vous possédez déjà le plan gratuit.', life: 3000 })
        return
    }

    form.post('/subscription/checkout')
}
</script>

<template>
    <Toast />

    <div class="text-white flex flex-col justify-center items-center min-h-[80vh] p-6 space-y-6">

        <div class="border border-[#33437e] backdrop-blur-md bg-black/40 w-full p-6 rounded-4xl shadow-2xl space-y-6">

            <div class="w-full flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-xl font-bold">Choisir votre abonnement</h1>
                    <p class="text-sm text-gray-400">Débloquez votre statut de créateur et commencez à publier vos titres dès aujourd'hui.</p>
                </div>
                <Link href="/artistes" class="text-gray-400 hover:text-white flex items-center gap-1 text-sm">
                    <ArrowLeft class="w-4 h-4" /> Retour
                </Link>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div
                    v-for="(plan, key) in plans"
                    :key="key"
                    @click="selectPlan(key)"
                    :class="[
                        'p-5 rounded-xl border transition-all duration-300 cursor-pointer flex flex-col justify-between bg-linear-to-br',
                        plan.color,
                        selectedPlan === key ? `${plan.borderColor} ring-2 ring-[#556cff]/50 scale-[1.02]` : 'border-neutral-800 opacity-70 hover:opacity-100'
                    ]"
                >
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span :class="['text-[10px] font-bold uppercase px-2 py-0.5 rounded-full', selectedPlan === key ? 'bg-white text-black' : 'bg-black/30 text-gray-300']">
                                {{ plan.badge }}
                            </span>
                            <component :is="plan.icon" class="w-4 h-4 text-[#556cff]" />
                        </div>

                        <div>
                            <h2 class="text-lg font-black mt-1">{{ plan.name }}</h2>
                            <div class="mt-2">
                                <span class="text-xl font-black text-[#556cff]">{{ plan.price }}</span>
                                <span class="text-xs text-gray-400 block">{{ plan.period }}</span>
                            </div>
                        </div>

                        <hr class="border-white/10" />

                        <ul class="space-y-2 text-xs text-gray-300">
                            <li v-for="feat in plan.features" :key="feat" class="flex items-start gap-1.5">
                                <Check class="w-3.5 h-3.5 text-green-400 shrink-0 mt-0.5" />
                                <span>{{ feat }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-4 pt-2">
                <div class="bg-black/40 p-4 rounded-lg border border-[#2e2e2e] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-[#1a1a1a] p-2 rounded-md">
                            <CreditCard class="w-5 h-5 text-gray-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium">Option sélectionnée : <span class="text-[#556cff] font-bold">{{ plans[selectedPlan].name }}</span></p>
                            <p class="text-xs text-gray-500">Passerelle de facturation sécurisée • Activation immédiate</p>
                        </div>
                    </div>
                    <span class="text-xs text-blue-400 bg-blue-500/10 px-2.5 py-1 rounded border border-blue-500/20 font-mono tracking-wide flex items-center gap-1.5">
                        <Lock class="w-3 h-3" /> PAIEMENT SÉCURISÉ
                    </span>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing || page.props.auth?.user?.artist?.status === 'pending'"
                    class="bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all duration-300 px-6 py-3 rounded-full text-sm font-bold flex items-center justify-center gap-2 w-full cursor-pointer disabled:opacity-50 shadow-lg shadow-[#33437e]/20"
                >
                    <span v-if="form.processing" class="flex gap-2 items-center">
                        Connexion...
                    </span>
                    <span v-else-if="page.props.auth?.user?.artist?.status === 'pending'">
                        Profil en cours de vérification administrative...
                    </span>
                    <span v-else>
                        S'abonner à la formule ({{ plans[selectedPlan].name }})
                    </span>
                </button>
            </form>

            <p class="text-center text-[11px] text-gray-500">
                En confirmant, vous acceptez nos Conditions Générales d'Utilisation et de Vente. Les droits d'accès seront instantanément appliqués à votre espace client.
            </p>
        </div>
    </div>
</template>
