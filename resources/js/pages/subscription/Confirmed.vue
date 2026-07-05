<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { CheckCircle2, Music, ShieldCheck, ArrowRight } from 'lucide-vue-next';

const props = defineProps({
    plan: String
});

const isPremium = computed(() => props.plan === 'premium');

const features = computed(() => {
    if (isPremium.value) {
        return [
            { text: 'Téléverser jusqu\'à 10 albums complets', allowed: true },
            { text: 'Ajouter jusqu\'à 50 pistes audio (tracks)', allowed: true },
            { text: 'Accès aux statistiques de lecture basiques', allowed: true },
            { text: 'Badge Artiste Premium sur votre profil', allowed: true },
        ];
    } else {
        return [
            { text: 'Téléverser des albums en illimité', allowed: true },
            { text: 'Ajouter des pistes audio (tracks) en illimité', allowed: true },
            { text: 'Accès complet aux statistiques avancées', allowed: true },
            { text: 'Support technique prioritaire & Badge VIP', allowed: true },
        ];
    }
});
</script>

<template>
    <div class="text-white flex flex-col justify-center items-center min-h-screen p-6 bg-neutral-950">
        <div class="border border-[#33437e] backdrop-blur-md bg-black/40 max-w-xl w-full p-8 rounded-3xl shadow-2xl space-y-6 text-center">

            <div class="flex flex-col items-center space-y-3">
                <div class="bg-green-500/10 p-4 rounded-full border border-green-500/30 text-green-400">
                    <CheckCircle2 class="w-12 h-12" />
                </div>
                <h1 class="text-2xl font-black tracking-wide">Paiement & Profil Validés !</h1>
                <p class="text-sm text-gray-400">
                    L'administration a approuvé votre demande. Votre forfait <span class="text-[#556cff] font-bold uppercase">{{ plan }}</span> est désormais entièrement actif.
                </p>
            </div>

            <hr class="border-white/10" />

            <div class="text-left space-y-4 bg-white/5 p-5 rounded-2xl border border-white/5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 flex items-center gap-2">
                    <Music class="w-4 h-4 text-[#556cff]" /> Vos privilèges de publication :
                </h3>

                <ul class="space-y-3 text-sm">
                    <li v-for="(feat, index) in features" :key="index" class="flex items-start gap-3 text-gray-200">
                        <ShieldCheck class="w-5 h-5 text-green-400 shrink-0 mt-0.5" />
                        <span>{{ feat.text }}</span>
                    </li>
                </ul>
            </div>

            <div class="pt-2">
                <Link
                    href="/artistes/create"
                    class="bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all duration-300 px-8 py-4 rounded-full text-sm font-bold flex items-center justify-center gap-2 w-full shadow-lg shadow-[#33437e]/20 group"
                >
                    Terminer l'abonnement & créer mon profil
                    <ArrowRight class="w-4 h-4 transition-transform group-hover:translate-x-1" />
                </Link>
            </div>

            <p class="text-[11px] text-gray-500">
                Vous allez être redirigé vers le formulaire d'identité pour configurer votre nom de scène public, votre biographie et votre pochette.
            </p>
        </div>
    </div>
</template>
