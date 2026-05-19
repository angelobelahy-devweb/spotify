<script setup lang="ts">

import MusicCard from '@/components/angelo/cards/MusicCard.vue'

import {
    ChevronLeft,
    ChevronRight
} from 'lucide-vue-next'

/* SWIPER */
import {
    Swiper,
    SwiperSlide
} from 'swiper/vue'

/* CSS */
import 'swiper/css'
import 'swiper/css/navigation'

/* MODULE */
import { Navigation } from 'swiper/modules'

const musics = [
    '/assets/images/1.JPG',
    '/assets/images/2.JPG',
    '/assets/images/3.JPG',
    '/assets/images/4.JPG',
    '/assets/images/5.JPG',
    '/assets/images/6.JPG',
    '/assets/images/7.JPG',
    '/assets/images/8.JPG',
]

</script>

<template>

    <div class="w-full py-10">

        <!-- HEADER -->
        <div
            class="
            flex
            items-center
            justify-between
            mb-6
            "
        >

            <h1
                class="
                text-white
                text-2xl
                font-bold
                "
            >
                Nouveautés
            </h1>

            <!-- NAVIGATION -->
            <div class="flex items-center gap-3">

                <button
                    class="
                    swiper-prev

                    w-10
                    h-10

                    rounded-full

                    bg-[#212121]
                    hover:bg-[#313131]

                    flex
                    items-center
                    justify-center
                    "
                >
                    <ChevronLeft class="w-5 h-5 text-white" />
                </button>

                <button
                    class="
                    swiper-next

                    w-10
                    h-10

                    rounded-full

                    bg-[#212121]
                    hover:bg-[#313131]

                    flex
                    items-center
                    justify-center
                    "
                >
                    <ChevronRight class="w-5 h-5 text-white" />
                </button>

            </div>

        </div>

        <!-- SWIPER -->
        <Swiper

            :modules="[Navigation]"

            :navigation="{
                prevEl: '.swiper-prev',
                nextEl: '.swiper-next'
            }"

            :space-between="20"

            :slidesPerView="4"

            :breakpoints="{
                0: {
                    slidesPerView: 1
                },

                640: {
                    slidesPerView: 2
                },

                768: {
                    slidesPerView: 3
                },

                1024: {
                    slidesPerView: 4
                }
            }"

        >

            <SwiperSlide
                v-for="(music,index) in musics"
                :key="index"
            >

                <MusicCard
                    title="Aza ambelanao"
                    artist="Rim ka"
                    :image="music"
                />

            </SwiperSlide>

        </Swiper>

    </div>

</template>