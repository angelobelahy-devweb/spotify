<script setup>
import { usePage, Link, router } from '@inertiajs/vue3'
import ArtisteCard from '@/components/angelo/cards/ArtisteCard.vue';
import Pagination from '@/components/Pagination.vue'
import { Plus, Search, CheckCircle, AlertTriangle, Clock, X } from 'lucide-vue-next'
import { ref, watch, computed } from 'vue'

const props = defineProps({
    artists: Object,   // Laravel paginator object
    isArtist: Boolean,
    isSubscriptionActive: Boolean,
    filters: Object,
})

const page = usePage()
const userPlan = computed(() => page.props.auth?.user?.pm_type || 'free')
const artistStatus = computed(() => page.props.auth?.user?.artist?.status || null)

const searchQuery = ref(props.filters?.search || '')

// Debounce search → backend request
let debounceTimer = null
watch(searchQuery, (val) => {
    clearTimeout(debounceTimer)
    debounceTimer = setTimeout(() => {
        router.get('/artistes', { search: val || undefined }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }, 350)
})

const clearSearch = () => {
    searchQuery.value = ''
}
</script>

<script>
// Nettoyé de l'import en doublon
export default { name: 'ArtisteList' }
</script>

<template>
    <div class="space-y-6">

        <div class="flex justify-between items-center gap-4 flex-wrap">

            <div class="flex gap-3 items-center flex-wrap">
                <h1 class="text-[#e4e8f3d0] text-2xl font-bold">Artistes</h1>

                <div v-if="isArtist && isSubscriptionActive && artistStatus === 'approved' && userPlan === 'free'" class="flex gap-4 items-center flex-wrap">
                    <div class="flex gap-2 items-center bg-[#121212]/80 border-2 border-emerald-600 rounded-full p-1">
                        <div class="bg-emerald-600 p-1 rounded-full flex items-center justify-center w-6 h-6">
                            <CheckCircle class="w-4 h-4 text-white" />
                        </div>
                        <span class="text-xs text-emerald-400 uppercase pr-1">Artiste (Plan Free)</span>
                    </div>

                    <Link
                        href="/subscription"
                        class="text-xs bg-[#33437e] hover:bg-[#364a92] text-white font-bold uppercase px-4 py-2 rounded-full transition-all duration-300 shadow-md shadow-[#33437e]/20"
                    >
                        Changer de forfait
                    </Link>
                </div>

                <div v-else-if="isArtist && isSubscriptionActive && artistStatus === 'approved' && userPlan !== 'free'" class="flex gap-3 items-center flex-wrap">
                    <div class="flex gap-2 items-center bg-[#121212]/80 border-2 border-emerald-600 rounded-full p-1">
                        <div class="bg-emerald-600 p-1 rounded-full flex items-center justify-center w-6 h-6">
                            <CheckCircle class="w-4 h-4 text-white" />
                        </div>
                        <span class="text-xs text-emerald-400 uppercase pr-1">Artiste {{ userPlan }} Certifié</span>
                    </div>
                    <span class="text-xs text-gray-500 italic bg-white/5 border border-white/10 px-3 py-1.5 rounded-full">
                        Forfait verrouillé pour 1 mois (Engagement en cours)
                    </span>
                </div>

                <Link
                    v-else-if="isArtist && artistStatus === 'pending'"
                    href="/subscription/pending"
                    class="flex gap-2 items-center bg-[#121212]/80 cursor-pointer border-2 border-amber-500 rounded-full p-1"
                >
                    <div class="bg-amber-500 p-1 rounded-full flex items-center justify-center w-6 h-6 animate-spin">
                        <Clock class="w-4 h-4 text-white" />
                    </div>
                    <span class="text-xs text-amber-400 uppercase pr-1">Profil en cours d'examen</span>
                </Link>

                <Link
                    v-else-if="isArtist && artistStatus === 'rejected'"
                    href="/subscription/rejected"
                    class="flex gap-2 items-center cursor-pointer bg-[#121212]/80 border-2 border-red-600 rounded-full p-1 hover:scale-105 transition-all"
                >
                    <div class="bg-red-600 p-1 rounded-full flex items-center justify-center w-6 h-6">
                        <AlertTriangle class="w-4 h-4 text-white" />
                    </div>
                    <span class="text-xs text-red-400 uppercase pr-1">Profil Refusé — Voir les détails</span>
                </Link>

                <Link
                    v-else-if="isArtist && !isSubscriptionActive"
                    href="/subscription"
                    class="flex gap-2 items-center cursor-pointer bg-[#121212]/80 border-2 border-amber-600 rounded-full p-1 hover:translate-y-0.5 transition-all duration-300"
                >
                    <div class="bg-amber-600 p-1 rounded-full flex items-center justify-center w-6 h-6">
                        <AlertTriangle class="w-4 h-4 text-white" />
                    </div>
                    <span class="text-xs text-amber-400 uppercase pr-1">Renouveler abonnement</span>
                </Link>

                <Link
                    v-else
                    href="/subscription"
                    class="flex gap-2 items-center bg-[#121212]/80 border-2 border-[#33437e] rounded-full p-1 hover:translate-y-0.5 transition-all duration-300"
                >
                    <div class="bg-[#33437e] p-1 rounded-full flex items-center justify-center w-6 h-6">
                        <Plus class="w-4 h-4 text-white" />
                    </div>
                    <span class="text-xs text-white uppercase pr-1">Devenir artiste</span>
                </Link>
            </div>

            <div class="relative w-full md:w-72">
                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    <Search :size="16" class="text-[#6b7bb8]" />
                </div>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Rechercher un artiste..."
                    class="w-full bg-[#1a1a26] border border-[#2a2a3e] rounded-full py-2.5 pl-10 pr-9 text-sm text-white placeholder-[#6b7bb8] focus:outline-none focus:border-[#4a5fc7] transition-all duration-300"
                />
                <button
                    v-if="searchQuery"
                    @click="clearSearch"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-white transition-colors"
                >
                    <X :size="14" />
                </button>
            </div>
        </div>

        <p v-if="searchQuery" class="text-sm text-gray-500">
            {{ artists?.total || 0 }} résultat{{ artists?.total !== 1 ? 's' : '' }} pour
            <span class="text-white font-medium">"{{ searchQuery }}"</span>
        </p>

        <div
            v-if="!artists?.data || artists.data.length === 0"
            class="flex flex-col items-center justify-center py-20 text-center space-y-3"
        >
            <Search :size="36" class="text-[#33437e]" />
            <p class="text-white font-semibold">Aucun artiste trouvé</p>
            <p class="text-sm text-gray-500">Essayez un autre terme de recherche.</p>
        </div>

        <div v-else-if="artists?.data && artists.data.length > 0" class="flex flex-wrap items-start justify-start gap-3">
            <ArtisteCard
                v-for="artist in artists.data"
                :key="artist.id"
                :slug="artist.user.slug"
                :artist="artist.surname"
                :image="`/storage/${artist.user.pdp}`"
            />
        </div>

        <Pagination :pagination="artists" />

    </div>
</template>
