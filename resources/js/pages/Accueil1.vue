<script setup lang="ts">
import PrimaryButton from '@/components/angelo/button/PrimaryButton.vue'
import MusicCard from '@/components/angelo/cards/MusicCard.vue'

import { ChevronLeft, ChevronRight } from 'lucide-vue-next'

/* SWIPER */
import { Swiper, SwiperSlide } from 'swiper/vue'

/* STYLE */
import 'swiper/css'
import 'swiper/css/navigation'

/* MODULE */
import { Navigation } from 'swiper/modules'

const modules = [Navigation]

/* DATA */

const nouveautes = [
    '/assets/images/1.JPG',
    '/assets/images/2.JPG',
    '/assets/images/3.JPG',
    '/assets/images/4.JPG',
    '/assets/images/5.JPG',
    '/assets/images/6.JPG',
    '/assets/images/8.JPG',
    '/assets/images/9.JPG',
]

const populaires = [
    '/assets/images/10.JPG',
    '/assets/images/11.JPG',
    '/assets/images/12.JPG',
    '/assets/images/13.JPG',
    '/assets/images/14.JPG',
    '/assets/images/15.JPG',
    '/assets/images/16.JPG',
]

const suggestions = [
    '/assets/images/18.JPG',
    '/assets/images/19.JPG',
    '/assets/images/20.JPG',
    '/assets/images/21.JPG',
    '/assets/images/22.JPG',
]

const genres = [
    'All',
    'Pop',
    'Rap',
    'Rock',
    'Metal',
    'Jazz',
    'Blues',
    'Musique Classique',
    'Dancehall',
    'Country',
]
</script>

