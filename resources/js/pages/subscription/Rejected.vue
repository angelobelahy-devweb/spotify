<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { XCircle, CreditCard, User, RefreshCw, AlertTriangle, ChevronRight } from 'lucide-vue-next'

const reasons = [
    {
        icon: CreditCard,
        title: 'Vérifiez vos informations bancaires',
        description: 'Assurez-vous que le numéro de carte, la date d\'expiration et le CVV sont corrects.'
    },
    {
        icon: User,
        title: 'Vérifiez le nom du titulaire',
        description: 'Le nom inscrit doit correspondre exactement à celui figurant sur votre carte bancaire.'
    },
    {
        icon: AlertTriangle,
        title: 'Fonds insuffisants ou carte bloquée',
        description: 'Contactez votre banque pour vérifier que votre carte est active et dispose de fonds suffisants.'
    },
    {
        icon: RefreshCw,
        title: 'Essayez une autre carte',
        description: 'Si le problème persiste, tentez avec une autre carte ou un autre moyen de paiement.'
    },
]
</script>

<template>
    <div class="text-white flex flex-col justify-center items-center min-h-[80vh] p-6">
        <div class="border border-red-500/30 backdrop-blur-md bg-black/40 w-full max-w-2xl p-8 rounded-4xl shadow-2xl space-y-8">

            <!-- Header -->
            <div class="flex flex-col items-center text-center space-y-4">
                <div class="relative">
                    <div class="absolute inset-0 bg-red-500/20 rounded-full blur-xl scale-150"></div>
                    <div class="relative bg-red-500/10 border border-red-500/30 rounded-full p-5">
                        <XCircle class="w-10 h-10 text-red-400" />
                    </div>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-red-400/70 mb-1">Demande refusée</p>
                    <h1 class="text-2xl font-bold text-white">Votre paiement a été rejeté</h1>
                    <p class="mt-2 text-sm text-gray-400 max-w-sm mx-auto">
                        Votre demande d'abonnement n'a pas pu être traitée. Voici les raisons possibles et comment y remédier.
                    </p>
                </div>
            </div>

            <hr class="border-white/10" />

            <!-- Reasons -->
            <div class="space-y-3">
                <p class="text-xs uppercase tracking-widest text-gray-500 mb-4">Que vérifier ?</p>

                <div
                    v-for="(reason, index) in reasons"
                    :key="index"
                    class="flex items-start gap-4 bg-white/5 border border-white/10 rounded-2xl p-4 hover:border-red-500/30 transition-all duration-200"
                >
                    <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-2.5 shrink-0">
                        <component :is="reason.icon" class="w-4 h-4 text-red-400" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white">{{ reason.title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">{{ reason.description }}</p>
                    </div>
                </div>
            </div>

            <hr class="border-white/10" />

            <!-- Actions -->
            <div class="space-y-3">
                <Link
                    href="/subscription"
                    class="flex items-center justify-center gap-2 w-full bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all duration-300 px-6 py-3.5 rounded-full text-sm font-bold text-white shadow-lg shadow-[#33437e]/20"
                >
                    Réessayer avec un autre moyen de paiement
                    <ChevronRight class="w-4 h-4" />
                </Link>

                <Link
                    href="/artistes"
                    class="flex items-center justify-center gap-2 w-full border border-white/10 hover:border-white/20 bg-white/5 hover:bg-white/10 active:scale-95 transition-all duration-300 px-6 py-3 rounded-full text-sm text-gray-400 hover:text-white"
                >
                    Retour à la liste des artistes
                </Link>
            </div>

            <p class="text-center text-[11px] text-gray-600">
                Si le problème persiste après plusieurs tentatives, contactez votre banque ou notre support.
            </p>
        </div>
    </div>
</template>