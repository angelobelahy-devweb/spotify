<script setup>


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
        group
        w-[200px]
        shadow-[0_2_20px_red]
        hover:translate-y-1
        
        "
    >
        <div class="relative">
            <img
                :src="image"
                class="
                w-full
                h-[150px]
                object-cover
                "
            />

        </div>
        <h2 class="text-white font-bold mt-4">
            {{ title }}
        </h2>

        <p class="text-gray-400 text-sm">
            Album . {{ artist }}
        </p>
    </div>
</template>