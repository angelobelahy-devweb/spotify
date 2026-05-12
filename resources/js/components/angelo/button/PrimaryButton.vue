<script setup>
defineProps({
    loading: Boolean
})
</script>

<template>
    <button
        class="
        bg-[#057430]
        hover:bg-green-800
        active:scale-95
        transition
        px-6
        py-1
        rounded-full
        text-white
        text-sm
        font-bold
        flex
        items-center
        justify-center
        gap-2
        w-[max-content]
        cursor-pointer
        disabled:opacity-50
        "
        :disabled="loading"
    >
        <span v-if="loading">
            Chargement...
        </span>

        <slot v-else />
    </button>
</template>