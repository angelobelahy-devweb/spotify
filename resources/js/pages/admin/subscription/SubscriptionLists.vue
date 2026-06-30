<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { Search, SquarePen, Trash2, Eye, Plus, Check, X } from 'lucide-vue-next';
import { defineProps, watch } from 'vue'
import Swal from 'sweetalert2';

const props = defineProps([
    'subscriptions',
    'pending_artists' // 🟢 Reçu depuis ton Admin/SubscriptionController
]);

const swalTheme = {
    background: '#0d0d13',
    border: '#ff0000',
    backdrop: 'rgba(0, 0, 0, 0.85) backdrop-filter: blur(8px);',
    buttonsStyling: false,
    iconColor: '#ff0000',
    customClass: {
        popup: 'border-2 border-white rounded-xl shadow-2xl p-8 flex flex-col items-center gap-4',
        title: '!text-white text-center text-2xl font-bold mt-2',
        htmlContainer: '!text-white text-sm font-medium max-w-sm text-center mb-4 opacity-90',
        confirmButton: 'bg-[#33437e] hover:bg-[#364a92] text-white font-bold py-2.5 px-8 rounded-full mx-2 cursor-pointer',
        cancelButton: 'bg-red-800 hover:bg-red-700 text-white font-bold py-2.5 px-8 rounded-full mx-2'
    }
};

watch(
    () => usePage().props.flash,
    (flash) => {
        if (flash && flash.success) {
            Swal.fire({ ...swalTheme, title: "Succès !", text: flash.success, icon: "success", timer: 3000, showConfirmButton: false });
        }
        if (flash && flash.error) {
            Swal.fire({ ...swalTheme, title: "Erreur !", text: flash.error, icon: "error" });
        }
    },
    { deep: true }
);

// 🟢 Action d'approbation mise à jour
const handleApprove = (id) => {
    router.post(`/admin/subscriptions/artists/${id}/approve`, {}, {
        preserveScroll: true,
        onSuccess: (page) => {
            // Déclenche manuellement l'alerte de succès si le watch ne l'intercepte pas
            Swal.fire({
                ...swalTheme,
                title: "Succès !",
                text: "L'artiste a été approuvé.",
                icon: "success",
                timer: 2000,
                showConfirmButton: false
            });
        },
        onError: (errors) => {
            Swal.fire({ ...swalTheme, title: "Erreur !", text: "Une erreur est survenue.", icon: "error" });
        }
    });
};

// 🟢 Action de rejet mise à jour
const handleReject = (id) => {
    Swal.fire({
        ...swalTheme,
        title: "Rejeter l'artiste ?",
        text: "Cette action modifiera le statut de l'artiste.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui, rejeter",
        cancelButtonText: "Annuler"
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(`/admin/subscriptions/artists/${id}/reject`, {}, {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({
                        ...swalTheme,
                        title: "Rejeté !",
                        text: "Le profil a été refusé.",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }
    });
};
</script>

<template>
    <div class="space-y-10">
        <!-- ================= SECTION 1 : ARTISTES EN ATTENTE DE VALIDATION ================= -->
        <div>
            <h2 class="text-amber-400 text-xl font-bold mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                Artistes en attente de validation
            </h2>

            <div class="overflow-x-auto border border-amber-500/30 backdrop-blur-sm bg-black/40 rounded-lg shadow-2xl w-full">
                <table class="min-w-full divide-y divide-[#33437e]/30">
                    <thead class="bg-[#121212]/80">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase">Nom d'artiste</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase">Actions de modération</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#0a0a0a]/50 divide-y divide-[#33437e]/20">
                        <tr v-if="!pending_artists || pending_artists.length === 0">
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">Aucun profil artiste en attente de validation.</td>
                        </tr>
                        <tr v-for="artist in pending_artists" :key="artist.id" class="hover:bg-[#1a1a2e]/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-white">{{ artist.surname }}</td>
                            <td class="px-6 py-4 text-sm text-gray-400 max-w-xs truncate">{{ artist.description || 'Aucune description' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">En attente</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm flex gap-3">
                                <button @click="handleApprove(artist.id)" class="bg-emerald-600/20 hover:bg-emerald-600 text-emerald-400 hover:text-white px-3 py-1.5 rounded-full text-xs font-medium flex items-center gap-1 border border-emerald-500/30 transition-all">
                                    <Check class="w-3 h-3" /> Accepter
                                </button>
                                <button @click="handleReject(artist.id)" class="bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white px-3 py-1.5 rounded-full text-xs font-medium flex items-center gap-1 border border-red-500/30 transition-all">
                                    <X class="w-3 h-3" /> Rejeter
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr class="border-[#33437e]/30" />

        <!-- ================= SECTION 2 : TOUS LES ABONNEMENTS STRIPE ================= -->
        <div>
            <div class="flex gap-2 items-center justify-between mb-6">
                <h1 class="text-[#e4e8f3d0] text-2xl my-2">Historique des abonnements</h1>
                <div class="relative">
                    <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 z-50" />
                    <input type="text" placeholder="Rechercher..." class="bg-[#121212]/80 border border-[#33437e] rounded-lg pl-10 pr-4 py-2 text-white text-sm focus:outline-none focus:border-[#4a5eb5]" />
                </div>
            </div>

            <div class="overflow-x-auto border border-[#33437e] backdrop-blur-sm bg-black/40 rounded-lg shadow-2xl w-full">
                <table class="min-w-full divide-y divide-[#33437e]/30">
                    <thead class="bg-[#121212]/80">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase">User ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase">Formule</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase">Statut Stripe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase">Expiration</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#0a0a0a]/50 divide-y divide-[#33437e]/20">
                        <tr v-for="sub in subscriptions" :key="sub.id" class="hover:bg-[#1a1a2e]/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ sub.user_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-400">{{ sub.type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">Active</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ sub.ends_at }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