<template>

    <div class="relative w-full min-h-screen">

        <!-- ===================== -->
        <!-- GENRES -->
        <!-- ===================== -->

        <div class="relative my-4">

            <!-- BUTTONS -->
            <button
                class="
                genre-prev

                absolute
                left-0
                top-1/2
                -translate-y-1/2

                z-10

                bg-black/70
                hover:bg-black

                p-2
                rounded-full
                "
            >
                <ChevronLeft class="text-white w-5 h-5" />
            </button>

            <button
                class="
                genre-next

                absolute
                right-0
                top-1/2
                -translate-y-1/2

                z-10

                bg-black/70
                hover:bg-black

                p-2
                rounded-full
                "
            >
                <ChevronRight class="text-white w-5 h-5" />
            </button>

            <!-- SWIPER -->
            <Swiper
                :slides-per-view="'auto'"
                :space-between="10"
                :modules="modules"
                :navigation="{
                    prevEl: '.genre-prev',
                    nextEl: '.genre-next'
                }"
                class="pr-10"
            >

                <SwiperSlide
                    v-for="genre in genres"
                    :key="genre"
                    class="!w-auto"
                >
                    <PrimaryButton
                        size="xs"
                        class="bg-[#c8cac9] hover:text-white"
                        color="black"
                    >
                        {{ genre }}
                    </PrimaryButton>
                </SwiperSlide>

            </Swiper>

        </div>

        <!-- ===================== -->
        <!-- NOUVEAUTES -->
        <!-- ===================== -->

        <div class="mt-8">

            <div class="flex items-center justify-between mb-4">

                <h1 class="text-[#e4e8f3d0] text-2xl">
                    Nouveautés
                </h1>

                <div class="flex items-center gap-2">

                    <button
                        class="
                        nouveaute-prev

                        bg-[#212121]
                        hover:bg-[#313131]

                        p-2
                        rounded-full
                        "
                    >
                        <ChevronLeft class="text-white w-5 h-5" />
                    </button>

                    <button
                        class="
                        nouveaute-next

                        bg-[#212121]
                        hover:bg-[#313131]

                        p-2
                        rounded-full
                        "
                    >
                        <ChevronRight class="text-white w-5 h-5" />
                    </button>

                </div>

            </div>

            <Swiper
                :modules="modules"
                :space-between="15"

                :navigation="{
                    prevEl: '.nouveaute-prev',
                    nextEl: '.nouveaute-next'
                }"

                :breakpoints="{
                    0: {
                        slidesPerView: 1.2
                    },
                    640: {
                        slidesPerView: 2.2
                    },
                    768: {
                        slidesPerView: 3.2
                    },
                    1024: {
                        slidesPerView: 4.2
                    },
                    1280: {
                        slidesPerView: 5.2
                    }
                }"
            >

                <SwiperSlide
                    v-for="(image,index) in nouveautes"
                    :key="index"
                >

                    <MusicCard
                        title="Aza ambelanao"
                        artist="Rim ka"
                        :image="image"
                    />

                </SwiperSlide>

            </Swiper>

        </div>

        <!-- ===================== -->
        <!-- POPULAIRES -->
        <!-- ===================== -->

        <div class="mt-10">

            <div class="flex items-center justify-between mb-4">

                <h1 class="text-[#e4e8f3d0] text-2xl">
                    Populaires
                </h1>

                <div class="flex items-center gap-2">

                    <button
                        class="
                        populaire-prev

                        bg-[#212121]
                        hover:bg-[#313131]

                        p-2
                        rounded-full
                        "
                    >
                        <ChevronLeft class="text-white w-5 h-5" />
                    </button>

                    <button
                        class="
                        populaire-next

                        bg-[#212121]
                        hover:bg-[#313131]

                        p-2
                        rounded-full
                        "
                    >
                        <ChevronRight class="text-white w-5 h-5" />
                    </button>

                </div>

            </div>

            <Swiper
                :modules="modules"
                :space-between="15"

                :navigation="{
                    prevEl: '.populaire-prev',
                    nextEl: '.populaire-next'
                }"

                :breakpoints="{
                    0: {
                        slidesPerView: 1.2
                    },
                    640: {
                        slidesPerView: 2.2
                    },
                    768: {
                        slidesPerView: 3.2
                    },
                    1024: {
                        slidesPerView: 4.2
                    },
                    1280: {
                        slidesPerView: 5.2
                    }
                }"
            >

                <SwiperSlide
                    v-for="(image,index) in populaires"
                    :key="index"
                >

                    <MusicCard
                        title="Aza ambelanao"
                        artist="Rim ka"
                        :image="image"
                    />

                </SwiperSlide>

            </Swiper>

        </div>

        <!-- ===================== -->
        <!-- SUGGESTIONS -->
        <!-- ===================== -->

        <div class="mt-10 pb-20">

            <div class="flex items-center justify-between mb-4">

                <h1 class="text-[#e4e8f3d0] text-2xl">
                    Suggestions
                </h1>

                <div class="flex items-center gap-2">

                    <button
                        class="
                        suggestion-prev

                        bg-[#212121]
                        hover:bg-[#313131]

                        p-2
                        rounded-full
                        "
                    >
                        <ChevronLeft class="text-white w-5 h-5" />
                    </button>

                    <button
                        class="
                        suggestion-next

                        bg-[#212121]
                        hover:bg-[#313131]

                        p-2
                        rounded-full
                        "
                    >
                        <ChevronRight class="text-white w-5 h-5" />
                    </button>

                </div>

            </div>

            <Swiper
                :modules="modules"
                :space-between="15"

                :navigation="{
                    prevEl: '.suggestion-prev',
                    nextEl: '.suggestion-next'
                }"

                :breakpoints="{
                    0: {
                        slidesPerView: 1.2
                    },
                    640: {
                        slidesPerView: 2.2
                    },
                    768: {
                        slidesPerView: 3.2
                    },
                    1024: {
                        slidesPerView: 4.2
                    },
                    1280: {
                        slidesPerView: 5.2
                    }
                }"
            >

                <SwiperSlide
                    v-for="(image,index) in suggestions"
                    :key="index"
                >

                    <MusicCard
                        title="Aza ambelanao"
                        artist="Rim ka"
                        :image="image"
                    />

                </SwiperSlide>

            </Swiper>

        </div>

    </div>

</template>