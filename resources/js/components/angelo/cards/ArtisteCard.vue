<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    slug: [String, Number],
    artist: String,
    image: String
})
</script>

<template>
    <Link
        :href="`/artist/profil/${slug}`"
        class="group block overflow-hidden rounded-3xl border border-white/10 bg-linear-to-br from-[#111827] via-[#101622] to-[#0f1220] p-4 text-center shadow-[0_18px_50px_rgba(0,0,0,0.35)] transition-transform duration-300 hover:-translate-y-1"
    >
        <div class="relative mb-4 overflow-hidden rounded-3xl border border-[#33437e]/40 bg-[#0f1322]">
            <img :src="image" class="h-30 w-full object-cover transition duration-300 group-hover:scale-105" />
            <div class="pointer-events-none absolute inset-0 bg-linear-to-t from-black/60 via-transparent to-transparent"></div>
        </div>

        <p class="text-sm font-semibold text-white line-clamp-1">{{ artist }}</p>
        <span class="mt-2 inline-flex items-center justify-center rounded-full bg-white/5 px-3 py-1 text-[11px] uppercase tracking-[0.18em] text-[#a9b5ff] transition-colors duration-300 group-hover:bg-[#33437e]/20">
            Voir le profil
        </span>
    </Link>
</template>
