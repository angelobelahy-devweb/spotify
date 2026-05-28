<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import logo from '@/assets/images/jbl.png'

defineProps({
  artists: Array
});

const form = useForm({
  name: '',
  artist_id: '',
  title: '',
  release_year: '',
});

const submit = () => {
  form.post(route('albums.store'));
};
</script>

<template>
  <div class="max-w-md mx-auto mt-10 p-6 bg-[#121212]/50 rounded-lg shadow flex flex-col items-center justify-center">
    <Link href="/" class="flex justify-start items-center bg-white/80 w-15 p-2 h-15 rounded-full">
        <img :src="logo" alt="logo" class="w-full">
    </Link>
    <h1 class="text-2xl font-bold mb-4">Ajouter un Album</h1>
    
    <form @submit.prevent="submit"  class="w-full">
      <!-- Choix de l'Artiste -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Artiste</label>
        <select v-model="form.artist_id" class="w-full border border-[#2e2e2e] focus:border-[#33437e] rounded p-2">
          <option value="">Sélectionnez un artiste</option>
          <option v-for="artist in artists" :key="artist.id" :value="artist.id">
            {{ artist.name }}
          </option>
        </select>
        <div v-if="form.errors.artist_id" class="text-red-500 text-sm mt-1">{{ form.errors.artist_id }}</div>
      </div>

      <!-- Titre de l'album -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Titre de l'album</label>
        <input v-model="form.title" type="text" class="w-full border border-[#2e2e2e] focus:border-[#33437e] rounded p-2 outline-none" />
        <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
      </div>

      <!-- Année de sortie -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Année de sortie</label>
        <input v-model="form.release_year" type="number" class="w-full border border-[#2e2e2e] focus:border-[#33437e] rounded p-2 outline-none" />
        <div v-if="form.errors.release_year" class="text-red-500 text-sm mt-1">{{ form.errors.release_year }}</div>
      </div>

      <!-- Bouton -->
      <button
          type="submit"
          :disabled="form.processing"
          class="bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all duration-300 px-6 py-2 rounded-full text-sm font-bold flex items-center justify-center gap-2 w-max cursor-pointer disabled:opacity-50
          "
      >
          {{
              form.processing
                  ? 'Création...'
                  : "Créer l'album"
          }}
      </button>
    </form>
  </div>
</template>
