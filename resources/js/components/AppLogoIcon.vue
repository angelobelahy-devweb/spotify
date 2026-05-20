<script setup lang="ts">
import logo from '@/assets/images/logo.png';
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <img :src="logo" alt="Logo" v-bind="$attrs" class="h-auto w-40 object-contain mx-auto" />
</template>
