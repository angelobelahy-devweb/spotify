<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft, Disc3Icon, Image, LucideAlbum, Music } from 'lucide-vue-next'
import { ref } from 'vue';

// defineProps({
//   albums: 
    
  
// });
const albums = ref([
    {id: '1',
    title: 'Pop',},
    {id: '2',
    title: 'Rock',},
    {id: '3',
    title: 'Afro',},
]);




const form = useForm({
  album_id: '',
  title: '',
  duration: '',
  audio_file: null, // On initialise à null
});

// Capturer le fichier quand l'utilisateur le sélectionne
const handleFileChange = (event) => {
  form.audio_file = event.target.files[0];
};

const submit = () => {
  form.post(route('tracks.store'));
};
</script>

<template>
  <div class="max-w-md mx-auto mt-10 p-6 bg-[#121212]/50 rounded-lg shadow flex flex-col items-center justify-center">
    <form @submit.prevent="submit"  class="w-full">
      <!-- Fichier Audio -->
      <div class="flex flex-col items-center gap-4">
          <label
              class="
                  relative
                  w-30
                  h-30
                  rounded-full
                  overflow-hidden
                  bg-[#232323]
                  cursor-pointer
                  group
                  border-2
                  border-dashed
                  border-[#3b3b3b]
                  hover:border-[#556cff]
                  transition-all
              "
          >
              <div
                  class="
                      w-full
                      h-full
                      flex
                      items-center
                      justify-center
                  "
              >
                  <Music
                      class="
                          w-12
                          h-12
                          text-gray-500
                      "
                  />
              </div>

              <input
                  type="file"
                  class="hidden"
                  @change="handleFileChange" accept="audio/*"
              />
          </label>
          <p class="text-gray-400 text-sm">
              Ajouter un fichier Audio(MP3)
          </p>
          <div v-if="form.errors.audio_file" class="text-red-500 text-sm mt-1">{{ form.errors.audio_file }}</div>
          <!-- Barre de progression Inertia pendant l'envoi -->
          <progress v-if="form.progress" :value="form.progress.percentage" max="100" class="w-full mt-2">
            {{ form.progress.percentage }}%
          </progress>
      </div>
      <!-- Album -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Album</label><!-- Note: ajusté selon votre logique d'id -->
        <div class="relative mt-2">
            <LucideAlbum
                class="
                    absolute
                    left-4
                    top-1/2
                    -translate-y-1/2
                    w-5
                    h-5
                    text-gray-500
                "
            />
            <select v-model="form.album_id" class="w-full text-white border  border-[#2e2e2e]  rounded-md  pl-12  pr-2  py-2  outline-none  focus:border-[#33437e] transition-all cursor-pointer">
              <option value="" class="bg-[#33437e]/50 text-black" selected disabled>Sélectionnez un album</option>
              <option v-for="album in albums" :key="album.id" :value="album.id" class="bg-[#33437e]/50 text-black">
                {{ album.title }}
              </option>
            </select>
        </div>
        
        <div v-if="form.errors.album_id" class="text-red-500 text-sm mt-1">{{ form.errors.album_id }}</div>
      </div>

      <!-- Titre -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Titre de la musique</label>
        <div class="relative mt-2">
            <Disc3Icon
                class="
                    absolute
                    left-4
                    top-1/2
                    -translate-y-1/2
                    w-5
                    h-5
                    text-gray-500
                "
            />
            <input
                v-model="form.title"
                type="text"
                placeholder="Ex : Repela"
                class="w-full border border-[#2e2e2e] focus:border-[#33437e] rounded-md pl-12 pr-2  py-2  outline-none   transition-all
                "
            />
        </div>
        <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
      </div>
      <!-- BUTTON -->
      <button
          type="submit"
          :disabled="form.processing"
          class="bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all duration-300 px-6 py-2 rounded-full text-sm font-bold flex items-center justify-center gap-2 w-max cursor-pointer disabled:opacity-50
          "
      >
          {{
              form.processing
                  ? 'Création...'
                  : 'Créer l’artiste'
          }}
      </button>
    </form>
  </div>
</template>
