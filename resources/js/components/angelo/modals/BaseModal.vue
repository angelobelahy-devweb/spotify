<script setup>
defineProps({
    isOpen: Boolean
})

const emits = defineEmits(['close'])
</script>

<template>

    <div
        v-if="isOpen"
        class="
        fixed
        inset-0
        bg-black/70
        flex
        items-center
        justify-center
        z-50
        "
    >

        <div
            class="
            bg-[#181818]
            p-6
            rounded-2xl
            w-[400px]
            "
        >

            <div class="flex justify-between mb-5">

                <h2 class="text-white text-xl font-bold">
                    Modal
                </h2>

                <button
                    @click="emits('close')"
                    class="text-white"
                >
                    X
                </button>

            </div>

            <slot />

        </div>

    </div>

</template>