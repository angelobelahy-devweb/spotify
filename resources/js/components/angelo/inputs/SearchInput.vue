<script setup>
import { Search } from 'lucide-vue-next'
</script>

<template>
    <div
        class="
        flex
        items-center
        bg-black/20
        border border-[#33437e]
        backdrop-blur-xl
        rounded-full
        px-2
        py-2
        "
    >
        <Search class="w-5 h-5 text-gray-400" />

        <input
            type="text"
            placeholder="Que souhaitez-vous écouter ?"
            class="
            bg-transparent
            outline-none
            text-white
            text-xs
            ml-3
            w-[160px]
            "
        />
    </div>
</template>