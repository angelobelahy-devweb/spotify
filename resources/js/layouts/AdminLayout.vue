<script setup lang="ts">
import {
  LayoutDashboard,
  Users,
  Music2,
  Disc3,
  Album,
  Tags,
  CreditCard,
  MessageCircle,
  Heart,
  BarChart3,
  Menu,
  X,
  LogOut,
} from 'lucide-vue-next'
import backgroundImage from '@/assets/images/font_casque.png';

import { Link, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const sidebarOpen = ref(false)

const menus = [
  {
    name: 'Dashboard',
    icon: LayoutDashboard,
<<<<<<< HEAD
    route: '/admin',
=======
<<<<<<< HEAD
    route: '/admin/dashboard',
=======
    route: '/admin',
>>>>>>> ed03808 (initialize AdminLayout)
>>>>>>> develop
  },
  {
    name: 'Utilisateurs',
    icon: Users,
    route: '/admin/users',
  },
  {
    name: 'Artistes',
    icon: Music2,
    route: '/admin/artists',
  },
  {
    name: 'Albums',
    icon: Album,
    route: '/admin/albums',
  },
  {
    name: 'Tracks',
    icon: Disc3,
    route: '/admin/tracks',
  },
  {
    name: 'Genres',
    icon: Tags,
    route: '/admin/genres',
  },
  {
    name: 'Abonnements',
    icon: CreditCard,
    route: '/admin/subscriptions',
  },
  {
    name: 'Commentaires',
    icon: MessageCircle,
    route: '/admin/comments',
  },
  {
    name: 'Favoris',
    icon: Heart,
    route: '/admin/favorites',
  },
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> develop
  {
    name: 'Statistiques',
    icon: BarChart3,
    route: '/admin/statistics',
  },
<<<<<<< HEAD
=======
>>>>>>> ed03808 (initialize AdminLayout)
>>>>>>> develop
]
</script>

<template>
  <div class="text-white w-full flex max-h-screen bg-[#212121] overflow-y-hidden">
    <!-- overlay mobile -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 bg-black/50 z-40 lg:hidden"
    ></div>

    <!-- SIDEBAR -->
    <aside
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
      class="fixed lg:static top-0 left-0 z-50
      w-[280px] h-screen
      bg-[#0d1329]
      border-r border-[#33437e]
      transition-all duration-300
      lg:translate-x-0"
    >
      <!-- logo -->
      <div
        class="h-[max-content] border-b border-[#33437e]
        flex items-center justify-between px-6"
      >
        <div class="flex flex-col items-center p-2">
          <div  class="flex justify-start items-center">
                <Link href="/" class="text-xl font-bold">
                <img src="/assets/images/logo.png" alt="logo" class="w-30">
                </Link>
            </div>

          <p class="text-[8px] text-gray-400">
            ADMIN PANEL
          </p>
        </div>
<<<<<<< HEAD
        
=======
<<<<<<< HEAD

=======
        
>>>>>>> ed03808 (initialize AdminLayout)
>>>>>>> develop

        <button
          @click="sidebarOpen = false"
          class="lg:hidden"
        >
          <X />
        </button>
      </div>

      <!-- menu -->
      <nav class="p-4 space-y-1">
        <Link
          v-for="menu in menus"
          :key="menu.name"
          :href="menu.route"
          class="flex items-center gap-4
          px-3 py-2 rounded-md text-sm
          transition-all duration-300
          hover:bg-[#33437ed0]"
          :class="
            usePage().url.startsWith(menu.route)
              ? 'bg-gradient-to-r from-black/80 to-[#33437ed0] shadow-lg'
              : ''
          "
        >
          <component :is="menu.icon" size="22" />

          <span class="font-medium">
            {{ menu.name }}
          </span>
        </Link>
      </nav>

      <!-- bottom -->
      <div
        class="absolute bottom-0 left-0
        w-full p-4 border-t border-[#33437e]"
      >
        <button
          class="w-full flex items-center gap-3
          px-2 py-2 rounded-md text-sm cursor-pointer
          bg-[#ab072e] hover:bg-[#ab072e]/80
          transition-all"
        >
          <LogOut size="20" />

          Déconnexion
        </button>
      </div>
    </aside>

    <!-- CONTENT -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- NAVBAR -->
      <header
        class="h-[80px]
        bg-[#0d1329]
        backdrop-blur-xl
        border-b border-[#33437e]
        px-6 flex items-center justify-between"
      >
        <!-- left -->
        <div class="flex items-center gap-4">
          <button
            @click="sidebarOpen = true"
            class="lg:hidden"
          >
            <Menu />
          </button>

          <div>
            <h2 class="font-bold text-xl">
              Dashboard
            </h2>

            <p class="text-sm text-gray-400">
              Bienvenue Admin
            </p>
          </div>
        </div>

        <!-- right -->
        <div class="flex items-center gap-4">
          <div class="text-right">
            <h3 class="font-semibold">
              Angelo
            </h3>

            <p class="text-xs text-gray-400">
              Administrateur
            </p>
          </div>

          <img
            :src="`/assets/images/admin.jpeg`"
            class="w-12 h-12 rounded-full object-cover border-2 border-[#ab072e]"
          />
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
        bg-cover
        bg-center
        "
        :style="{
          backgroundImage: `linear-gradient(#000, #33437e44),url(${backgroundImage})`
        }"
      >

        <div class="relative z-10 p-10">
          <slot />
        </div>
      </main>
    </div>
  </div>
<<<<<<< HEAD
</template>
=======
<<<<<<< HEAD
</template>
=======
</template>
>>>>>>> ed03808 (initialize AdminLayout)
>>>>>>> develop
