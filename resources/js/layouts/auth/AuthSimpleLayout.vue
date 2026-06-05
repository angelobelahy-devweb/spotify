<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';
import backgroundImage from '@/assets/images/font_casque.png';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <!--class="flex min-h-svh flex-col items-center justify-center gap-6 bg-red-950 text-white p-6 md:p-10"-->
    <div
        
        class="
        flex
        flex-col items-center justify-center gap-6
        w-full
        h-screen
        overflow-y-auto
        rounded-[1rem_0_0_0]
        bg-cover
        bg-center
        "
        :style="{
          backgroundImage: `linear-gradient(#000, #060a18c5),url(${backgroundImage})`
        }"
    >
        <div class="p-5 bg-black/40 backdrop-blur-md rounded-xl">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link
                        :href="home()"
                        class="flex flex-col items-center gap-2 font-medium"
                    >
                        <div
                            class="mb-1 flex items-center justify-center rounded-md"
                        >
                            <AppLogoIcon
                                class="w-20"
                            />
                        </div>
                        <span class="sr-only">{{ title }}</span>
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium">{{ title }}</h1>
                        <p class="text-center text-sm text-muted-foreground text-zinc-400">
                            {{ description }}
                        </p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
