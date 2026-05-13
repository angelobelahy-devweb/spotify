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
        <!-- Heart -->
        <button
            class="
            text-gray-400
            hover:text-green-500
            transition-all
            mt-2
            cursor-pointer
            "
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="currentColor"
                viewBox="0 0 24 24"
                class="w-5 h-5"
            >
                <path
                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                    2 5.42 4.42 3 7.5 3c1.74 0 3.41.81
                    4.5 2.09C13.09 3.81 14.76 3
                    16.5 3 19.58 3 22 5.42
                    22 8.5c0 3.78-3.4 6.86-8.55
                    11.54L12 21.35z"
                />
            </svg>
        </button>
        <h2 class="text-white font-bold mt-4">
            {{ title }}
        </h2>

        <p class="text-gray-400 text-sm">
            {{ artist }}
        </p>
    </div>
</template>