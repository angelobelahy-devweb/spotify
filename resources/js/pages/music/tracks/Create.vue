<script setup>
import { useForm, Link, usePage } from '@inertiajs/vue3';
import { ArrowLeft, Disc3Icon, LucideAlbum, Music, Tags } from 'lucide-vue-next';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import { Spinner } from '@/components/ui/spinner';
import { ref } from 'vue';

defineProps({
  albums: Array,
  genres: Array,
});

const toast = useToast();
const isAudioLoading = ref(false);
const selectedFileName = ref('');

const form = useForm({
  album_id: '',
  genre_id: '',
  title: '',
  duration: null,  
  audio_file: null,
  is_free: false,  
});

const handleFileChange = (event) => {
  const target = event.target;
  // Correction majeure : extraction sécurisée du premier fichier
  if (target.files && target.files[0]) {
      const file = target.files[0];
      form.audio_file = file; 
      selectedFileName.value = file.name;
      isAudioLoading.value = true;

      const audio = new Audio();
      audio.src = URL.createObjectURL(file);
      
      audio.addEventListener('loadedmetadata', () => {
          form.duration = Math.round(audio.duration); 
          isAudioLoading.value = false;
      });

      audio.addEventListener('error', () => {
          toast.add({
              severity: 'error',
              summary: 'Erreur audio',
              detail: 'Fichier corrompu ou illisible.',
              life: 3000
          });
          isAudioLoading.value = false;
          form.audio_file = null;
          selectedFileName.value = '';
      });
  }
};

const submit = () => {
    // Utilisation de la méthode manuelle pour garantir la construction du Multipart
    form.post('/tracks/store', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            const successMessage = usePage().props.flash?.success;
            toast.add({
                severity: 'success',
                summary: 'Succès',
                detail: successMessage || 'Musique ajoutée avec succès !',
                life: 3000
            });
            form.reset();
            selectedFileName.value = '';
        },
        onError: (err) => {
            console.error("Détails des erreurs :", err);
            toast.add({
                severity: 'error',
                summary: 'Erreur',
                detail: 'L\'envoi a échoué.',
                life: 3000
            });
        }
    });
};
</script>

