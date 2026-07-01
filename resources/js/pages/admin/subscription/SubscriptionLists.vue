<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { Search, Check, X, Clock, Users, CreditCard } from 'lucide-vue-next';
import { defineProps, watch, ref, computed } from 'vue'
import Swal from 'sweetalert2';

const props = defineProps(['subscriptions', 'pending_artists']);

const activeTab = ref('pending');
const searchQuery = ref('');

const filteredSubscriptions = computed(() => {
    if (!searchQuery.value) return props.subscriptions;
    const q = searchQuery.value.toLowerCase();
    return props.subscriptions.filter(s =>
        String(s.user_id).includes(q) ||
        s.type?.toLowerCase().includes(q)
    );
});

const swalTheme = {
    background: '#0d0d13',
    buttonsStyling: false,
    iconColor: '#ff0000',
    customClass: {
        popup: 'border border-[#33437e] rounded-2xl shadow-2xl p-8 flex flex-col items-center gap-4',
        title: '!text-white text-center text-xl font-bold mt-2',
        htmlContainer: '!text-white text-sm text-center mb-4 opacity-80',
        confirmButton: 'bg-[#33437e] hover:bg-[#364a92] text-white font-bold py-2.5 px-8 rounded-full mx-2 cursor-pointer transition-all',
        cancelButton: 'bg-red-900/60 hover:bg-red-800 text-white font-bold py-2.5 px-8 rounded-full mx-2 transition-all'
    }
};

watch(
    () => usePage().props.flash,
    (flash) => {
        if (flash?.success) {
            Swal.fire({ ...swalTheme, title: "Succès !", text: flash.success, icon: "success", timer: 3000, showConfirmButton: false });
        }
        if (flash?.error) {
            Swal.fire({ ...swalTheme, title: "Erreur !", text: flash.error, icon: "error" });
        }
    },
    { deep: true }
);

const handleApprove = (id) => {
    Swal.fire({
        ...swalTheme,
        title: "Approuver cet artiste ?",
        text: "L'utilisateur pourra configurer son profil artiste.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Oui, approuver",
        cancelButtonText: "Annuler"
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(`/admin/subscriptions/artists/${id}/approve`, {}, {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({ ...swalTheme, title: "Approuvé !", text: "L'artiste a été validé.", icon: "success", timer: 2000, showConfirmButton: false });
                },
                onError: () => {
                    Swal.fire({ ...swalTheme, title: "Erreur !", text: "Une erreur est survenue.", icon: "error" });
                }
            });
        }
    });
};

const handleReject = (id) => {
    Swal.fire({
        ...swalTheme,
        title: "Rejeter cet artiste ?",
        text: "L'utilisateur sera informé du refus et pourra resoumettre une demande.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui, rejeter",
        cancelButtonText: "Annuler"
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(`/admin/subscriptions/artists/${id}/reject`, {}, {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({ ...swalTheme, title: "Rejeté !", text: "Le profil a été refusé.", icon: "success", timer: 2000, showConfirmButton: false });
                }
            });
        }
    });
};

const formatDate = (date) => {
    if (!date) return '—';
    return new Date(date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });
};
</script>

