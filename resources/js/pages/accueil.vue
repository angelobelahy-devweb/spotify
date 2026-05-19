<script setup lang="ts">
import PrimaryButton from '@/components/angelo/button/PrimaryButton.vue';
import MusicCard from '@/components/angelo/cards/MusicCard.vue';
import { ChevronFirst, ChevronLast } from 'lucide-vue-next';

</script>

<template>
    <!-- <PrimaryButton :loading="true">
        Sauvegarder
    </PrimaryButton> -->
    <div class="relative w-full min-h-screen ">
        <!-- Je veux que ceci est un carousel avec des bouttons droite et gauche -->
        <div class="flex gap-2 my-2 flex-wrap">
            <PrimaryButton size="xs">
                All
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9] hover:text-white" color="black">
                Pop
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9] hover:text-white" color="black">
                Rap
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9] hover:text-white" color="black">
                Rock
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9] hover:text-white" color="black">
                Metal
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9] hover:text-white" color="black">
                Jazz 
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9] hover:text-white" color="black">
                Blues 
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9] hover:text-white" color="black">
                Musique Classique
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9] hover:text-white" color="black">
                Dancehall 
            </PrimaryButton>
            <PrimaryButton size="xs" class="bg-[#c8cac9] hover:text-white" color="black">
                Country  
            </PrimaryButton>
        </div>
        <!-- Music List -->
         <h1 class="text-[#e4e8f3d0] text-2xl my-2">Nouveautés</h1>
        <!-- Je veux que ceci est un carousel avec des bouttons droite et gauche -->
        <div class="flex flex-wrap items-center justify-start gap-2 ">
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/1.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/2.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/3.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/4.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/5.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/6.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/8.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/9.JPG"
            />
            
        </div>
        <!-- Music List -->
         <h1 class="text-[#e4e8f3d0] text-2xl my-2">Populaires</h1>
        <!-- Je veux que ceci est un carousel avec des bouttons droite et gauche -->
        <div class="flex flex-wrap items-center justify-start gap-2 ">
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/10.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/11.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/12.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/13.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/14.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/15.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/16.JPG"
            />
        </div>
        <!-- Music List -->
         <h1 class="text-[#e4e8f3d0] text-2xl my-2">Suggestions</h1>
        <!-- Je veux que ceci est un carousel avec des bouttons droite et gauche -->
        <div class="flex flex-wrap items-center justify-start gap-2 ">
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/18.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/19.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/20.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/21.JPG"
            />
            <MusicCard
                title="Aza ambelanao"
                artist="Rim ka"
                image="/assets/images/22.JPG"
            />
        </div>
     </div>
</template>