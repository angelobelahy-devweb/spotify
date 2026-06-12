

<script setup lang="ts">
import Toast from 'primevue/toast';
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import logo from '@/assets/images/logo.png'


import PrimaryButton from '@/components/angelo/button/PrimaryButton.vue';
import logo1 from '@/assets/images/lunnette.JPG';
import backgroundImage from '@/assets/images/font_casque.png';
import SearchInput from '@/components/angelo/inputs/SearchInput.vue';
import SearchInputMd from '@/components/angelo/inputs/SearchInputMd.vue';
import MusicPlayeur from '@/components/angelo/cards/MusicPlayeur.vue';
import { Search } from 'lucide-vue-next'

import { 
  AlbumIcon, 
  CogIcon, 
  Album, 
  HeartIcon, 
  HomeIcon, 
  MenuIcon, 
  Music2, 
  Disc3, 
  ChevronDown,
  User,
  LogIn,
  LogInIcon,
  UserPlus,
  PanelLeft ,
  Settings,
  Sidebar,
  LogOut } from 'lucide-vue-next';
import MusicPlay from '@/components/angelo/cards/MusicPlay.vue';
import BaseModal from '@/components/angelo/modals/BaseModal.vue';
import FooterMusic from '@/components/angelo/footer/FooterMusic.vue';
import { dashboard, login, register } from '@/routes'





withDefaults(
    defineProps<{
        canRegister: boolean;
        sidebarOpen: Boolean;
    }>(),
    {
        canRegister: true,
    },
);