<template>
  <Toast />

  <div class="w-[500px] backdrop-blur-md max-w-md mx-auto mt-10 p-6 bg-black/40 rounded-lg shadow flex flex-col items-center justify-center text-white  border border-[#33437e]">

    <div class="w-full flex items-center justify-between mb-6">
        <Link href="/tracks" class="text-gray-400 hover:text-white flex items-center gap-1 text-sm">
            <ArrowLeft class="w-4 h-4" /> Retour
        </Link>
        <h1 class="text-xl font-bold">Ajouter une Musique</h1>
    </div>

    <form @submit.prevent="submit" class="w-full">
      
      <!-- Fichier Audio -->
      <div class="flex flex-col items-center gap-4 mb-6">
          <label
              class="
                  relative
                  w-30
                  h-30
                  rounded-xl
                  overflow-hidden
                  cursor-pointer
                  group
                  border-2
                  border-dashed
                  border-[#3b3b3b]
                  hover:border-[#33437e]
                  transition-all
                  flex items-center justify-center
              "
          >
              <div class="text-center flex flex-col items-center justify-center p-2">
                  <Music :class="form.audio_file ? 'text-[#33437e]' : 'text-gray-500'" class="w-12 h-12" />
              </div>

              <input
                  type="file"
                  class="hidden"
                  @change="handleFileChange" 
                  accept="audio/*"
              />
          </label>
          
          <!--<p class="text-gray-400 text-xs text-center p-1">
              {{ form.audio_file ? `Sélectionné : ${form.audio_file.name}` : 'Ajouter un fichier Audio (MP3, WAV...)' }}
          </p>-->
          <p class="text-gray-400 text-xs text-center p-1">
            {{ selectedFileName ? `Sélectionné : ${selectedFileName}` : 'Ajouter un fichier Audio (MP3, WAV...)' }}
            </p>

          
          <div v-if="form.errors.audio_file" class="text-red-500 text-sm mt-1">{{ form.errors.audio_file }}</div>
          
          <div v-if="form.progress" class="w-full text-center mt-2">
            <progress :value="form.progress.percentage" max="100" class="w-full h-2 rounded bg-[#364a92]">
              {{ form.progress.percentage }}%
            </progress>
            <span class="text-xs text-gray-300 mt-1 block">{{ form.progress.percentage }}%</span>
          </div>
      </div>

      <!-- Album -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Album</label>
        <div class="relative mt-2">
            <LucideAlbum class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" />
            <select v-model="form.album_id" class="w-full text-white border border-[#2e2e2e] rounded-md pl-12 pr-2 py-2 outline-none focus:border-[#33437e] transition-all cursor-pointer ">
              <option value="" class="bg-[#121212] text-zinc-500 font-sans text-sm antialiased" disabled selected>Sélectionnez un album</option>
              <option v-for="album in albums" :key="album.id" :value="album.id" class="bg-[#33437e] text-zinc-100 font-sans text-sm tracking-wide antialiased">
                {{ album.title }}
              </option>
            </select>
        </div>
        <div v-if="form.errors.album_id" class="text-red-500 text-sm mt-1">{{ form.errors.album_id }}</div>
      </div>
      <!-- Genre -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Genre musical</label>
        <div class="relative mt-2">
            <Tags class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" />
            <select v-model="form.genre_id" class="w-full text-white border border-[#2e2e2e] rounded-md pl-12 pr-2 py-2 outline-none focus:border-[#33437e] transition-all cursor-pointer ">
            <!-- Option par défaut (Grise et discrète) -->
            <option value="" class="bg-[#121212] text-zinc-500 font-sans text-sm antialiased" disabled selected>
                Sélectionnez un genre
            </option>
            <!-- Options de la liste (Blanches et lisibles) -->
            <option v-for="genre in genres" :key="genre.id" :value="genre.id" class="bg-[#33437e] text-zinc-100 font-sans text-sm tracking-wide antialiased">
                {{ genre.name }}
            </option>
            </select>
        </div>

        <div v-if="form.errors.album_id" class="text-red-500 text-sm mt-1">{{ form.errors.album_id }}</div>
      </div>

      <!-- Titre -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Titre de la musique</label>
        <div class="relative mt-2">
            <Disc3Icon class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" />
            <input
                v-model="form.title"
                type="text"
                placeholder="Ex : Repela"
                class="w-full bg-transparent text-white border border-[#2e2e2e] focus:border-[#33437e] rounded-md pl-12 pr-2 py-2 outline-none transition-all"
            />
        </div>
        <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
      </div>

      <!-- Case à cocher : Musique Gratuite -->
      <div class="mb-6 flex items-center gap-2">
        <input 
            v-model="form.is_free"
            id="is_free_track"  
            type="checkbox" class="checkbox checkbox-primary border border-[#33437e]"
        />
        <label for="is_free_track" class="text-sm font-medium select-none cursor-pointer">
            Cette musique est gratuite
        </label>
      </div>

      <!-- BUTTON DESACTIVE TANT QUE L'AUDIO CHARGE -->
      <button
          type="submit"
          :disabled="form.processing || isAudioLoading"
          class="bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all duration-300 px-6 py-2 rounded-full text-sm font-bold flex items-center justify-center gap-2 w-[max-content] cursor-pointer disabled:opacity-50"
      >
            <span v-if="isAudioLoading" class="flex gap-2 items-center">
                <Spinner /> Analyse du fichier audio...
            </span>
            <span v-else-if="form.processing" class="flex gap-2 items-center">
                <Spinner /> Envoi en cours...
            </span>
            <span v-else>Créer musique</span>
      </button>
    </form>
  </div>
</template>
