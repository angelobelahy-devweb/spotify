<script setup lang="ts">
import Toast from 'primevue/toast';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import logo from '@/assets/images/logo.png'

import PrimaryButton from '@/components/angelo/button/PrimaryButton.vue';
import backgroundImage from '@/assets/images/font_casque.png';
import SearchInput from '@/components/angelo/inputs/SearchInput.vue';
import SearchInputMd from '@/components/angelo/inputs/SearchInputMd.vue';
import MusicPlayeur from '@/components/angelo/cards/MusicPlayeur.vue';
import { Search, ShoppingCart } from 'lucide-vue-next'

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
import { authModalStore } from '@/lib/authModalStore';
import { playerStore } from '@/lib/playerStore';
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

const cartCount = ref(0)
const isOpenModal = ref(false);
const openModal = () => {
    isOpenModal.value = true;
    console.log(isOpenModal.value);
}
const closeModal = (data) => {
    isOpenModal.value = data;
}

const page = usePage();

const initializeFavorites = () => {
    const favorites = page.props.favorites ?? [];
    playerStore.setFavorites(favorites);
};

initializeFavorites();

const sidebarOpen = ref(true)

// 👉 Gestion du dropdown personnalisé
const isDropdownOpen = ref(false);

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const closeDropdown = () => {
    isDropdownOpen.value = false;
};

const handleLogout = () => {
    playerStore.stop();
};

// 👉 Fermer le dropdown en cliquant à l'extérieur
const handleClickOutside = (event: MouseEvent) => {
    const dropdown = document.querySelector('.custom-dropdown');
    if (dropdown && !dropdown.contains(event.target as Node)) {
        closeDropdown();
    }
};

// 👉 Ajouter/retirer l'écouteur d'événements
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

onMounted(() => {
    document.addEventListener('click', handleClickOutside);

    // Initialise le compteur du panier
    const savedCart = localStorage.getItem('music_cart')
    if (savedCart) cartCount.value = JSON.parse(savedCart).length

    // Écoute les mises à jour en direct depuis albumList
    window.addEventListener('cart-updated', ((e: CustomEvent) => {
        cartCount.value = e.detail
    }) as EventListener)
});
</script>

