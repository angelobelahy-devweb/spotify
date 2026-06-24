<script setup lang="ts">
import { ref, computed } from 'vue'
import { router, useForm, Link, usePage } from '@inertiajs/vue3'
import { ArrowLeft, Check, CreditCard } from 'lucide-vue-next'
import { useToast } from 'primevue/usetoast'
import Toast from 'primevue/toast'

const toast = useToast()
const page = usePage()

// Formule sélectionnée par défaut
const selectedPlan = ref('premium')
const currentPlan = computed(() => String(page.props.auth?.user?.plan || 'basic').toLowerCase())
const isArtistEligible = computed(() => ['premium', 'vip'].includes(currentPlan.value))

const plans = {
    basic: {
        name: 'Mélomane Basic',
        price: '0 €',
        period: 'Gratuit',
        badge: 'Gratuit',
        color: 'from-gray-800 to-gray-900',
        borderColor: 'border-gray-700',
        features: ['Écoute avec publicités', 'Qualité audio standard (160 kbps)', 'Pas d\'accès téléversement']
    },
    premium: {
        name: 'Mélomane Pro',
        price: '9,99 €',
        period: 'par mois',
        badge: 'Populaire',
        color: 'from-[#1e295d] to-[#111632]',
        borderColor: 'border-[#33437e]',
        features: ['Écoute illimitée sans publicités', 'Qualité audio supérieure (320 kbps)', 'Accès au téléversement d\'artistes']
    },
    vip: {
        name: 'Mélomane VIP',
        price: '19,99 €',
        period: 'par mois',
        badge: 'Privilège',
        color: 'from-[#3a1c5c] to-[#1a0b2e]',
        borderColor: 'border-[#6328a0]',
        features: ['Tous les avantages du plan Pro', 'Badge VIP sur votre profil', 'Support prioritaire 24/7 & Événements']
    }
}

const form = useForm({
    plan: 'premium'
})

const selectPlan = (planKey: string) => {
    selectedPlan.value = planKey
    form.plan = planKey
}

const submit = () => {
    router.get('/subscription/payment', { plan: form.plan })
}
</script>

<template>
    <Toast />

    <div class="text-white flex flex-col justify-center items-center min-h-[80vh] p-6 space-y-6">

        <div class="border border-[#33437e] backdrop-blur-md bg-black/40 w-full p-6 rounded-4xl shadow-2xl space-y-6">

            <div class="w-full flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <div class="space-y-2">
                    <Link href="/artistes" class="text-gray-400 hover:text-white flex items-center gap-1 text-sm">
                        <ArrowLeft class="w-4 h-4" /> Retour
                    </Link>
                    <div>
                        <h1 class="text-xl font-bold">Choisir une formule d'abonnement</h1>
                        <p class="text-sm text-gray-400">Passez au plan Pro ou VIP pour débloquer le statut artiste et téléverser vos titres.</p>
                    </div>
                </div>

                <div class="rounded-3xl bg-[#11131d]/80 border border-[#2a3b70] p-4 shadow-[0_18px_40px_rgba(51,67,126,0.18)]">
                    <p class="text-xs uppercase tracking-[0.25em] text-[#8fa4ff]/70">Déblocage artiste</p>
                    <p class="mt-3 text-sm text-gray-300">{{ isArtistEligible ? "Vous avez déjà accès à la création d'artiste." : "Choisissez Premium ou VIP pour pouvoir créer un profil artiste." }}</p>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <Link
                            v-if="isArtistEligible"
                            href="/artistes/create"
                            class="rounded-full bg-[#33437e] px-4 py-2 text-xs font-bold uppercase text-white shadow-lg shadow-[#33437e]/20 transition hover:bg-[#3f5dce]"
                        >
                            Créer un profil artiste
                        </Link>
                        <span v-else class="rounded-full border border-[#556cff]/40 bg-[#111827] px-4 py-2 text-xs font-semibold uppercase text-[#dbe5ff]">
                            Sélectionnez un plan Pro/VIP
                        </span>
                    </div>
                </div>
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
                            <p class="text-sm font-medium">Formule sélectionnée : <span class="text-[#556cff] font-bold">{{ plans[selectedPlan].name }}</span></p>
                            <p class="text-xs text-gray-500">Validation instantanée • Mode démo actif</p>
                        </div>
                    </div>
                    <span class="text-xs text-green-400 bg-green-500/10 px-2 py-0.5 rounded border border-green-500/20 font-mono">
                        MODE ÉCOLE
                    </span>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all duration-300 px-6 py-3 rounded-full text-sm font-bold flex items-center justify-center gap-2 w-full cursor-pointer disabled:opacity-50 shadow-lg shadow-[#33437e]/20"
                >
                    <span v-if="form.processing" class="flex gap-2 items-center">
                        Validation de la formule {{ plans[selectedPlan].name }}...
                    </span>
                    <span v-else>
                        Confirmer et activer la formule {{ plans[selectedPlan].name }}
                    </span>
                </button>
            </form>

            <p class="text-center text-[11px] text-gray-500">
                Aucun paiement réel. Les droits associés à la formule seront appliqués à votre session.
            </p>
        </div>
    </div>
</template>
