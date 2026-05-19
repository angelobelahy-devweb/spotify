<script setup lang="ts">
import AlbumCard from '@/components/angelo/cards/AlbumCard.vue';
import { Link } from '@inertiajs/vue3'
</script>

<template>
     <h1 class="text-[#e4e8f3d0] text-2xl my-2">Albums</h1>
        <Link href="/albums/details" class="flex flex-wrap items-center justify-start gap-2">
            <AlbumCard
                title="My first album"
                artist="Rim ka"
                image="/assets/images/album.JPG"
            />
        </Link>
</template>