<template>
    <div class="space-y-6">

        <!-- Tabs -->
        <div class="flex gap-1 bg-black/40 border border-[#33437e]/30 p-1 rounded-xl w-fit">
            <button
                @click="activeTab = 'pending'"
                :class="[
                    'flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                    activeTab === 'pending'
                        ? 'bg-[#33437e] text-white shadow-lg shadow-[#33437e]/20'
                        : 'text-gray-400 hover:text-white'
                ]"
            >
                <Clock class="w-4 h-4" />
                Validations en attente
                <span v-if="pending_artists?.length" class="bg-amber-500 text-black text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                    {{ pending_artists.length }}
                </span>
            </button>
            <button
                @click="activeTab = 'subscriptions'"
                :class="[
                    'flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200',
                    activeTab === 'subscriptions'
                        ? 'bg-[#33437e] text-white shadow-lg shadow-[#33437e]/20'
                        : 'text-gray-400 hover:text-white'
                ]"
            >
                <CreditCard class="w-4 h-4" />
                Historique abonnements
            </button>
        </div>

        <!-- Tab: Pending artists -->
        <div v-if="activeTab === 'pending'">
            <div v-if="!pending_artists || pending_artists.length === 0"
                class="border border-dashed border-[#33437e]/40 rounded-2xl p-16 flex flex-col items-center justify-center text-center space-y-3">
                <div class="bg-[#33437e]/10 rounded-full p-4">
                    <Users class="w-8 h-8 text-[#33437e]" />
                </div>
                <p class="text-white font-semibold">Aucune demande en attente</p>
                <p class="text-sm text-gray-500">Les nouvelles candidatures artistes apparaîtront ici.</p>
            </div>

            <div v-else class="space-y-3">
                <div
                    v-for="artist in pending_artists"
                    :key="artist.id"
                    class="bg-black/40 border border-[#33437e]/30 hover:border-[#33437e]/60 rounded-2xl p-5 flex items-center justify-between gap-4 transition-all duration-200"
                >
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-10 h-10 rounded-full bg-[#33437e]/30 border border-[#33437e]/50 flex items-center justify-center shrink-0 text-sm font-bold text-[#8fa4ff]">
                            {{ artist.surname?.charAt(0)?.toUpperCase() }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-white font-semibold text-sm">{{ artist.surname }}</p>
                            <p class="text-gray-500 text-xs truncate max-w-xs mt-0.5">
                                {{ artist.description || 'Aucune description fournie' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-400 border border-amber-500/20">
                            En attente
                        </span>
                        <button
                            @click="handleApprove(artist.id)"
                            class="flex items-center gap-1.5 bg-emerald-600/10 hover:bg-emerald-600 text-emerald-400 hover:text-white px-4 py-2 rounded-full text-xs font-semibold border border-emerald-500/30 transition-all duration-200"
                        >
                            <Check class="w-3.5 h-3.5" /> Approuver
                        </button>
                        <button
                            @click="handleReject(artist.id)"
                            class="flex items-center gap-1.5 bg-red-600/10 hover:bg-red-600 text-red-400 hover:text-white px-4 py-2 rounded-full text-xs font-semibold border border-red-500/30 transition-all duration-200"
                        >
                            <X class="w-3.5 h-3.5" /> Rejeter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Subscriptions -->
        <div v-if="activeTab === 'subscriptions'" class="space-y-4">
            <div class="relative w-72">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" />
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Rechercher par ID ou formule..."
                    class="w-full bg-black/40 border border-[#33437e]/40 focus:border-[#33437e] rounded-xl pl-10 pr-4 py-2.5 text-sm text-white placeholder-gray-600 outline-none transition-all"
                />
            </div>

            <div v-if="filteredSubscriptions.length === 0"
                class="border border-dashed border-[#33437e]/40 rounded-2xl p-16 flex flex-col items-center justify-center text-center space-y-3">
                <div class="bg-[#33437e]/10 rounded-full p-4">
                    <CreditCard class="w-8 h-8 text-[#33437e]" />
                </div>
                <p class="text-white font-semibold">Aucun abonnement trouvé</p>
                <p class="text-sm text-gray-500">Essayez un autre terme de recherche.</p>
            </div>

            <div v-else class="overflow-x-auto border border-[#33437e]/30 bg-black/40 rounded-2xl shadow-2xl">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-[#33437e]/20">
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Utilisateur</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Formule</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Statut</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Expiration</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#33437e]/10">
                        <tr
                            v-for="sub in filteredSubscriptions"
                            :key="sub.id"
                            class="hover:bg-[#33437e]/10 transition-colors duration-150"
                        >
                            <td class="px-6 py-4 text-sm text-gray-300 font-mono">#{{ sub.user_id }}</td>
                            <td class="px-6 py-4">
                                <span :class="[
                                    'px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider',
                                    sub.type === 'vip'
                                        ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20'
                                        : 'bg-blue-500/10 text-blue-400 border border-blue-500/20'
                                ]">
                                    {{ sub.type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="flex items-center gap-1.5 w-fit px-2.5 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                                    Actif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">{{ formatDate(sub.ends_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>