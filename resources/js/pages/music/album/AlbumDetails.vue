<script setup>

import AlbumHeader from '@/components/angelo/album/AlbumHeader.vue'
import AlbumSongs from '@/components/angelo/album/AlbumSongs.vue'

const songs = [

    {
        title: 'Blinding Lights',
        artist: 'The Weeknd',
        duration: '3:22',
        image: '/assets/images/24.JPG'
    },

    {
        title: 'Save Your Tears',
        artist: 'The Weeknd',
        duration: '3:10',
        image: '/assets/images/25.JPG'
    },

    {
        title: 'Starboy',
        artist: 'The Weeknd',
        duration: '4:01',
        image: '/assets/images/26.JPG'
    }

]

</script>

<template>

    <div
        class="
        min-h-screen
        bg-[#121212]/90
        rounded-xl
        p-4
        md:p-8
        "
    >

        <!-- HEADER -->
        <AlbumHeader
            title="After Hours"
            artist="The Weeknd"
            image="/assets/images/album.JPG"
            year="2020"
            :totalSongs="songs.length"
        />

        <!-- SONGS -->
        <AlbumSongs
            :songs="songs"
        />

    </div>

</template>