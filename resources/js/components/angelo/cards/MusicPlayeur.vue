<script setup>
    import { ChevronFirst, ChevronLast } from 'lucide-vue-next';
</script>
<template>
    <!-- PLAYER -->
    <div
        class="
        

        p-2
        backdrop-blur-xl
        "
    >
        <div class="flex flex-col items-center justify-center">

            <!-- LEFT -->
            <div class="w-full flex flex-col items-center gap-4 mb-2">
                <!-- Cover -->
                <div class="w-full flex justify-between gap-2">
                    <div class="w-30 h-30 overflow-hidden rounded-full shadow-lg flex justify-center">
                        <img src="/assets/images/album.JPG" class="w-full h-full object-cover"/>
                    </div>
                    <!-- Heart -->
                    <button
                        class="
                        text-gray-400
                        hover:text-[#fae311]
                        transition-all
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

            </div>

            <!-- CENTER -->
            <div class="flex flex-col items-center w-full">

                <!-- Controls -->
                <div class="flex items-center gap-5">

                    <!-- Previous -->
                    <button
                        class="
                        text-white
                        hover:scale-110
                        transition-all
                        cursor-pointer
                        "
                    >
                        <ChevronFirst  class="w-5 h-5 text-gray-400"/>
                    </button>

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
                        cursor-pointer
                        "
                    >
                        <ChevronLast  class="w-5 h-5 text-gray-400"/>
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
                            w-[100%]
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
</template>