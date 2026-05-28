<script setup>
    import { ChevronFirst, ChevronLast, HeartIcon, PauseIcon } from 'lucide-vue-next';

</script>
<template>
    <!-- PLAYER -->
    <div
        class="
        bg-[#121212]/50
        border-t
        border-white/10
        p-2
        backdrop-blur-xl
        "
    >
        <div class="flex items-center justify-between">

            <!-- LEFT -->
            <div class="w-full flex justify-start items-center gap-4">
                <!-- Cover -->
                <div class="w-16 h-16 overflow-hidden rounded-lg shadow-lg flex justify-center">
                    <img src="/assets/images/album.JPG" class="w-full h-full object-cover"/>
                </div>
                <!-- Heart -->
                <button
                    class="
                    text-gray-400
                    hover:text-[#ab072e]
                    transition-all
                    cursor-pointer
                    "
                >
                    <HeartIcon class="fill-gray-400 text-gray-400 hover:text-[#fae311] hover:fill-[#fae311] w-5 h-5" />
                </button>
                <!-- Info -->
                <div class="flex flex-col gap-1">
                    <h2 class="text-white font-bold">
                        Aza ambelanao
                    </h2>

                    <p class="text-gray-400 text-sm">
                        Rim ka
                    </p>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-3">

                <!-- Pause -->
                <button
                    class="
                    bg-[#33437e]
                    p-3
                    rounded-full
                    fill-white
                    hover:scale-110
                    active:scale-95
                    transition-all
                    cursor-pointer
                    "
                >
                    <PauseIcon class="w-6 h-6 text-gray-400 fill-gray-400"/>
                </button>

            </div>

        </div>
    </div>
</template>