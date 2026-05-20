<script setup>
import PrimaryButton from '@/components/angelo/button/PrimaryButton.vue';
import MusicCard from '@/components/angelo/cards/MusicCard.vue';

// import { ref } from 'vue';

// const scrollContainer = ref(null)

// const scrollLeft = () => {
//     scrollContainer.value.scrollBy({
//         left: -200,
//         behavior: 'smooth',
//     })
// }

// const scrollRight = () => {
//     scrollContainer.value.scrollBy({
//         left: 200,
//         behavior: 'smooth',
//     })
// }

</script>



<template>
    
    <div class="relative w-full min-h-screen ">
        <!-- <div class="relative w-full">
            <button
                @click="scrollLeft"
                class="
                    absolute
                    left-0
                    top-1/2
                    -translate-y-1/2
                    z-20
                    bg-[#33437e]
                    hover:bg-black
                    text-white
                    w-10
                    h-10
                    rounded-full
                    flex
                    items-center
                    justify-center
                    shadow-lg
                "
            >
                ‹
            </button>

   
            <div
                ref="scrollContainer"
                class="
                    flex
                    flex-nowrap
                    gap-2
                    overflow-x-auto
                    scroll-smooth
                    whitespace-nowrap
                    scrollbar-hide
                    px-12
                    py-2
                    w-full
                "
            >
                <PrimaryButton
                    size="xs"
                    class="flex-shrink-0"
                >
                    All
                </PrimaryButton>

                <PrimaryButton
                    size="xs"
                    class="bg-[#c8cac9] hover:text-white flex-shrink-0"
                    color="black"
                >
                    Pop
                </PrimaryButton>

                <PrimaryButton
                    size="xs"
                    class="bg-[#c8cac9] hover:text-white flex-shrink-0"
                    color="black"
                >
                    Rap
                </PrimaryButton>

                <PrimaryButton
                    size="xs"
                    class="bg-[#c8cac9] hover:text-white flex-shrink-0"
                    color="black"
                >
                    Rock
                </PrimaryButton>

                <PrimaryButton
                    size="xs"
                    class="bg-[#c8cac9] hover:text-white flex-shrink-0"
                    color="black"
                >
                    Metal
                </PrimaryButton>

                <PrimaryButton
                    size="xs"
                    class="bg-[#c8cac9] hover:text-white flex-shrink-0"
                    color="black"
                >
                    Jazz
                </PrimaryButton>

                <PrimaryButton
                    size="xs"
                    class="bg-[#c8cac9] hover:text-white flex-shrink-0"
                    color="black"
                >
                    Blues
                </PrimaryButton>

                <PrimaryButton
                    size="xs"
                    class="bg-[#c8cac9] hover:text-white flex-shrink-0"
                    color="black"
                >
                    Musique Classique
                </PrimaryButton>

                <PrimaryButton
                    size="xs"
                    class="bg-[#c8cac9] hover:text-white flex-shrink-0"
                    color="black"
                >
                    Dancehall
                </PrimaryButton>

                <PrimaryButton
                    size="xs"
                    class="bg-[#c8cac9] hover:text-white flex-shrink-0"
                    color="black"
                >
                    Country
                </PrimaryButton>
            </div>

    
            <button
                @click="scrollRight"
                class="
                    absolute
                    right-0
                    top-1/2
                    -translate-y-1/2
                    z-20
                    bg-[#33437e]
                    hover:bg-black
                    text-white
                    w-10
                    h-10
                    rounded-full
                    flex
                    items-center
                    justify-center
                    shadow-lg
                "
            >
                ›
            </button>
        </div> -->
        <!-- Music List -->
        <h1 class="text-[#e4e8f3d0] text-2xl my-2">Nouveautés</h1>
        <!-- Je veux que ceci est un carousel avec des bouttons droite et gauche -->
        <div class="flex flex-wrap items-center justify-start gap-2 w-full">
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

<style scoped>
/* Cache scrollbar */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>