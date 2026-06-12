<script setup>
<<<<<<< HEAD
import SongItem from './SongItem.vue'
import { playerStore } from '@/lib/playerStore'; // AJOUT : Import du store global

defineProps({
    songs: Array
})
=======
import SongItem from './SongItem.vue';
import { playerStore } from '@/lib/playerStore';

const props = defineProps({
    songs: Array
})

const handlePlayTrack = (song) => {
    playerStore.play(song, props.songs);
};
>>>>>>> develop
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
        <!-- AJOUT : écoute de @play-track et transmission de :file_path -->
        <SongItem
            v-for="(song, index) in songs"
            :key="song.id || song.title"
            :index="index + 1"
            :title="song.title"
            :artist="song.artist"
            :duration="song.duration"
            :image="song.image"
            :file_path="song.file_path"
<<<<<<< HEAD
            @play-track="playerStore.play(song)"
=======
            @play-track="handlePlayTrack(song)"
>>>>>>> develop
        />
    </div>
</template>
