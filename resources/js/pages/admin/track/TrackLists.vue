<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { Search, Trash2 } from 'lucide-vue-next';
import { watch, ref } from 'vue' // <-- Nettoyé : defineProps est retiré d'ici
import Swal from 'sweetalert2';
import Pagination from '@/components/Pagination.vue';
import { debounce } from 'lodash';

const props = defineProps({
    tracks: Object,
    filters: Object,
})

// Reusable theme configuration with high-contrast text and white borders
const swalTheme = {
    background: '#0d0d13',
    border: '#ff0000',
    backdrop: 'rgba(0, 0, 0, 0.85) backdrop-filter: blur(8px);',
    buttonsStyling: false,
    iconColor: '#ff0000',
    customClass: {
        popup: 'border-2 border-white rounded-xl shadow-2xl p-8 flex flex-col items-center gap-4',
        title: '!text-white text-center text-2xl font-bold tracking-wide mt-2',
        htmlContainer: '!text-white text-sm font-medium leading-relaxed max-w-sm text-center mb-4 opacity-90',
        confirmButton: 'bg-[#33437e] hover:bg-[#364a92] active:scale-95 text-white font-bold py-2.5 px-8 rounded-full mx-2 transition-all duration-300 cursor-pointer shadow-lg text-sm border border-[#4a5eb5]/30',
        cancelButton: 'bg-red-800 hover:bg-red-700 text-white font-bold py-2.5 px-8 rounded-full mx-2 transition-all duration-300 cursor-pointer shadow-lg text-sm'
    }
};

// Flash Watcher
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

// Delete Handler
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
            router.delete(`/admin/tracks/${id}`, {
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
    router.get('/admin/tracks', {
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
    router.get('/admin/tracks', {
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
        <div class="flex gap-2 items-center justify-between mb-6">
            <div class="flex gap-2 items-center">
                <h1 class="text-[#e4e8f3d0] text-2xl my-2">Tracks</h1>
            </div>

            <div class="relative">
                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 z-50" />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Rechercher un track..."
                    class="bg-[#121212]/80 border border-[#33437e] rounded-lg pl-10 pr-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-[#4a5eb5] transition-colors"
                />
            </div>
        </div>

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

        <div class="overflow-x-auto border border-[#33437e] backdrop-blur-sm bg-black/40 rounded-lg shadow-2xl w-full">
            <table class="min-w-full divide-y divide-[#33437e]/30">
                <thead class="bg-[#121212]/80">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase tracking-wider">Album</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase tracking-wider">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase tracking-wider">Artist</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase tracking-wider">Durée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase tracking-wider">Nbr de commentaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase tracking-wider">Etat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <tbody class="bg-[#0a0a0a]/50 divide-y divide-[#33437e]/20">
                    <tr v-for="track in tracks.data" :key="track.id" class="hover:bg-[#1a1a2e]/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ track.album_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ track.title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ track.artist_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ track.duration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ track.comments }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ track.is_free ? 'Gratuit' : track.price }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center">
                            <button @click.prevent="handleDelete(track.id)" class="text-red-400 hover:text-red-300 flex gap-1 items-center">
                                <Trash2 />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :pagination="tracks" />

    </div>
</template>
