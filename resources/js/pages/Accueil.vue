<script setup>


import PrimaryButton from '@/components/angelo/button/PrimaryButton.vue';
import MusicCard from '@/components/angelo/cards/MusicCard.vue';

import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Navigation, injectCarousel } from 'vue3-carousel'
import { ArrowLeft, Disc3Icon, LucideAlbum, Music, Tags } from 'lucide-vue-next';
defineProps({
  genres: Array,
});
const musics = [
  {
    id: 1,
    title: 'Aza ambelanao',
    artist: 'Rim ka',
    image: '/assets/images/1.JPG'
  },
  {
    id: 2,
    title: 'Song 2',
    artist: 'Artist 2',
    image: '/assets/images/2.JPG'
  },
  {
    id: 3,
    title: 'Song 3',
    artist: 'Artist 3',
    image: '/assets/images/3.JPG'
  },
  {
    id: 4,
    title: 'Song 4',
    artist: 'Artist 4',
    image: '/assets/images/4.JPG'
  },
  {
    id: 5,
    title: 'Aza ambelanao',
    artist: 'Rim ka',
    image: '/assets/images/5.JPG'
  },
  {
    id: 6,
    title: 'Song 6',
    artist: 'Artist 6',
    image: '/assets/images/6.JPG'
  },
  {
    id: 8,
    title: 'Song 8',
    artist: 'Artist 8',
    image: '/assets/images/8.JPG'
  },
  {
    id: 9,
    title: 'Song 9',
    artist: 'Artist 9',
    image: '/assets/images/9.JPG'
  }
]


const config = {
  height: 300,
//   itemsToShow: 3,
  gap: 5,
  itemsToShow: 6,
//   gap: 10,
//   wrapAround: true,
  breakpoints: {
    640: {
      itemsToShow: 3,
    },
    1024: {
      itemsToShow: 5,
    },
  },
}


</script>



<template>

    <div class="relative min-h-screen ">
        <div class="relative w-[max-content]">
            <select class="select select-[#33437e] cursor-pointer bg-[#33437e] select-sm outline-none border-none">
                <!-- Option par défaut (Grise et discrète) -->
                <option value="" disabled selected>
                    Filtrer par genre
                </option>
                <!-- Options de la liste (Blanches et lisibles) -->
                <option class="bg-[#33437e]/80">
                    All
                </option>
                <option v-for="genre in genres" :key="genre.id" :value="genre.id" class="bg-[#33437e]/80 text-zinc-100 font-sans text-sm tracking-wide antialiased">
                    {{ genre.name }}
                </option>
            </select>
        </div>
        <!-- Music List -->
        <h1 class="text-[#e4e8f3d0] text-2xl my-2">Nouveauté</h1>
        <Carousel v-bind="config">
            <Slide v-for="music in musics" :key="music.id">
                <MusicCard
                    :title="music.title"
                    :artist="music.artist"
                    :image="music.image"
                    class="w-full"
                />
            </Slide>
            <template #addons>
            <Navigation />
            </template>
        </Carousel>
        <!-- Music List -->
         <h1 class="text-[#e4e8f3d0] text-2xl my-2">Populaires</h1>
        <!-- Je veux que ceci est un carousel avec des bouttons droite et gauche -->
        <div class="flex flex-wrap items-center justify-start gap-2 ">
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/18.JPG"
                class="w-[200px]"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/19.JPG"
                class="w-[200px]"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/20.JPG"
                class="w-[200px]"
            />
        </div>
        <!-- Music List -->
         <h1 class="text-[#e4e8f3d0] text-2xl my-2">Suggestions</h1>
        <!-- Je veux que ceci est un carousel avec des bouttons droite et gauche -->
        <div class="flex flex-wrap items-center justify-start gap-2 ">

            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/21.JPG"
                class="w-[200px]"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/22.JPG"
                class="w-[200px]"
            />
        </div>
     </div>
</template>

<style scoped>
/* Permet aux slides de prendre uniquement la largeur de leur bouton */
.carousel {
  --vc-nav-background: rgba(255, 255, 255, 0.7);
  --vc-nav-border-radius: 100%;
}
img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
