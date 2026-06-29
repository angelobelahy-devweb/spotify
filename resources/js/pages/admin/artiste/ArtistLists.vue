<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { Search, SquarePen, Trash2, Shield, Zap, Crown } from 'lucide-vue-next';
import { defineProps, watch, ref } from 'vue'
import Swal from 'sweetalert2';
import Pagination from '@/components/Pagination.vue';
import { debounce } from 'lodash';

const props = defineProps({
    artists: Object,
    filters: Object,
})

// Reusable theme configuration with high-contrast text and white borders
const swalTheme = {
    // Solid dark background to block out any text underneath
    background: '#0d0d13',
    border: '#ff0000',

    // Smooth backdrop blur with a darker overlay to maximize contrast
    backdrop: 'rgba(0, 0, 0, 0.85) backdrop-filter: blur(8px);',

    buttonsStyling: false,

    // Change the exclamation mark icon color to white to match the new theme
    iconColor: '#ff0000',

    customClass: {
        // Forced a pure white border and strong shadow
        popup: 'border-2 border-white rounded-xl shadow-2xl p-8 flex flex-col items-center gap-4',

        // Used !text-white to bypass any low-opacity parenting issues
        title: '!text-white text-center text-2xl font-bold tracking-wide mt-2',
        htmlContainer: '!text-white text-sm font-medium leading-relaxed max-w-sm text-center mb-4 opacity-90',

        // Crisp button styles
        confirmButton: 'bg-[#33437e] hover:bg-[#364a92] active:scale-95 text-white font-bold py-2.5 px-8 rounded-full mx-2 transition-all duration-300 cursor-pointer shadow-lg text-sm border border-[#4a5eb5]/30',
        cancelButton: 'bg-red-800 hover:bg-red-700 text-white font-bold py-2.5 px-8 rounded-full mx-2 transition-all duration-300 cursor-pointer shadow-lg text-sm'
    }
};

// 2. Updated Flash Watcher
watch(
    () => usePage().props.flash,
    (flash) => {
        if (flash && flash.success) {
            Swal.fire({
                ...swalTheme,
                title: "Succès !",
                text: flash.success,
                icon: "success",
                timer: 3000,
                showConfirmButton: false
            });
        }
        if (flash && flash.error) {
            Swal.fire({
                ...swalTheme,
                title: "Erreur !",
                text: flash.error,
                icon: "error"
            });
        }
    },
    { deep: true }
);

// 3. Updated Delete Handler
const handleDelete = (id) => {
    Swal.fire({
      ...swalTheme,
      title: "Êtes-vous sûr ?",
      text: "Vous ne pourrez pas annuler cette action !",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Oui, supprimer !",
      cancelButtonText: "Annuler",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/admin/artists/${id}`, {
                preserveScroll: true,
                onError: () => {
                    Swal.fire({
                       ...swalTheme,
                       title: "Erreur !",
                       text: "Une erreur est survenue lors de la suppression.",
                       icon: "error"
                    });
                }
            });
        }
    });
}

const search = ref(props.filters?.search ?? '')

const performSearch = debounce(() => {
    router.get('/admin/artists', {
        search: search.value || undefined,
        per_page: props.filters?.per_page,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
}, 350)

watch(search, performSearch)


const perPage = ref(props.filters?.per_page ?? 5)

function onPerPageChange() {
    router.get('/admin/artists', {
        search: search.value || undefined,
        per_page: perPage.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
}
</script>

<template>
    <div>
        <!-- Header Section -->
        <div class="flex gap-2 items-center justify-between mb-6">
            <div class="flex gap-2 items-center">
                <h1 class="text-[#e4e8f3d0] text-2xl my-2">Artistes</h1>
            </div>

            <!-- Search Bar -->
            <div class="relative">
                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 z-50" />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Rechercher un artiste..."
                    class="bg-[#121212]/80 border border-[#33437e] rounded-lg pl-10 pr-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-[#4a5eb5] transition-colors"
                />
            </div>
        </div>

        <!-- Items Per Page Selector -->
        <div class="flex justify-end mb-4">
            <select
                v-model="perPage"
                @change="onPerPageChange"
                class="bg-[#121212]/80 border border-[#33437e] rounded-lg px-3 py-1 text-white text-sm focus:outline-none focus:border-[#4a5eb5]"
            >
                <option value="5">5 par page</option>
                <option value="10">10 par page</option>
                <option value="20">20 par page</option>
                <option value="50">50 par page</option>
            </select>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto border border-[#33437e] backdrop-blur-sm bg-black/40 w-[500px] rounded-lg  shadow-2xl w-full">
            <table class="min-w-full divide-y divide-[#33437e]/30">
                <thead class="bg-[#121212]/80">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase tracking-wider">Profil</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase tracking-wider">Surnom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase tracking-wider">Abonnement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <tbody class="bg-[#0a0a0a]/50 divide-y divide-[#33437e]/20">
                    <tr v-for="artist in artists.data" :key="artist.id" class="hover:bg-[#1a1a2e]/50 transition-colors">
                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-300">
                            <img :src="artist.user?.pdp ? `/storage/${artist.user.pdp}` : '/images/default-avatar.png'" alt="pdp" class="w-[35px] h-[35px] object-cover rounded-full border border-[#33437e]">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ artist.surname }}</td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span
                                v-if="artist.is_active"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20"
                            >
                                <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                                Active
                            </span>
                            <span
                                v-else
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-zinc-500/10 text-zinc-400 border border-zinc-500/20"
                            >
                                <span class="w-1.5 h-1.5 rounded-full bg-zinc-400"></span>
                                Inactif
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            <div v-if="artist.is_active" class="flex items-center gap-2">

                                <template v-if="artist.subscription_tier === 'basic'">
                                    <Shield class="w-4 h-4 text-gray-400" />
                                    <span class="text-sm font-medium text-gray-300">Basic</span>
                                </template>

                                <template v-else-if="artist.subscription_tier === 'premium'">
                                    <Zap class="w-4 h-4 text-blue-400" />
                                    <span class="text-sm font-semibold text-blue-400">Premium</span>
                                </template>

                                <template v-else-if="artist.subscription_tier === 'vip'">
                                    <Crown class="w-4 h-4 text-amber-400" />
                                    <span class="text-sm font-bold text-amber-400 tracking-wide uppercase text-xs">VIP</span>
                                </template>
                            </div>

                            <span v-else class="text-gray-600 text-sm">—</span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center">
                            <!--<Link :href="`/admin/artists/${artist.id}/update`" class="text-blue-400 hover:text-blue-300 mr-3 flex gap-1 items-center">
                                /<SquarePen /> <span>Edit</span>
                            </Link> -->
                            <button @click.prevent="handleDelete(artist.id)" class="text-red-400 hover:text-red-300 flex gap-1 items-center">
                                <Trash2 /> <span>Supprimer</span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <Pagination :pagination="artists" />

    </div>
</template>
