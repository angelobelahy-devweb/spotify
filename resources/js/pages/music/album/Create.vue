<script setup>
import { useForm, Link, usePage } from '@inertiajs/vue3'; // Importation de usePage
import { reactive, watch } from 'vue';
import { Image, ArrowLeft } from 'lucide-vue-next';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast'; // Importation du composant Toast de PrimeVue



// Initialisation du Toast de PrimeVue
const toast = useToast();

// Initialisation du formulaire avec tous les champs de la migration
const form = useForm({
  artist_id: '', // Pensez à lier l'artiste dans votre formulaire !
  title: null,
  release_year: null,
  image: null,
  is_free: false,
  price: null,
});

// Aperçu de l'image
const preview = reactive({
    image: '',
});

const handleImage = (e) => {
    const target = e.target;
    if (target.files && target.files[0]) {
        form.image = target.files[0];
        preview.image = URL.createObjectURL(target.files[0]);
    }
};

// Si l'album devient gratuit, on force le prix à vide ou 0
watch(() => form.is_free, (newValue) => {
    if (newValue) {
        form.price = '';
    }
});

const submit = () => {
  form.post('/albums/store', {
        forceFormData: true,
        onSuccess: () => {
            // 1. On récupère le message flash envoyé par le contrôleur Laravel
            const successMessage = usePage().props.flash?.success;
            
            // 2. Syntaxe PrimeVue pour afficher un toast de succès
            toast.add({ 
                severity: 'success', 
                summary: 'Succès', 
                detail: successMessage || 'L\'album a été créé avec succès !', 
                life: 3000 
            });

            // 3. Réinitialisation complète du formulaire et de l'aperçu de la photo
            form.reset();
            preview.image = '';
        },
        onError: () => {
            // Syntaxe PrimeVue pour afficher un toast d'erreur
            toast.add({ 
                severity: 'error', 
                summary: 'Erreur', 
                detail: 'Veuillez corriger les erreurs du formulaire.', 
                life: 3000 
            });
        }
    });
};
</script>

<template>
  <!-- Le composant Toast doit être présent pour afficher les notifications -->
  <Toast />

  <div class="border border-[#33437e] backdrop-blur-md max-w-md mx-auto mt-10 p-6 bg-black/40 rounded-lg shadow flex flex-col items-center justify-center text-white">
    
    <div class="w-full flex items-center justify-between mb-6">
        <Link href="/albums" class="text-gray-400 hover:text-white flex items-center gap-1 text-sm">
            <ArrowLeft class="w-4 h-4" /> Retour
        </Link>
        <h1 class="text-xl font-bold">Ajouter un Album</h1>
    </div>
    
    <form @submit.prevent="submit" class="w-full">
      
      <!-- IMAGE -->
      <div class="flex flex-col items-center gap-2 mb-6">
          <label class="relative w-30 h-30 rounded-xl overflow-hidden cursor-pointer group border-2 border-dashed border-[#3b3b3b] hover:border-[#33437e] transition-all flex items-center justify-center">
              <img v-if="preview.image" :src="preview.image" class="w-full h-full object-cover" />
              <Image v-else class="w-12 h-12 text-gray-500" />
              <input type="file" class="hidden" accept="image/*" @change="handleImage" />
          </label>
          <p class="text-gray-400 text-xs">Pochette de l'album</p>
          <div v-if="form.errors.image" class="text-red-500 text-xs mt-1">{{ form.errors.image }}</div>
      </div>

      <!-- Titre de l'album -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Titre de l'album</label>
        <input v-model="form.title" type="text" class="w-full bg-transparent border border-[#2e2e2e] rounded-md p-2 outline-none focus:border-[#33437e] transition-all" />
        <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
      </div>

      <!-- Date de sortie -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Date de sortie</label>
        <input v-model="form.release_year" type="date" class="w-full bg-transparent border border-[#2e2e2e] rounded-md p-2 outline-none focus:border-[#33437e] transition-all text-white" />
        <div v-if="form.errors.release_year" class="text-red-500 text-sm mt-1">{{ form.errors.release_year }}</div>
      </div>

      <!-- Album Gratuit ou Payant -->
      <div class="mb-4 flex items-center gap-2">
        <input v-model="form.is_free" type="checkbox" id="is_free" class="checkbox checkbox-primary border border-[#33437e]"/>
        <label for="is_free" class="text-sm font-medium select-none cursor-pointer">Cet album est gratuit</label>
      </div>

      <!-- Prix -->
      <div v-if="!form.is_free" class="mb-6">
        <label class="block text-sm font-medium mb-1">Prix (€)</label>
        <input v-model="form.price" type="number" step="0.01" class="w-full bg-transparent border border-[#2e2e2e] rounded-md p-2 outline-none focus:border-[#33437e] transition-all" />
        <div v-if="form.errors.price" class="text-red-500 text-sm mt-1">{{ form.errors.price }}</div>
      </div>

      <!-- Bouton de validation -->
      <button type="submit" :disabled="form.processing" class="bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all duration-300 px-6 py-2 rounded-full text-sm font-bold flex items-center justify-center gap-2 cursor-pointer w-[max-content] disabled:opacity-50">
          <span v-if="form.processing">Création en cours...</span>
          <span v-else>Créer l'album</span>
      </button>
      
    </form>
  </div>
</template>
