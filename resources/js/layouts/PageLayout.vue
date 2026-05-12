

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import logo from '@/assets/images/spotify1.png'
import PrimaryButton from '@/components/angelo/button/PrimaryButton.vue';
import logo1 from '@/assets/images/lunnette.JPG';
import backgroundImage from '@/assets/images/font_casque.JPG';



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
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="currentColor"
                viewBox="0 0 24 24"
                class="w-6 h-6"
            >
                <path
                    d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"
                />
            </svg>
            <li icon="pi pi-headphones">
              Accueil
            </li>
          </Link>
          <Link href="" class="flex gap-2 items-center p-3 hover:bg-[#057430d0] cursor-pointer rounded">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="currentColor"
                viewBox="0 0 24 24"
                class="w-6 h-6"
            >
                <path
                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10
                    10 10 10-4.48 10-10S17.52 2 12 2zm0
                    14c-2.21 0-4-1.79-4-4s1.79-4
                    4-4 4 1.79 4 4-1.79 4-4 4z"
                />
            </svg>
            <li icon="pi pi-headphones">
              Album
            </li>
          </Link>
          <Link href="" class="flex gap-2 items-center p-3 hover:bg-[#057430d0] cursor-pointer rounded">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="currentColor"
                viewBox="0 0 24 24"
                class="w-6 h-6"
            >
                <path
                    d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5
                    2.24-5 5 2.24 5 5 5zm0 2c-3.33
                    0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z"
                />
            </svg>
            <li>
              Artiste
            </li>
          </Link>

          <Link href="" class="flex gap-2 items-center p-3 hover:bg-[#057430d0] cursor-pointer rounded">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="currentColor"
                viewBox="0 0 24 24"
                class="w-6 h-6"
            >
                <path
                    d="M12 3v10.55A4 4 0 1 0 14
                    17V7h4V3h-6z"
                />
            </svg>
            <li>
              Genre
            </li>
          </Link>
          <Link href="" class="flex gap-2 items-center p-3 hover:bg-[#057430d0] cursor-pointer rounded">
            <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  viewBox="0 0 24 24"
                  class="w-5 h-5"
              >
                  <path
                      d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                      2 5.42 4.42 3 7.5 3c1.74 0 3.41.81
                      4.5 2.09C13.09 3.81 14.76 3
                      16.5 3 19.58 3 22 5.42
                      22 8.5c0 3.78-3.4 6.86-8.55
                      11.54L12 21.35z"
                  />
              </svg>
            <li>
              Favorie
            </li>
          </Link>
          <Link href="" class="flex gap-2 items-center p-3 hover:bg-[#057430d0] cursor-pointer rounded">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="currentColor"
                viewBox="0 0 24 24"
                class="w-6 h-6"
            >
                <path
                    d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5
                    2.24-5 5 2.24 5 5 5zm0 2c-3.33
                    0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z"
                />
            </svg>
            <li>
              Profil
            </li>
          </Link>
        </ul>
      </nav>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">
      <!-- Navbar -->
      <header class="bg-[#212121de] p-4 flex justify-between items-center text-black">
        <button @click="sidebarOpen = !sidebarOpen" class="text-white">☰</button>
        <PrimaryButton
        size="sm">
          Explore premium
      </PrimaryButton>
        <div class="flex items-center gap-3">
          <img :src="logo1" class="rounded-full w-10" alt="profil"/>
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
          backgroundImage: `linear-gradient(#212121de, #212121c7),url(${backgroundImage})`
        }"
      >

        <div class="relative z-10 p-5">
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