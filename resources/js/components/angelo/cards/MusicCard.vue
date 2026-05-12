<script setup>
import { Play } from 'lucide-vue-next'

defineProps({
    title: String,
    artist: String,
    image: String
})
</script>

<template>
    <div
        class="
        bg-[#212121]
        p-4
        rounded-xl
        hover:bg-[#212121d0]
        transition
        cursor-pointer
        group
        w-[200px]
        shadow-[0 2 20px red]
        "
    >
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

            <button
                class="
                absolute
                bottom-3
                right-3
                bg-green-500
                p-3
                rounded-full
                opacity-0
                group-hover:opacity-100
                transition
                cursor-pointer
                "
            >
                <Play class="text-black fill-black w-5 h-5" />
            </button>

        </div>

        <h2 class="text-white font-bold mt-4">
            {{ title }}
        </h2>

        <p class="text-gray-400 text-sm">
            {{ artist }}
        </p>
    </div>
</template>