<script setup>
import { Play, HeartIcon } from 'lucide-vue-next';

defineProps({
    index: Number,
    title: String,
    duration: String,
    image: String,
    artist: String,
    file_path: String // AJOUT : chemin du morceau
})

// Déclaration de l'événement vers le composant parent
const emit = defineEmits(['playTrack']);
</script>

<template>
    <div
        @click="emit('playTrack')"
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
        cursor-pointer
        "
    >
        <!-- NUMBER -->
        
        <div class="text-4xl font-thin opacity-30 tabular-nums">
            {{ index }}
        </div>

        

        <!-- SONG -->
        <div class="flex items-center gap-4">
            <img :src="image" class="w-14 h-14 rounded-md object-cover">
            <div>
                <h2 class="text-white font-semibold">
                    {{ title }}
                </h2>
                <p class="text-sm text-gray-400">
                    {{ artist }}
                </p>
            </div>
            <!-- Heart -->
            <button
                class="
                hover:text-gray-400
                text-[#fae311]
                transition-all
                cursor-pointer
                "
            >
                <HeartIcon class="hover:fill-gray-400 hover:text-gray-400 text-[#fae311] fill-[#fae311] w-5 h-5" />
            </button>
        </div>

        <!-- DURATION -->
        <div class="flex gap-2 items-center">
            <span class="text-gray-400">
                {{ duration }}
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
                w-[max-content]
                "
            >
                <Play class="text-white fill-white w-4 h-4" />
            </button>
        </div>
        
    </div>
</template>
