<script setup>
import { HeartIcon, Play } from 'lucide-vue-next'

defineProps({
    title: String,
    artist: String,
    image: String
})
</script>

<template>
    <div
    class="
    bg-[#33437e]/20
    border border-[#33437e]
    backdrop-blur-xl
    transition
    p-2
    rounded-xs
    "
>
        <div class="relative">
            <div class="relative">
                <img
                    :src="image"
                    class="
                    w-full
                    h-[150px]
                    object-cover
                    rounded-lg
                    "
                />
                <strong class="badge text-white bg-[#33437e]/80 px-2 text-xs absolute top-2 right-2">Dancehall</strong>
            </div>
            <button
                class="
                absolute
                bottom-3
                right-3
                bg-[#33437e]
                p-2
                rounded-full
                hover:scale-110
                active:scale-90
                transition-all
                cursor-pointer
                "
            >
                <Play class="text-white fill-white w-4 h-4" />
            </button>

        </div>
        <!-- Heart -->
        <button
            class="
            text-gray-400
            hover:text-[#fae311]
            transition-all
            mt-2
            cursor-pointer
            "
        >
            <HeartIcon class="fill-gray-400 text-gray-400 hover:text-[#fae311] hover:fill-[#fae311] w-5 h-5" />
        </button>
        <h2 class="text-white font-bold mt-4">
            {{ title }}
        </h2>

        <p class="text-gray-400 text-sm">
            {{ artist }}
        </p>
    </div>
</template>