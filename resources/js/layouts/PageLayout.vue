

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import logo from '@/assets/images/spotify1.png'
import PrimaryButton from '@/components/angelo/button/PrimaryButton.vue';
import logo1 from '@/assets/images/lunnette.JPG';



const sidebarOpen = ref(true)
</script>

<template>
  <div class="flex h-screen bg-[#212121]">
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class=" p-2 bg-[#212121] text-white transition-all duration-300 ">
      <div  class="flex justify-start items-center">
        <Link href="" class="text-xl font-bold">
          <img :src="logo" alt="logo" class="w-30">
        </Link>
      </div>
      <nav class="mt-5">
        
        <ul>
          <Link href="" class="flex gap-2 items-center p-3 hover:bg-[#057430d0] cursor-pointer rounded">
   
            <li icon="pi pi-headphones">
              Album
            </li>
          </Link>
          <Link href="" class="flex gap-2 items-center p-3 hover:bg-[#057430d0] cursor-pointer rounded">
    
            <li>
              Artiste
            </li>
          </Link>

          <Link href="" class="flex gap-2 items-center p-3 hover:bg-[#057430d0] cursor-pointer rounded">
      
            <li>
              Genre
            </li>
          </Link>
        </ul>
      </nav>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">
      <!-- Navbar -->
      <header class="bg-[#212121] p-4 flex justify-between items-center text-black">
        <button @click="sidebarOpen = !sidebarOpen" class="text-white">☰</button>
        <PrimaryButton>
          Explore premium
      </PrimaryButton>
        <div class="flex items-center gap-3">
          <img :src="logo1" class="rounded-full w-10" alt="profil"/>
        </div>
      </header>

      <!-- Content -->
      <main class="flex flex-col w-full h-screen overflow-y-auto p-5 bg-[#000203] rounded-[1rem_0_0_0]" id="main">
        <slot />
      </main>
    </div>
  </div>
</template>

<style>
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