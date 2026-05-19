<script setup>
import { Play } from 'lucide-vue-next';


defineProps({
    index: Number,
    title: String,
    duration: String,
    image: String,
    artist: String
})

</script>

<template>

    <div
        class="
        grid
        grid-cols-[40px_1fr_auto]

        items-center

        gap-4

        p-3

        hover:bg-white/5

        rounded-lg

        transition
        group
        "
    >

        <!-- NUMBER -->
        <span
            class="
            text-gray-400
            group-hover:hidden
            "
        >
            {{ index }}
        </span>

        <!-- PLAY -->
        <button
            class="
            bg-[#33437e]
            p-2
            rounded-full
            hover:scale-110
            active:scale-90
            transition-all
            cursor-pointer
            hidden
            group-hover:block
            w-[max-content]
            "
        >
            <Play class="text-white fill-white w-4 h-4" />
        </button>

        <!-- SONG -->
        <div class="flex items-center gap-4">

            <img
                :src="image"
                class="
                w-14
                h-14
                rounded-md
                object-cover
                "
            >

            <div>

                <h2
                    class="
                    text-white
                    font-semibold
                    "
                >
                    {{ title }}
                </h2>

                <p
                    class="
                    text-sm
                    text-gray-400
                    "
                >
                    {{ artist }}
                </p>

            </div>

        </div>

        <!-- DURATION -->
        <span class="text-gray-400">
            {{ duration }}
        </span>

    </div>

</template>