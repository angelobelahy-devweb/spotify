

<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import logo from '@/assets/images/logo.png'


import PrimaryButton from '@/components/angelo/button/PrimaryButton.vue';
import logo1 from '@/assets/images/lunnette.JPG';
import backgroundImage from '@/assets/images/font_casque.png';
import SearchInput from '@/components/angelo/inputs/SearchInput.vue';
import MusicPlayeur from '@/components/angelo/cards/MusicPlayeur.vue';
import { AlbumIcon, CogIcon, DiscAlbumIcon, HeartIcon, HomeIcon, MenuIcon, Music, UserIcon } from 'lucide-vue-next';
import MusicPlay from '@/components/angelo/cards/MusicPlay.vue';
import BaseModal from '@/components/angelo/modals/BaseModal.vue';
import FooterMusic from '@/components/angelo/footer/FooterMusic.vue';
import { dashboard, login, register } from '@/routes'


withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);


const isOpenModal = ref(false);
console.log('1-',isOpenModal.value);
const openModal = () => {
    isOpenModal.value = true;
    console.log(isOpenModal.value);
}
const closeModal = (data) => {
    isOpenModal.value = data;
}

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
            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">
              Accueil
            </li>
          </Link>
          <Link href="/albums" class="flex gap-2 items-center p-2 hover:bg-[#33437ed0] cursor-pointer rounded" active-class="text-green-400 font-semibold">
            <DiscAlbumIcon class="w-4 h-4" />
            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">
              Album
            </li>
          </Link>
          <Link href="/artistes" class="flex gap-2 items-center p-2 hover:bg-[#33437ed0] cursor-pointer rounded">

            <UserIcon class="w-4 h-4" />
            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">
              Artiste
            </li>
          </Link>
          <Link href="/tracks/create" class="flex gap-2 items-center p-2 hover:bg-[#33437ed0] cursor-pointer rounded">
              <Music class="w-4 h-4" />
            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">
              Music
            </li>
          </Link>
          <Link href="/favories" class="flex gap-2 items-center p-2 hover:bg-[#33437ed0] cursor-pointer rounded">
              <HeartIcon class="w-4 h-4" />
            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">
              Favorie
            </li>
          </Link>
          <Link href="/parameters" class="flex gap-2 items-center p-2 hover:bg-[#33437ed0] cursor-pointer rounded">
              <CogIcon class="w-4 h-4" />
            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">
              Paramettre
            </li>
          </Link>
        </ul>
      </nav>
      <span class="blurr"></span>
      <span class="blurr"></span>
      <!-- Music Playeur -->
        <MusicPlay class="fixed
          bottom-0
          left-0
          right-0
          w-full
          z-50" @click.prevent="openModal" />
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">
      <!-- Navbar -->
      <header class="bg-[#212121de] p-4 flex justify-between items-center text-black">
        <button @click="sidebarOpen = !sidebarOpen" class="text-black cursor-pointer bg-[#33437e] hover:text-dark flex justify-center items-center w-8 h-8 hover:bg-[#33437ed0] rounded-sm">
          <MenuIcon  class="w-7 h-7"/>
        </button>
        <div class="flex gap-5 items-center">
          <SearchInput/>
          <div v-if="$page.props.auth.user" class="flex gap-5 items-center">
              <PrimaryButton
              size="xs">
                  Explore premium
              </PrimaryButton>
              <div class="flex items-center gap-3">
                <img :src="logo1" class="rounded-full w-10" alt="profil"/>
              </div>
          </div>
          <div v-else class="flex gap-3 items-center">
                <Link
                    :href="login()"
                >
                    <PrimaryButton>
                     Se connecter
                    </PrimaryButton>
                </Link>
                <Link
                    v-if="canRegister"
                    :href="register()"
                >
                    <PrimaryButton>
                        Créer un compte
                    </PrimaryButton>
                </Link>
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
        <div>
          <FooterMusic class="mb-[5rem]"/>
        </div>
      </main>
    </div>


  </div>
  <BaseModal v-if="isOpenModal" @close-modal="closeModal">
      <MusicPlayeur/>
  </BaseModal>

</template>

<style scoped>
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
.blurr {
  position: absolute;
  box-shadow: 0 0 1000px 50px rgb(39, 59, 133);
  z-index: 20;
}

</style>

