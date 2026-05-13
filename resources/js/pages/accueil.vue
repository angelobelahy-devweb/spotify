<script setup lang="ts">
import PrimaryButton from '@/components/angelo/button/PrimaryButton.vue';
import MusicCard from '@/components/angelo/cards/MusicCard.vue';
import { ChevronFirst, ChevronLast } from 'lucide-vue-next';

</script>

<template>
    <!-- <PrimaryButton :loading="true">
        Sauvegarder
    </PrimaryButton> -->
    <div class="relative w-full min-h-screen">
        <div class="flex gap-2 my-2 flex-wrap">
            <PrimaryButton size="xs">
                All
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9]" color="black">
                Pop
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9]" color="black">
                Rap
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9]" color="black">
                Rock
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9]" color="black">
                Metal
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9]" color="black">
                Jazz 
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9]" color="black">
                Blues 
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9]" color="black">
                Musique Classique
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9]" color="black">
                Dancehall 
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9]" color="black">
                Country  
            </PrimaryButton>
        </div>
        <!-- Music List -->
        <div class="flex flex-wrap items-center justify-between gap-5">
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        <MusicCard
            title="Aza ambelanao"
            artist="Rim ka"
            image="/assets/images/album.JPG"
        />
        
        </div>
        
    
     </div>
</template>