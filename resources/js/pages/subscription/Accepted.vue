<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { CheckCircle, Sparkles, Crown, ArrowRight, ShieldCheck } from 'lucide-vue-next'

const props = defineProps({
    plan: String
})

const planDetails = computed(() => {
    if (props.plan === 'vip') {
        return {
            name: 'Plan VIP',
            icon: Crown,
            color: 'text-purple-400 border-purple-500/30 bg-purple-500/10',
            description: 'Albums & Tracks illimités',
        }
    }
    return {
        name: 'Plan Premium',
        icon: Sparkles,
        color: 'text-blue-400 border-blue-500/30 bg-blue-500/10',
        description: '10 albums et 50 tracks inclus',
    }
})
</script>

<template>
    <div class="text-white flex flex-col justify-center items-center min-h-[80vh] p-6">
        <div class="border border-emerald-500/30 backdrop-blur-md bg-black/40 w-full max-w-xl p-8 rounded-4xl shadow-2xl space-y-8 text-center">

            <div class="flex justify-center">
                <div class="relative">
                    <div class="absolute inset-0 bg-emerald-500/20 rounded-full blur-xl scale-150"></div>
                    <div class="relative bg-emerald-500/10 border border-emerald-500/30 rounded-full p-5">
                        <CheckCircle class="w-10 h-10 text-emerald-400" />
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <p class="text-xs uppercase tracking-[0.3em] text-emerald-400 font-semibold">Paiement Réussi</p>
                <p class="text-sm text-gray-400 max-w-sm mx-auto">
                    Votre transaction a été approuvée par Stripe. Votre espace créateur est prêt à être configuré.
                </p>
            </div>

            <hr class="border-white/10" />

            <div class="bg-white/5 border border-white/10 rounded-2xl p-5 flex items-center justify-between text-left">
                <div class="flex items-center gap-4">
                    <div :class="['border rounded-xl p-3', planDetails.color]">
                        <component :is="planDetails.icon" class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Formule activée</p>
                        <h3 class="text-lg font-bold text-white">{{ planDetails.name }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ planDetails.description }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 text-xs text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 rounded-full font-medium">
                    <ShieldCheck class="w-3.5 h-3.5" /> Réglé
                </div>
            </div>

            <hr class="border-white/10" />

            <div>
                <Link
                    href="/artistes/create"
                    class="flex items-center justify-center gap-2 w-full bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all duration-300 px-6 py-4 rounded-full text-sm font-bold text-white shadow-lg shadow-[#33437e]/20"
                >
                    Continuer et créer mon profil artiste
                    <ArrowRight class="w-4 h-4" />
                </Link>
            </div>
        </div>
    </div>
</template>
