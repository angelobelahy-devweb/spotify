<script setup>
import ArtisteCard from '@/components/angelo/cards/ArtisteCard.vue';
import { Link } from '@inertiajs/vue3'
import { Plus, Search, CheckCircle, AlertTriangle  } from 'lucide-vue-next';
import { ref } from 'vue'

const props = defineProps({
    artists: Array,
    isArtist: Boolean,
    isSubscriptionActive: Boolean,
})

const searchQuery = ref('')
</script>

<template>
    <div class="flex justify-between items-center mb-4">
        <div class="flex gap-2 items-center">
            <h1 class="text-[#e4e8f3d0] text-2xl my-2">Artistes</h1>
            <!-- ✅ Case 1: Artist + active subscription -->
            <div
                v-if="isArtist && isSubscriptionActive"
                class="flex gap-2 items-center bg-[#121212]/80 border-2 border-emerald-600 rounded-full w-[max-content] p-1"
            >
                <div class="bg-emerald-600 p-1 rounded-full flex items-center justify-center w-6 h-6">
                    <CheckCircle class="w-4 h-4 text-white" />
                </div>
                <span class="text-xs text-emerald-400 uppercase pr-1">Vous êtes artiste</span>
            </div>

            <!-- ✅ Case 2: Artist but subscription expired → renew button -->
            <Link
                v-else-if="isArtist && !isSubscriptionActive"
                href="/subscription"
                class="flex gap-2 items-center bg-[#121212]/80 border-2 border-amber-600 rounded-full w-[max-content] p-1 hover:translate-y-1 transition-all duration-300"
            >
                <div class="bg-amber-600 p-1 rounded-full flex items-center justify-center w-6 h-6">
                    <AlertTriangle class="w-4 h-4 text-white" />
                </div>
                <span class="text-xs text-amber-400 uppercase pr-1">Renouveler abonnement</span>
            </Link>

            <!-- ✅ Case 3: Not an artist yet → become artist button -->
            <Link
                v-else
                href="/subscription"
                class="flex gap-2 items-center bg-[#121212]/80 border-2 border-[#33437e] rounded-full w-[max-content] p-1 hover:translate-y-1 transition-all duration-300"
            >
                <div class="bg-[#33437e] hover:bg-[#364a92] transition-all duration-300 p-1 rounded-full flex items-center justify-center w-6 h-6">
                    <Plus class="w-4 h-4 text-white" />
                </div>
                <span class="text-xs text-white uppercase pr-1">Devenir artiste</span>
            </Link>
        </div>
        <!-- Search Bar -->
        <div class="relative w-full md:w-72">
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                <Search :size="18" class="text-[#6b7bb8]" />
            </div>
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Rechercher un artiste..."
                class="w-full bg-[#1a1a26] border border-[#2a2a3e] rounded-full py-2.5 pl-10 pr-4 text-sm text-white placeholder-[#6b7bb8] focus:outline-none focus:border-[#4a5fc7] focus:ring-1 focus:ring-[#4a5fc7] transition-all duration-300"
            />
        </div>
    </div>
    <div class="flex flex-wrap items-center justify-start gap-2">
        <div v-for="artist in artists"  :key="artist.id">
                <ArtisteCard
                    :slug="artist.user.slug"
                    :artist="artist.surname"
                    :image="`/storage/${artist.user.pdp}`"
                />
        </div>
    </div>
</template>
