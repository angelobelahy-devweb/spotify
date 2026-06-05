<script setup>
import { Link } from '@inertiajs/vue3'
import { HeartIcon, Play } from 'lucide-vue-next';

defineProps({
    number: String,
    title: String,
    artist: String,
    image: String
})
</script>

<template>
    <Link href=""
        class="
        bg-[#33437e]/20
    border-2 border-[#33437e]
        hover:scale-[1.01]
        transition
        p-2
        rounded-sm
        group
        w-full
        shadow-[0_2_20px_red]
        hover:translate-y-1
        
        "
    >
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <strong class="text-gray-400 text-xs">{{ number }}</strong>
                <img
                    :src="image"
                    class="
                    w-[50px]
                    h-[50px]
                    object-cover
                    rounded-[5px]
                    "
                />
                <div class="flex flex-col gap-2">
                    <h2 class="text-white font-bold text-sm">
                        {{ title }}
                    </h2>
                    <p class="text-gray-400 text-xs">
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
    </Link>
</template>