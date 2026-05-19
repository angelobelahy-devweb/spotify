<script setup>

import SongItem from './SongItem.vue'

defineProps({
    songs: Array
})

</script>

<template>

    <div class="mt-8">

        <!-- HEADER -->
        <div
            class="
            grid
            grid-cols-[40px_1fr_auto]

            text-gray-400

            border-b
            border-white/10

            pb-3
            mb-4
            "
        >

            <span>#</span>

            <span>Titre</span>

            <span>Durée</span>

        </div>

        <!-- SONGS -->
        <SongItem
            v-for="(song,index) in songs"
            :key="song.title"

            :index="index + 1"
            :title="song.title"
            :artist="song.artist"
            :duration="song.duration"
            :image="song.image"
        />

    </div>

</template>