<template>
  <div class="w-full flex max-h-screen bg-[#0d1329] overflow-y-hidden overflow-x-hidden">
    <Toast />

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'w-50' : 'w-15'" class="p-2 bg-[#0d1329] text-white transition-all duration-300 relative flex-shrink-0">
      <div class="flex justify-start items-center">
        <Link href="/" class="text-xl font-bold">
          <img :src="logo" alt="logo" class="w-30">
        </Link>
      </div>
      <nav class="mt-5">
        <ul class="space-y-1">
          <Link
            href="/"
            :class="[
              'flex items-center gap-4 px-3 py-2 rounded-md text-sm transition-all duration-300 hover:bg-[#33437ed0]',
              sidebarOpen ? 'justify-start' : 'justify-center',
              $page.url === '/' ? 'bg-gradient-to-r from-black/80 to-[#33437ed0] shadow-lg' : ''
            ]"
            title='Accueil'
          >
            <HomeIcon class="w-5 h-5 flex-shrink-0" />
            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">Accueil</li>
          </Link>

          <Link
            href="/albums"
            :class="[
              'flex items-center gap-4 px-3 py-2 rounded-md text-sm transition-all duration-300 hover:bg-[#33437ed0]',
              sidebarOpen ? 'justify-start' : 'justify-center',
              $page.url.startsWith('/albums') ? 'bg-gradient-to-r from-black/80 to-[#33437ed0] shadow-lg' : ''
            ]"
            title='Album'
          >
            <Album class="w-5 h-5 flex-shrink-0" />
            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">Albums</li>
          </Link>

          <Link
            href="/artistes"
            :class="[
              'flex items-center gap-4 px-3 py-2 rounded-md text-sm transition-all duration-300 hover:bg-[#33437ed0]',
              sidebarOpen ? 'justify-start' : 'justify-center',
              $page.url.startsWith('/artistes') ? 'bg-gradient-to-r from-black/80 to-[#33437ed0] shadow-lg' : ''
            ]"
            title='Artiste'
          >
            <Music2 class="w-5 h-5 flex-shrink-0" />
            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">Artistes</li>
          </Link>

          <Link
            href="/tracks"
            :class="[
              'flex items-center gap-4 px-3 py-2 rounded-md text-sm transition-all duration-300 hover:bg-[#33437ed0]',
              sidebarOpen ? 'justify-start' : 'justify-center',
              $page.url.startsWith('/tracks') ? 'bg-gradient-to-r from-black/80 to-[#33437ed0] shadow-lg' : ''
            ]"
            title='Music'
          >
            <Disc3 class="w-5 h-5 flex-shrink-0" />
            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">Tracks</li>
          </Link>

          <Link
            href="/favories"
            :class="[
              'flex items-center gap-4 px-3 py-2 rounded-md text-sm transition-all duration-300 hover:bg-[#33437ed0]',
              sidebarOpen ? 'justify-start' : 'justify-center',
              $page.url.startsWith('/favories') ? 'bg-gradient-to-r from-black/80 to-[#33437ed0] shadow-lg' : ''
            ]"
            title='Favorie'
          >
            <HeartIcon class="w-5 h-5 flex-shrink-0" />
            <li :class="sidebarOpen ? 'text-sm' : 'hidden'">Favoris</li>
          </Link>
        </ul>
      </nav>
      <span class="blurr"></span>
      <span class="blurr"></span>
      <MusicPlay class="fixed bottom-0 left-0 right-0 w-full z-50" @click.prevent="openModal" />
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col min-w-0 relative">
      <!-- Navbar -->
      <header class="bg-[#0d1329] p-4 flex justify-between items-center text-black flex-shrink-0 relative z-50">
        <button @click="sidebarOpen = !sidebarOpen" class="text-black cursor-pointer hover:text-dark flex justify-center items-center w-8 h-8 rounded-sm">
          <Sidebar class="w-7 h-7 text-white flex-shrink-0"/>
        </button>
        <div class="flex gap-5 items-center justify-end">
            
            <Link href="/checkout" class="relative p-2 text-white hover:opacity-80 transition-all">
                <ShoppingCart class="w-6 h-6" />
                <span v-if="cartCount > 0" class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center">
                    {{ cartCount }}
                </span>
            </Link>

        <div class="flex gap-5 items-center justify-end">

          <div v-if="$page.props.auth.user" class="flex gap-5 items-center">
            <!-- 👉 DROPDOWN PERSONNALISÉ -->
            <div class="custom-dropdown">
              <button
                @click="toggleDropdown"
                class="dropdown-trigger"
              >
                <div class="avatar">
                  {{ $page.props.auth.user.name.charAt(0) }}
                </div>
                <ChevronDown class="dropdown-arrow" :class="{ 'rotate-180': isDropdownOpen }" />
              </button>

              <!-- Dropdown menu -->
              <transition
                enter-active-class="dropdown-enter-active"
                leave-active-class="dropdown-leave-active"
                enter-from-class="dropdown-enter-from"
                leave-to-class="dropdown-leave-to"
              >
                <div
                  v-if="isDropdownOpen"
                  class="dropdown-menu"
                >
                  <div class="dropdown-header">
                    <p class="dropdown-user-name">{{ $page.props.auth.user.name }}</p>
                    <p class="dropdown-user-email">{{ $page.props.auth.user.email }}</p>
                  </div>
                  
                  <Link
                    href="/" 
                    class="dropdown-item"
                  >
                    <User class="dropdown-item-icon" />
                    Voir Profil
                  </Link>

                  <Link
                    href="/settings/profile"
                    class="dropdown-item"
                  >
                    <Settings class="dropdown-item-icon" />
                    Paramètres
                  </Link>

                  <Link
                    href="/logout"
                    method="post"
                    as="button"

                    @click="handleLogout"
                    class="dropdown-item dropdown-item-danger"
                  >
                    <LogOut class="dropdown-item-icon" />
                    Déconnexion
                  </Link>
                </div>
              </transition>
            </div>
          </div>

          <div v-else class="flex gap-3 items-center">
            <Link :href="login()">
              <PrimaryButton>
                <LogIn class="w-5 h-5 text-white flex-shrink-0" />
                <span class="not-md:hidden">Se connecter</span>
              </PrimaryButton>
            </Link>
            <Link v-if="canRegister" :href="register()">
              <PrimaryButton>
                <UserPlus class="w-5 h-5 text-white flex-shrink-0" />
                <span class="not-md:hidden">Créer un compte</span>
              </PrimaryButton>
            </Link>
          </div>
        </div>
      </header>

      <!-- Content -->
      <main
        class="relative flex flex-col w-full h-screen overflow-y-auto overflow-x-hidden rounded-[1rem_0_0_0] bg-cover bg-center"
        :style="{
          backgroundImage: `linear-gradient(#000, #33437e44),url(${backgroundImage})`
        }"
      >
        <div class="relative z-10 p-[10px_10px_6rem_10px] w-full max-w-full overflow-x-hidden">
          <slot />
          <div>
            <FooterMusic class="mt-5 mb-5" />
          </div>
        </div>
      </main>
    </div>
  </div>

  <BaseModal v-if="isOpenModal" @close-modal="closeModal">
    <MusicPlayeur/>
  </BaseModal>

  <BaseModal v-if="authModalStore.open" @close-modal="authModalStore.open = false">
    <div class="text-white">
      <h2 class="text-2xl font-semibold mb-3">Connexion requise</h2>
      <p class="text-gray-300 leading-relaxed">{{ authModalStore.message }}</p>
      <div class="mt-6 flex justify-end gap-2">
        <button @click="authModalStore.open = false" class="px-4 py-2 rounded-lg border border-white/20 text-white hover:bg-white/10">
          Annuler
        </button>
        <Link href="/login" class="px-4 py-2 rounded-lg bg-[#33437e] text-white hover:bg-[#2a3560]">
          Se connecter
        </Link>
      </div>
    </div>
  </BaseModal>
