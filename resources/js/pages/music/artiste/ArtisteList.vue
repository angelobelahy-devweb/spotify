<script setup lang="ts">
import ArtisteCard from '@/components/angelo/cards/ArtisteCard.vue';
import { Link } from '@inertiajs/vue3'
import { Plus } from 'lucide-vue-next';
</script>

<template>
    <div class="flex gap-2 items-center">
        <h1 class="text-[#e4e8f3d0] text-2xl my-2">Artistes</h1>
        <Link href="/artistes/create" class="flex gap-2 items-center bg-[#121212]/80 border-2 border-[#33437e] rounded-full w-[max-content] p-1 hover:translate-y-1 transition-all duration-300">
            <div class="bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all duration-300 p-1 rounded-full text-sm font-bold flex items-center justify-center gap-2 w-6 h-6 cursor-pointer disabled:opacity-50">
                <Plus />
            </div>
            <span class="text-xs text-white uppercase">Créer</span>
        </Link>
    </div>
        <div class="flex flex-wrap items-center justify-start gap-2 ">
            <ArtisteCard
                artist="King Boy"
                image="/assets/images/user.jpg"
            />
            <ArtisteCard
                artist="Boy Black"
                image="/assets/images/user1.jpg"
            />
            <ArtisteCard
                artist="Basta Lion"
                image="/assets/images/user2.jpg"
            />

        </div>
</template>