<script setup lang="ts">
// import AdminLayout from '@/layouts/AdminLayout.vue'
import {
  Users,
  Music2,
  Disc3,
  DollarSign,
  PlayCircle,
  Album,
} from 'lucide-vue-next'
import { Link } from '@inertiajs/vue3'

const stats = [
  {
    title: 'Utilisateurs',
    value: '12 540',
    icon: Users,
  },
  {
    title: 'Artistes',
    value: '1 240',
    icon: Music2,
  },
  {
    title: 'Albums',
    value: '820',
    icon: Album,
  },
  {
    title: 'Tracks',
    value: '5 200',
    icon: Disc3,
  },
  {
    title: 'Lectures',
    value: '1.2M',
    icon: PlayCircle,
  },
  {
    title: 'Revenus',
    value: '12 000 000 Ar',
    icon: DollarSign,
  },
]
</script>

<template>
  <!--<AdminLayout>-->
    <!-- title -->
    <div class="mb-8">
      <h1 class="text-3xl font-black">
        Dashboard
      </h1>

      <p class="text-gray-400 mt-2">
        Gérez votre plateforme
      </p>
    </div>

    <!-- cards -->
    <div
      class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6"
    >
      <div
        v-for="stat in stats"
        :key="stat.title"
        class="bg-[#33437e]/10
        border border-[#33437e]
        backdrop-blur-md
        rounded-xl
        p-6
        hover:scale-[1.02]
        transition-all duration-300"
      >
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400">
              {{ stat.title }}
            </p>

            <h2 class="text-xl font-black mt-3">
              {{ stat.value }}
            </h2>
          </div>

          <div
            class="w-10 h-10 rounded-xl
            bg-gradient-to-br
            from-black to-[#33437e]
            flex items-center justify-center"
          >
            <component
              :is="stat.icon"
              size="20"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- bottom section -->
    <div
      class="grid grid-cols-1 xl:grid-cols-3 gap-6 mt-8 "
    >
      <!-- recent albums -->
      <div
        class="xl:col-span-2
        bg-[#33437e]/10
        border border-[#33437e]
        rounded-xl
        backdrop-blur-md p-6"
      >
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold">
            Albums récents
          </h2>

            <Link href="" class="border-2 border-[#364a92] hover:bg-[#364a92] active:scale-95 transition-all duration-300 px-6 py-2 rounded-md text-sm font-bold flex items-center justify-center gap-2 cursor-pointer w-[max-content] disabled:opacity-50">
                Voir tout
            </Link>
        </div>

        <div class="space-y-4">
          <div
            v-for="i in 5"
            :key="i"
            class="flex items-center justify-between
            bg-[#0d1329]
            p-4 rounded-2xl"
          >
            <div class="flex items-center gap-4">
              <img
                src="/assets/images/album.jpg"
                class="w-16 h-16 rounded-xl object-cover"
              />

              <div>
                <h3 class="font-bold">
                  Album {{ i }}
                </h3>

                <p class="text-gray-400 text-sm">
                  Artiste
                </p>
              </div>
            </div>

            <Link href="" class="border-2 border-opacity-75 border-[#364a92] hover:bg-[#364a92] active:scale-95 transition-all duration-300 px-4 py-1 rounded-full text-sm font-bold flex items-center justify-center gap-2 cursor-pointer w-[max-content] disabled:opacity-50">
                Voir
            </Link>
          </div>
        </div>
      </div>

      <!-- users -->
      <div
        class="bg-[#0d1329]
        border border-[#33437e]
        rounded-xl p-6 h-[max-content]"
      >
        <h2 class="text-xl font-bold mb-6">
          Nouveaux utilisateurs
        </h2>

        <div class="space-y-4">
          <div
            v-for="i in 6"
            :key="i"
            class="flex items-center gap-4"
          >
            <img
              src="/assets/images/lunnette.jpg"
              class="w-12 h-12 rounded-full"
            />

            <div>
              <h3 class="font-semibold">
                Utilisateur {{ i }}
              </h3>

              <p class="text-sm text-gray-400">
                utilisateur@gmail.com
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  <!--</AdminLayout>-->
</template>
