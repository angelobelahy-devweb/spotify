<script setup lang="ts">
import ArtisteCard from '@/components/angelo/cards/ArtisteCard.vue';

</script>

<template>
     <h1 class="text-[#e4e8f3d0] text-2xl my-2">Artistes</h1>
        <div class="flex flex-wrap items-center justify-start gap-2 ">
            <ArtisteCard
                artist="King Boy"
                image="/assets/images/user.jpg"
            />
            <ArtisteCard
                artist="Boy Black"
                image="/assets/images/user1.jpg"
            />
            <ArtisteCard
                artist="Basta Lion"
                image="/assets/images/user2.jpg"
            />

        </div>
</template>