const isOpenModal = ref(false);
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
  <div class="w-full flex max-h-screen bg-[#0d1329] overflow-y-hidden">
    <!-- On place le Toast ici au niveau global -->
    <Toast />
    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'w-50' : 'w-15'" class="p-2 bg-[#0d1329] text-white transition-all duration-300 relative">
      <div  class="flex justify-start items-center">
        <Link href="/" class="text-xl font-bold">
          <img :src="logo" alt="logo" class="w-30">
        </Link>
      </div>
      <nav class="mt-5">
        <ul class="space-y-1">

          <!-- ACCUEIL -->
          <Link
            href="/"
            :class="[
              'flex items-center gap-4 px-3 py-2 rounded-md text-sm transition-all duration-300 hover:bg-[#33437ed0]',
              sidebarOpen ? 'justify-start' : 'justify-center',
              $page.url === '/' ? 'bg-gradient-to-r from-black/80 to-[#33437ed0] shadow-lg' : ''
            ]"
             title='Accueil'
          >
            <HomeIcon class="w-5 h-5" />

            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">
              Accueil
            </li>
          </Link>

          <!-- ALBUM -->
          <Link
            href="/albums"
            :class="[
              'flex items-center gap-4 px-3 py-2 rounded-md text-sm transition-all duration-300 hover:bg-[#33437ed0]',
              sidebarOpen ? 'justify-start' : 'justify-center',
              $page.url.startsWith('/albums')
                ? 'bg-gradient-to-r from-black/80 to-[#33437ed0] shadow-lg'
                : ''
            ]"
            title='Album'
          >
            <Album class="w-5 h-5" />

            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">
              Albums
            </li>
          </Link>

          <!-- ARTISTE -->
          <Link
            href="/artistes"
            :class="[
              'flex items-center gap-4 px-3 py-2 rounded-md text-sm transition-all duration-300 hover:bg-[#33437ed0]',
              sidebarOpen ? 'justify-start' : 'justify-center',
              $page.url.startsWith('/artistes')
                ? 'bg-gradient-to-r from-black/80 to-[#33437ed0] shadow-lg'
                : ''
            ]"
            title='Artiste'
          >
            <Music2 class="w-5 h-5" />

            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">
              Artistes
            </li>
          </Link>

          <!-- MUSIC -->
          <Link
            href="/tracks/create"
            :class="[
              'flex items-center gap-4 px-3 py-2 rounded-md text-sm transition-all duration-300 hover:bg-[#33437ed0]',
              sidebarOpen ? 'justify-start' : 'justify-center',
              $page.url.startsWith('/tracks')
                ? 'bg-gradient-to-r from-black/80 to-[#33437ed0] shadow-lg'
                : ''
            ]"
            title='Music'
          >
            <Disc3 class="w-5 h-5" />

            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">
              Tracks
            </li>
          </Link>

          <!-- FAVORIE -->
          <Link
            href="/favories"
            :class="[
              'flex items-center gap-4 px-3 py-2 rounded-md text-sm transition-all duration-300 hover:bg-[#33437ed0]',
              sidebarOpen ? 'justify-start' : 'justify-center',
              $page.url.startsWith('/favories')
                ? 'bg-gradient-to-r from-black/80 to-[#33437ed0] shadow-lg'
                : ''
            ]"
            title='Favorie'
          >
            <HeartIcon class="w-5 h-5" />

            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">
              Favoris
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
      <header class="bg-[#0d1329] p-4 flex justify-between items-center text-black">
        <button @click="sidebarOpen = !sidebarOpen" class="text-black cursor-pointer hover:text-dark flex justify-center items-center w-8 h-8  rounded-sm">
          <Sidebar  class="w-7 h-7 text-white"/>
        </button>
        <div class="flex gap-5 items-center justify-end">
          <div class="not-md:hidden">
            <SearchInput/>
          </div>
          
          <label for="my_modal_7" class="md:hidden">
            <SearchInputMd />
          </label>

          <!-- Put this part before </body> tag -->
          <input type="checkbox" id="my_modal_7" class="modal-toggle" />
          <div class="modal" role="dialog">
            <div class="modal-box">
              <div class="flex  items-center  bg-black/20  border border-[#33437e]  backdrop-blur-xl rounded-full px-2 py-2">
                  <Search class="w-5 h-5 text-gray-400" />
                  <input  type="text" placeholder="Que souhaitez-vous écouter ?" class="bg-transparent  outline-none  text-white  text-xs  ml-3  w-full"/>
              </div>
            </div>
            <label class="modal-backdrop" for="my_modal_7">Close</label>
          </div>
          
          <div v-if="$page.props.auth.user" class="flex gap-5 items-center">
            
              <PrimaryButton
              size="xs" class="w-[500px]">
                  Explore premium
              </PrimaryButton>
              <!-- PROFILE -->
              <div class="dropdown dropdown-end w-[max-content]">
                
                <!-- BUTTON -->
                <div
                  tabindex="0"
                  role="button"
                  class="
                    flex items-center gap-3  w-[max-content]
                  "
                >

                  <div class="w-10 rounded-full h-10 bg-white/80 text-lg flex justify-center items-center font-bold cursor-pointer">
                  {{ $page.props.auth.user.name.charAt(0) }}
                  </div>

                  
                </div>

                <!-- DROPDOWN -->
                <ul
                  tabindex="0"
                  class="
                    dropdown-content
                    z-[999]
                    menu
                    p-2
                    shadow-2xl
                    bg-[#181818]
                    rounded-2xl
                    w-64
                    border
                    border-white/10
                    mb-2
                  "
                >

                  <!-- HEADER -->
                  <div class="px-3 py-2 border-b border-white/10 mb-2">
                    <p class="text-white font-semibold">
                      {{ $page.props.auth.user.name }}
                    </p>

                    <p class="text-xs text-gray-400">
                      {{ $page.props.auth.user.email }}
                    </p>
                  </div>

                  <!-- PROFILE -->
                  <li>
                    <Link
                      href="/profile"
                      class="flex gap-2 items-center p-2 hover:bg-[#33437ed0] cursor-pointer rounded transition text-white"
                    >
                      <User class="w-4 h-4" />
                      Voir Profil
                    </Link>
                  </li>

                  <!-- SETTINGS -->
                  <li>
                    <Link
                      href="/settings"
                      class="flex gap-2 items-center p-2 hover:bg-[#33437ed0] cursor-pointer rounded transition text-white"
                    >
                      <Settings class="w-4 h-4" />
                      Paramètres
                    </Link>
                  </li>

                  <!-- LOGOUT -->
                  <li>
                    <Link
                      href="/logout"
                      method="post"
                      as="button"
                      class="flex items-center gap-2 text-red-500 hover:text-white hover:bg-red-500 transition"
                    >
                      <LogOut class="w-4 h-4" />
                      Déconnexion
                    </Link>
                  </li>

                </ul>
              </div>

          </div>
          <div v-else class="flex gap-3 items-center">
                <Link
                    :href="login()"
                >
                    <PrimaryButton>
                      <LogIn class=" w-5 h-5 text-white" />
                     <span class="not-md:hidden"> Se connecter </span>
                    </PrimaryButton>
                </Link>
                <Link
                    v-if="canRegister"
                    :href="register()"
                >
                    <PrimaryButton>
                      <UserPlus class=" w-5 h-5 text-white" />
                        <span class="not-md:hidden">Créer un compte</span>
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

