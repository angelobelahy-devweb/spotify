<script setup lang="ts">
import FavorieCard from '@/components/angelo/cards/FavorieCard.vue';

</script>

<template>
     <h1 class="text-[#e4e8f3d0] text-2xl my-2">My Favories music</h1>
        <div class="flex flex-col items-center justify-start gap-2 ">
            <FavorieCard
                number="1"
                title="Donia"
                artist="Lion Hill"
                image="/assets/images/23.JPG"
            />
            <FavorieCard
                number="2"
                title="Seedap"
                artist="Rim ka"
                image="/assets/images/24.JPG"
            />
        </div>
</template>