</template>

<style scoped>
@font-face {
  font-family: 'titre';
  src: url('/assets/fonts/titre.ttf') format('truetype');
  font-weight: normal;
  font-style: normal;
}

.titre {
  font-family: 'titre', sans-serif;
}

@font-face {
  font-family: 'text';
  src: url('/assets/fonts/text.ttf') format('truetype');
  font-weight: normal;
  font-style: normal;
}

.texte {
  font-family: 'text', sans-serif;
}

.blurr {
  position: absolute;
  box-shadow: 0 0 1000px 50px rgb(39, 59, 133);
  z-index: 20;
}

/* ========== DROPDOWN PERSONNALISÉ ========== */
.custom-dropdown {
  position: relative;
  overflow: visible !important;
}

.dropdown-trigger {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 0;
  transition: opacity 0.2s;
}

.dropdown-trigger:hover {
  opacity: 0.8;
}

.avatar {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 9999px;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 1.125rem;
  font-weight: bold;
  flex-shrink: 0;
  color: #0d1329;
}

.dropdown-arrow {
  width: 1rem;
  height: 1rem;
  color: white;
  transition: transform 0.3s ease;
}

.rotate-180 {
  transform: rotate(180deg);
}

/* ========== MENU DROPDOWN ========== */
.dropdown-menu {
  position: absolute;
  right: 0;
  top: calc(100% + 0.5rem);
  width: 16rem;
  background: #09090fd0;
  border-radius: 1rem;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
  border: 1px solid rgba(255, 255, 255, 0.1);
  padding: 0.5rem;
  z-index: 9999;
  min-width: 16rem;
}

.dropdown-header {
  padding: 0.75rem 1rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  margin-bottom: 0.5rem;
}

.dropdown-user-name {
  color: white;
  font-weight: 600;
  margin: 0;
}

.dropdown-user-email {
  color: #9ca3af;
  font-size: 0.75rem;
  margin: 0;
}

.dropdown-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.625rem 1rem;
  color: white;
  text-decoration: none;
  font-size: 0.875rem;
  transition: all 0.2s;
  cursor: pointer;
  border: none;
  background: transparent;
  width: 100%;
  text-align: left;
}

.dropdown-item:hover {
  background: rgba(51, 67, 126, 0.8);
}

.dropdown-item-danger {
  color: #ef4444;
}

.dropdown-item-danger:hover {
  background: rgba(239, 68, 68, 0.2);
  color: #ef4444;
}

.dropdown-item-icon {
  width: 1rem;
  height: 1rem;
  flex-shrink: 0;
}

/* ========== ANIMATIONS DROPDOWN ========== */
.dropdown-enter-active {
  transition: all 0.2s ease;
}

.dropdown-leave-active {
  transition: all 0.15s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-0.5rem) scale(0.95);
}
</style>

<style>
/* 👉 Styles globaux pour empêcher le scroll horizontal */
html, body {
  overflow-x: hidden !important;
  max-width: 100% !important;
}

* {
  max-width: 100%;
  box-sizing: border-box;
}

/* ========== RESPONSIVE DROPDOWN ========== */
@media (max-width: 640px) {
  .dropdown-menu {
    width: 14rem;
    right: -1rem;
  }
}
</style>
