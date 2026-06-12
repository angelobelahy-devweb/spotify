<script setup>
import { HeartIcon, PlayIcon } from 'lucide-vue-next';
import PrimaryButton from '../button/PrimaryButton.vue';


defineProps({
    title: String,
    artist: String,
    image: String,
    year: String,
    totalSongs: Number
})

</script>

<template>

    <div
        class="
        flex
        flex-col
        md:flex-row
        gap-6
        items-center
        md:items-end

        bg-gradient-to-r
        from-[#33437e]/30
        to-transparent

        p-6
        rounded-xl
        "
    >

        <!-- IMAGE -->
        <img
            :src="image"
            class="
            w-[250px]
            h-[250px]
            object-cover

            shadow-2xl
            rounded-lg
            "
        >

        <!-- INFO -->
        <div class="flex-1">

            <p class="text-sm text-gray-300">
                Album
            </p>

            <h1
                class="
                text-white
                text-4xl
                md:text-6xl
                font-black
                mt-3
                "
            >
                {{ title }}
            </h1>

            <div
                class="
                flex
                flex-wrap
                items-center
                gap-2
                mt-4
                text-gray-300
                "
            >

                <span class="font-bold text-white">
                    {{ artist }}
                </span>

                <span>•</span>

                <span>
                    {{ year }}
                </span>

                <span>•</span>

                <span>
                    {{ totalSongs }} chansons
                </span>

            </div>

            <!-- BUTTONS -->
            <div class="flex items-center gap-4 mt-6">

                <PrimaryButton
                size="sm">
                    <PlayIcon class="fill-white text-white w-4 h-4" /> Lecture
                </PrimaryButton>

                <button
                    class="
                    border
                    border-white/20
                    flex gap-2 items-center
                    hover:border-white
                    transition
                    px-6
                    py-1
                    cursor-pointer
                    rounded-full
                    text-white
                    "
                >
                    <!-- Heart -->
                    <HeartIcon class="fill-gray-400 text-gray-400 w-4 h-4" /> Favorie
                </button>

            </div>

        </div>

    </div>

</template>