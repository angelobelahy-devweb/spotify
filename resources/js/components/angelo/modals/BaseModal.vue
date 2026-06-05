<script setup>
import { defineEmits, defineProps } from 'vue'
import logo from '@/assets/images/logo.png'
const emits = defineEmits(['close-modal'])
    

    const closeModal = () => {
        emits('close-modal', false);
    }
</script>

<template>

    <div
        class="
        fixed
        inset-0
        bg-black/90
        backdrop-blur-md
        flex
        items-center
        justify-center
        z-50
        "
    >

        <div
            class="
            backdrop-blur-md bg-[#121212]/40
            p-6
            rounded-2xl
            w-[400px]
            "
        >

            <div class="flex justify-between mb-5">

                <div class="text-white text-xl font-bold">
                    <img :src="logo" alt="logo" class="w-10">
                </div>

                <button
                    @click="emits('close-modal')"
                    class="text-white cursor-pointer w-8 h-8 rounded-[5px] hover:bg-red-600 bg-[#ab072e]"
                >
                    X
                </button>

            </div>

            <slot />

        </div>

    </div>

</template>