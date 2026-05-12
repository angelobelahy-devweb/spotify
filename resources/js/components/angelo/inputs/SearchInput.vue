<script setup>
import { Search } from 'lucide-vue-next'
</script>

<template>
    <div
        class="
        flex
        items-center
        bg-[#242424]
        rounded-full
        px-4
        py-3
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
            ml-3
            w-full
            "
        />
    </div>
</template>