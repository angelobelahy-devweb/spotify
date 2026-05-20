<script setup>
import { Link } from '@inertiajs/vue3'


defineProps({
    artist: String,
    image: String
})
</script>

<template>
    <Link href=""
        class="
        hover:bg-[#33437e]/40
        transition
        p-2
        rounded-xl
        group
        w-[100px]
        shadow-[0_2_20px_red]
        hover:translate-y-1
        flex flex-col items-center
        gap-2
        "
    >
        <img
            :src="image"
            class="
            w-[80px]
            h-[80px]
            object-cover
            rounded-full
            "
        />

        <p class="badge text-white bg-[#00000054] px-2 text-xs">
            {{ artist }}
        </p>
    </Link>
</template>