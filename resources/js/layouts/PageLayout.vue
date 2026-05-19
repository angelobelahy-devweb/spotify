

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import logo from '@/assets/images/logo.png'
import PrimaryButton from '@/components/angelo/button/PrimaryButton.vue';
import logo1 from '@/assets/images/lunnette.JPG';
import backgroundImage from '@/assets/images/font_casque.png';
import SearchInput from '@/components/angelo/inputs/SearchInput.vue';
import MusicPlayeur from '@/components/angelo/cards/MusicPlayeur.vue';
import { AlbumIcon, CogIcon, DiscAlbumIcon, HeartIcon, HomeIcon, MenuIcon, UserIcon } from 'lucide-vue-next';
import MusicPlay from '@/components/angelo/cards/MusicPlay.vue';




const sidebarOpen = ref(true)
</script>

<template>
  <div class="w-full flex max-h-screen bg-[#212121] overflow-y-hidden">
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'w-50' : 'w-15'" class="p-2 bg-[#212121] text-white transition-all duration-300 relative">
      <div  class="flex justify-start items-center">
        <Link href="/" class="text-xl font-bold">
          <img :src="logo" alt="logo" class="w-30">
        </Link>
      </div>
      <nav class="mt-5">
        <ul>
          <Link href="/" class="flex gap-2 items-center p-2 hover:bg-[#33437ed0] cursor-pointer rounded">
            <HomeIcon class="w-4 h-4" />
            <li class="text-sm">
              Accueil
            </li>
          </Link>
          <Link href="/albums" class="flex gap-2 items-center p-2 hover:bg-[#33437ed0] cursor-pointer rounded" active-class="text-green-400 font-semibold">
            <DiscAlbumIcon class="w-4 h-4" />
            <li class="text-sm">
              Album
            </li>
          </Link>
          <Link href="/artistes" class="flex gap-2 items-center p-2 hover:bg-[#33437ed0] cursor-pointer rounded">
            
            <UserIcon class="w-4 h-4" />
            <li class="text-sm">
              Artiste
            </li>
          </Link>
          <Link href="/favories" class="flex gap-2 items-center p-2 hover:bg-[#33437ed0] cursor-pointer rounded">
              <HeartIcon class="w-4 h-4" />
            <li class="text-sm">
              Favorie
            </li>
          </Link>
          <Link href="/parameters" class="flex gap-2 items-center p-2 hover:bg-[#33437ed0] cursor-pointer rounded">
              <CogIcon class="w-4 h-4" />
            <li class="text-sm">
              Paramettre
            </li>
          </Link>
        </ul>
      </nav>
      <!-- Music Playeur -->
       <MusicPlay class="fixed
        bottom-0
        left-0
        right-0
        w-full
        z-50" />
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">
      <!-- Navbar -->
      <header class="bg-[#212121de] p-4 flex justify-between items-center text-black">
        <!-- <button @click="sidebarOpen = !sidebarOpen" class="text-white">☰</button> -->
        <button @click="sidebarOpen = !sidebarOpen" class="text-black cursor-pointer bg-[#33437e] hover:text-dark flex justify-center items-center w-8 h-8 hover:bg-[#33437ed0] rounded-sm">
          <MenuIcon  class="w-7 h-7"/>
        </button>
        <div class="flex gap-5 items-center">
          <SearchInput/>
          <PrimaryButton
          size="xs">
              Explore premium
          </PrimaryButton>
          <div class="flex items-center gap-3">
            <img :src="logo1" class="rounded-full w-10" alt="profil"/>
          </div>
        </div>
      </header>

      <!-- Content -->
      <main
        class="
        relative
        flex
        flex-col
        w-full
        h-screen
        overflow-y-auto
        rounded-[1rem_0_0_0]
        bg-cover
        bg-center
        "
        :style="{
          backgroundImage: `linear-gradient(#000, #33437e44),url(${backgroundImage})`
        }"
      >

        <div class="relative z-10 p-[10px_10px_6rem_10px]">
          <slot />
        </div>
      </main>
    </div>

    
  </div>
</template>

<style scoop>
@font-face {
  font-family: 'titre'; /* OBLIGATOIRE */
  src: url('/assets/fonts/titre.ttf') format('truetype'); /* préciser le format */
  font-weight: normal;
  font-style: normal;
}

.titre {
  font-family: 'titre', sans-serif;
  
}
@font-face {
  font-family: 'text'; /* OBLIGATOIRE */
  src: url('/assets/fonts/text.ttf') format('truetype'); /* préciser le format */
  font-weight: normal;
  font-style: normal;
}

.texte {
  font-family: 'text', sans-serif;
}
#main {
  background: red;
}
</style>