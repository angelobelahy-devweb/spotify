<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { Search, SquarePen, Trash2, Eye, Plus } from 'lucide-vue-next';
import { defineProps, watch } from 'vue'
import Swal from 'sweetalert2';

const props = defineProps([
    'genres'
]);

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
        title: '!text-white text-center text-2xl font-bold genreing-wide mt-2',
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
            router.delete(`/admin/genres/${id}`, {
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

</script>

<template>
    <div>
        <!-- Header Section -->
        <div class="flex gap-2 items-center justify-between mb-6">
            <div class="flex gap-2 items-center">
                <h1 class="text-[#e4e8f3d0] text-2xl my-2">Genres</h1>
                <Link href="/admin/genres/create" class="flex gap-2 items-center bg-[#121212]/80 border-2 border-[#33437e] rounded-full w-[max-content] p-1 hover:translate-y-1 transition-all duration-300">
                    <div class="bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all duration-300 p-1 rounded-full text-sm font-bold flex items-center justify-center gap-2 w-6 h-6 cursor-pointer disabled:opacity-50">
                        <Plus />
                    </div>
                    <span class="text-xs text-white uppercase">Créer</span>
                </Link>
            </div>

            <!-- Search Bar -->
            <div class="relative">
                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 z-50" />
                <input
                    type="text"
                    placeholder="Rechercher un genre..."
                    class="bg-[#121212]/80 border border-[#33437e] rounded-lg pl-10 pr-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-[#4a5eb5] transition-colors"
                />
            </div>
        </div>

        <!-- Items Per Page Selector -->
        <div class="flex justify-end mb-4">
            <select
                class="bg-[#121212]/80 border border-[#33437e] rounded-lg px-3 py-1 text-white text-sm focus:outline-none focus:border-[#4a5eb5]"
            >
                <option value="">5 par page</option>
                <option value="">10 par page</option>
                <option value="">20 par page</option>
                <option value="">50 par page</option>
            </select>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto border border-[#33437e] backdrop-blur-sm bg-black/40 w-[500px] rounded-lg  shadow-2xl w-full">
            <table class="min-w-full divide-y divide-[#33437e]/30">
                <thead class="bg-[#121212]/80">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase genreing-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#e4e8f3d0] uppercase genreing-wider">Actions</th>
                    </tr>
                </thead>

                <tbody class="bg-[#0a0a0a]/50 divide-y divide-[#33437e]/20">
                    <tr v-for="genre in genres" :key="genre.id" class="hover:bg-[#1a1a2e]/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ genre.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center">
                            <Link :href="`/admin/genres/${genre.id}/update`" class="text-blue-400 hover:text-blue-300 mr-3 flex gap-1 items-center">
                                <SquarePen /> <span>Edit</span>
                            </Link>
                            <button @click.prevent="handleDelete(genre.id)" class="text-red-400 hover:text-red-300 flex gap-1 items-center">
                                <Trash2 /> <span>Supprimer</span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="flex items-center justify-between mt-6">
            <div class="text-sm text-gray-400">
                Affichage de {{ (currentPage - 1) * itemsPerPage + 1 }} à {{ Math.min(currentPage * itemsPerPage, filteredgenres.length) }} sur {{ filteredgenres.length }} genrees
            </div>

            <div class="flex items-center gap-2">
                <button
                    @click="prevPage"
                    :disabled="currentPage === 1"
                    class="p-2 rounded-lg bg-[#121212]/80 border border-[#33437e] text-white disabled:opacity-50 disabled:cursor-not-allowed hover:bg-[#33437e]/50 transition-all"
                >
                    <ChevronLeft class="w-4 h-4" />
                </button>

                <button
                    v-for="page in pageNumbers"
                    :key="page"
                    @click="goToPage(page)"
                    :class="[
                        'px-3 py-1 rounded-lg transition-all',
                        currentPage === page
                            ? 'bg-[#33437e] text-white'
                            : 'bg-[#121212]/80 border border-[#33437e] text-gray-300 hover:bg-[#33437e]/50'
                    ]"
                >
                    {{ page }}
                </button>

                <button
                    @click="nextPage"
                    :disabled="currentPage === totalPages"
                    class="p-2 rounded-lg bg-[#121212]/80 border border-[#33437e] text-white disabled:opacity-50 disabled:cursor-not-allowed hover:bg-[#33437e]/50 transition-all"
                >
                    <ChevronRight class="w-4 h-4" />
                </button>
            </div>
        </div>
    </div>
</template>
