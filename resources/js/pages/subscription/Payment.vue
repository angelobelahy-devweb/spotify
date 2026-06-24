<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3'
import { ArrowLeft, Banknote, CreditCard, Calendar, ShieldCheck } from 'lucide-vue-next'
import { useToast } from 'primevue/usetoast'
import Toast from 'primevue/toast'

const props = defineProps<{ plan: string }>()

const planDetails = {
    basic: {
        name: 'Mélomane Basic',
        description: 'Accès gratuit avec publicités et lecture standard.',
        highlight: 'Pas de création artiste',
    },
    premium: {
        name: 'Mélomane Pro',
        description: 'Écoute illimitée, audio 320 kbps et accès artiste.',
        highlight: 'Accès création artiste activé',
    },
    vip: {
        name: 'Mélomane VIP',
        description: 'Tous les avantages Pro plus badge VIP et support prioritaire.',
        highlight: 'Statut VIP & création artiste instantanée',
    },
}

const selectedPlan = planDetails[props.plan] || planDetails.premium


const toast = useToast()
const form = useForm({
    plan: props.plan,
    bank_name: '',
    card_number: '',
    expiry_date: '',
    cvc: '',
    account_holder: '',
})

const submit = () => {
    if (!form.bank_name || !form.card_number || !form.expiry_date || !form.cvc || !form.account_holder) {
        toast.add({
            severity: 'warn',
            summary: 'Informations manquantes',
            detail: 'Veuillez remplir tous les champs de paiement pour poursuivre.',
            life: 4000,
        })
        return
    }

    form.post('/subscription/checkout', {
        preserveState: true,
    })
}
</script>

<template>
        <Toast />

        <div class="w-full p-6">
            <div class="rounded-t-4xl border border-[#33437e] backdrop-blur-sm bg-black/40 w-full p-6 shadow-[0_30px_80px_rgba(0,0,0,0.4)]">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-[#7f92ff]/70">Détails de paiement</p>
                        <h1 class="text-3xl font-bold text-white">Finalisez votre abonnement</h1>
                        <p class="mt-2 max-w-2xl text-sm text-gray-400">Entrez des informations bancaires simulées pour activer votre plan, puis complétez votre profil artiste.</p>
                    </div>
                    <Link href="/subscription" class="inline-flex items-center gap-2 rounded-full border border-[#4e5dd1]/40 bg-[#111826] px-4 py-2 text-sm text-gray-300 transition hover:border-[#7c8cf5] hover:text-white">
                        <ArrowLeft class="w-4 h-4" /> Retour
                    </Link>
                </div>
            </div>

            <div class="grid lg:grid-cols-[1.1fr_0.9fr]">
                <section class="rounded-bl-4xl border border-[#33437e] backdrop-blur-sm bg-black/40 w-full p-6 shadow-[0_25px_70px_rgba(0,0,0,0.35)]">
                    <div class="mb-6 flex items-center gap-3 rounded-3xl bg-[#11131d]/80 border border-[#2a3b70] p-4 shadow-[0_18px_40px_rgba(51,67,126,0.18)] p-5">
                        <Banknote class="h-6 w-6 text-[#8fa4ff]" />
                        <div>
                            <p class="text-sm uppercase tracking-[0.2em] text-[#8fa4ff]/80">Plan sélectionné</p>
                            <p class="mt-1 text-lg font-semibold text-white">{{ selectedPlan.name }}</p>
                            <p class="text-sm text-gray-400">{{ selectedPlan.description }}</p>
                        </div>
                    </div>

                    <div class="grid gap-4">
                        <div class="rounded-3xl bg-[#11131d]/80 border border-[#2a3b70] p-4 shadow-[0_18px_40px_rgba(51,67,126,0.18)] p-5">
                            <p class="text-xs uppercase tracking-[0.2em] text-[#7f92ff]/70">Résumé</p>
                            <p class="mt-3 text-sm text-gray-300">{{ selectedPlan.highlight }}</p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="space-y-2 text-sm text-gray-300">
                                Nom de la banque
                                <div class="flex items-center gap-3 rounded-2xl bg-[#11131d]/80 border border-[#2a3b70] px-4 py-3">
                                    <CreditCard class="h-5 w-5 text-[#7f92ff]" />
                                    <input v-model="form.bank_name" type="text" placeholder="Ex: Banque Nova" class="w-full bg-transparent text-white outline-none placeholder:text-gray-500" />
                                </div>
                            </label>

                            <label class="space-y-2 text-sm  text-gray-300">
                                Titulaire du compte
                                <input v-model="form.account_holder" type="text" placeholder="Votre nom complet" class="w-full rounded-2xl bg-[#11131d]/80 border border-[#2a3b70] px-4 py-3 text-white outline-none focus:border-[#556cff]" />
                            </label>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="space-y-2 text-sm text-gray-300">
                                Numéro de carte
                                <input v-model="form.card_number" type="text" placeholder="4242 4242 4242 4242" class="w-full rounded-2xl bg-[#11131d]/80 border border-[#2a3b70] px-4 py-3 text-white outline-none focus:border-[#556cff]" />
                            </label>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <label class="space-y-2 text-sm text-gray-300">
                                    Date d'expiration
                                    <div class="flex items-center gap-3 rounded-2xl bg-[#11131d]/80 border border-[#2a3b70] px-4 py-3">
                                        <Calendar class="h-5 w-5 text-[#7f92ff]" />
                                        <input v-model="form.expiry_date" type="text" placeholder="MM/AA" class="w-full bg-transparent text-white outline-none placeholder:text-gray-500" />
                                    </div>
                                </label>
                                <label class="space-y-2 text-sm text-gray-300">
                                    CVC
                                    <div class="flex items-center gap-3 rounded-2xl bg-[#11131d]/80 border border-[#2a3b70] px-4 py-3">
                                        <ShieldCheck class="h-5 w-5 text-[#7f92ff]" />
                                        <input v-model="form.cvc" type="text" placeholder="123" class="w-full bg-transparent text-sm text-white placeholder:text-gray-500 outline-none" />
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-br-4xl border border-[#33437e] backdrop-blur-sm bg-black/40 w-full p-6 shadow-[0_25px_70px_rgba(0,0,0,0.35)]">
                    <p class="text-xs uppercase tracking-[0.2em] text-[#7f92ff]/70">Récapitulatif</p>
                    <div class="mt-5 space-y-4">
                        <div class="rounded-3xl bg-[#11131d]/80 border border-[#2a3b70] p-4 shadow-[0_18px_40px_rgba(51,67,126,0.18)] p-5">
                            <p class="text-sm text-gray-300">Formule sélectionnée</p>
                            <p class="mt-2 text-xl font-semibold text-white">{{ selectedPlan.name }}</p>
                        </div>

                        <div class="rounded-3xl bg-[#11131d]/80 border border-[#2a3b70] p-4 shadow-[0_18px_40px_rgba(51,67,126,0.18)] p-5">
                            <p class="text-sm text-gray-300">Mode de paiement</p>
                            <p class="mt-2 text-lg font-semibold text-white">Carte bancaire</p>
                        </div>

                        <p class="text-sm text-gray-400">Aucun paiement réel n’est effectué. Ceci est une simulation qui débloque le formulaire artiste.</p>
                    </div>

                    <button @click.prevent="submit" type="button" class="mt-8 inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#33437e] px-6 py-4 text-sm font-bold uppercase text-white shadow-lg shadow-[#33437e]/20 transition hover:bg-[#3e5ecf] active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-50">
                        Confirmer le paiement
                    </button>
                </section>
            </div>
        </div>
</template>
