<script setup>
import { router, Link } from '@inertiajs/vue3'
import { Clock, RefreshCw, ArrowLeft } from 'lucide-vue-next'

// Fonction pour rafraîchir manuellement le statut
const checkStatus = () => {
    router.reload({
        only: ['auth'],
        onSuccess: () => {
            // Grâce à la logique du contrôleur, si le statut est passé à 'approved',
            // Inertia va automatiquement rediriger l'utilisateur vers /artistes/create
        }
    });
}
</script>

<template>
    <div class="text-white flex flex-col justify-center items-center min-h-[80vh] p-6">
        <div class="border border-amber-500/30 backdrop-blur-md bg-black/40 max-w-xl w-full p-8 rounded-4xl shadow-2xl text-center space-y-6">

            <!-- Icône Animée d'attente -->
            <div class="flex justify-center">
                <div class="relative flex items-center justify-center w-20 h-20 bg-amber-500/10 border-2 border-amber-500 rounded-full">
                    <Clock class="w-10 h-10 text-amber-400 animate-pulse" />
                </div>
            </div>

            <!-- Textes -->
            <div class="space-y-2">
                <h1 class="text-2xl font-black text-amber-400">Information reçu ! Validation en cours...</h1>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Votre abonnement a été traité avec succès. Pour des raisons de sécurité et de conformité, notre équipe administrative vérifie actuellement votre éligibilité au statut d'artiste.
                </p>
                <p class="text-xs text-amber-500/80 font-medium">
                    Une fois validé, cette page se débloquera automatiquement pour vous laisser configurer votre profil d'artiste.
                </p>
            </div>

            <hr class="border-white/10" />

            <!-- Actions -->
            <div class="flex flex-col gap-3 pt-2">
                <button
                    @click="checkStatus"
                    class="bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all px-6 py-3 rounded-full text-sm font-bold flex items-center justify-center gap-2 w-full cursor-pointer shadow-lg"
                >
                    <RefreshCw class="w-4 h-4 text-white" />
                    Vérifier si j'ai été validé
                </button>

                <Link href="/artistes" class="text-xs text-gray-500 hover:text-white flex items-center justify-center gap-1 transition-colors">
                    <ArrowLeft class="w-3 h-3" /> Retour à l'accueil
                </Link>
            </div>
        </div>
    </div>
</template>
