<script setup lang="ts">
import PrimaryButton from '@/components/angelo/button/PrimaryButton.vue';
import MusicCard from '@/components/angelo/cards/MusicCard.vue';
// import {
//     BackwardIcon,
//     ForwardIcon,
//     PauseIcon,
//     SpeakerWaveIcon,
//     HeartIcon,
//     ArrowsRightLeftIcon
// } from '@heroicons/vue/24/solid';

</script>

<template>
    <!-- <PrimaryButton :loading="true">
        Sauvegarder
    </PrimaryButton> -->
    <div class="relative w-full min-h-screen">
        <div class="flex gap-2 my-2">
            <PrimaryButton size="xs">
                All
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9]" color="black">
                Top
            </PrimaryButton>
        </div>
        <!-- Music List -->
        <div class="flex flex-wrap items-center justify-between gap-5">
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        
        </div>
        <!-- music player -->
        <!-- PLAYER -->
         <!-- PLAYER -->
        <div
            class="
            fixed
            bottom-0
            left-0
            right-0
            z-50

            bg-[#121212]/95
            border-t
            border-white/10

            px-6
            py-4

            backdrop-blur-xl
            "
        >
            <div class="flex items-center justify-between">

                <!-- LEFT -->
                <div class="flex items-center gap-4">

                    <!-- Cover -->
                    <div
                        class="
                        w-16
                        h-16
                        overflow-hidden
                        rounded-lg
                        shadow-lg
                        "
                    >
                        <img
                            src="/assets/images/album.JPG"
                            class="w-full h-full object-cover"
                        />
                    </div>

                    <!-- Info -->
                    <div>
                        <h2 class="text-white font-bold">
                            Aza ambelanao
                        </h2>

                        <p class="text-gray-400 text-sm">
                            Rim ka
                        </p>
                    </div>

                    <!-- Heart -->
                    <button
                        class="
                        text-gray-400
                        hover:text-green-500
                        transition-all
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

                </div>

                <!-- CENTER -->
                <div class="flex flex-col items-center w-full max-w-xl">

                    <!-- Controls -->
                    <div class="flex items-center gap-5">

                        <!-- Previous -->
                        <button
                            class="
                            text-white
                            hover:scale-110
                            transition-all
                            "
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                class="w-6 h-6"
                            >
                                <path
                                    d="M6 6h2v12H6zm3.5 6L18 18V6z"
                                />
                            </svg>
                        </button>

                        <!-- Pause -->
                        <button
                            class="
                            bg-white
                            p-3
                            rounded-full

                            hover:scale-110
                            active:scale-95

                            transition-all
                            "
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="black"
                                viewBox="0 0 24 24"
                                class="w-6 h-6"
                            >
                                <path
                                    d="M6 5h4v14H6zm8 0h4v14h-4z"
                                />
                            </svg>
                        </button>

                        <!-- Next -->
                        <button
                            class="
                            text-white
                            hover:scale-110
                            transition-all
                            "
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                class="w-6 h-6"
                            >
                                <path
                                    d="M16 6h2v12h-2zm-10.5 6L14 6v12z"
                                />
                            </svg>
                        </button>

                    </div>

                    <!-- Progress -->
                    <div class="flex items-center gap-3 w-full mt-3">

                        <span class="text-xs text-gray-400">
                            1:24
                        </span>

                        <div
                            class="
                            w-full
                            h-1
                            bg-gray-700
                            rounded-full
                            overflow-hidden
                            "
                        >
                            <div
                                class="
                                w-[40%]
                                h-full
                                bg-white
                                rounded-full
                                "
                            ></div>
                        </div>

                        <span class="text-xs text-gray-400">
                            3:45
                        </span>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="flex items-center gap-3">

                    <!-- Volume -->
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                        class="w-5 h-5 text-white"
                    >
                        <path
                            d="M3 10v4h4l5 5V5L7 10H3zm13.5
                            2c0-1.77-1-3.29-2.5-4.03v8.05
                            c1.5-.73 2.5-2.25 2.5-4.02z"
                        />
                    </svg>

                    <!-- Volume bar -->
                    <div
                        class="
                        w-24
                        h-1
                        bg-gray-700
                        rounded-full
                        overflow-hidden
                        "
                    >
                        <div
                            class="
                            w-[70%]
                            h-full
                            bg-white
                            "
                        ></div>
                    </div>

                </div>

            </div>
        </div>
    
     </div>
</template>