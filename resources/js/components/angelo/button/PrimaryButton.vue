<script setup lang="ts">
import { computed } from 'vue';
const props = defineProps({
    loading: Boolean,
    size: {
        type: String,
        default: 'md',
    },
    color: {
        type: String,
        default: 'white',
    },

    type: {
        type: String,
        default: 'button',
    },
})

const sizeClasses = computed(() => {
    switch (props.size) {
        case 'xs':
            return 'px-2 py-1 text-xs';
        case 'sm':
            return 'px-4 py-1 text-sm';
        case 'lg':
            return 'px-8 py-3 text-lg';

        default:
            return 'px-4 py-1 text-sm text-base';
    }
});
const colorClasses = computed(() => {
    switch (props.color) {
        case 'white':
            return 'text-white';
        case 'black':
            return 'text-black';
        case 'blue':
            return 'text-[#364a92]';
        default:
            return 'text-white';
    }
});
</script>

<template>
    <button
        :type="type"
        :disabled="loading"
        :class="[
            sizeClasses,
            colorClasses
        ]"
        class="
        bg-[#33437e]
        hover:bg-[#364a92]
        active:scale-95
        transition-all
        duration-300
        px-6
        py-2
        rounded-full
        text-sm
        font-bold
        flex
        items-center
        justify-center
        gap-2
        w-max
        cursor-pointer
        disabled:opacity-50
        "
    >
        <span v-if="loading">
            Chargement...
        </span>

        <slot v-else />
    </button>
</template>