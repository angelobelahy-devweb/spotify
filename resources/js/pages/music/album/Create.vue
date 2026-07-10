<script setup>
import { useForm, Link, usePage } from '@inertiajs/vue3';
import { reactive, watch, computed } from 'vue';
import { Image, ArrowLeft } from 'lucide-vue-next';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';

const props = defineProps({
    artists: Array,
    isArtist: Boolean,
    isSubscriptionActive: Boolean,
    albumCount: Number,
    albumLimit: Number,
})

// Déterminer si l'artiste a le droit de publier
const hasReachedLimit = computed(() => props.albumCount >= props.albumLimit)

const canPublish = computed(() => {
    return props.isArtist && props.isSubscriptionActive && !hasReachedLimit.value
})

// Initialisation du Toast de PrimeVue
const toast = useToast();

// Initialisation du formulaire (Nettoyé du champ inutile artist_id)
const form = useForm({
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

// Si l'album devient gratuit, on force le prix à vide
watch(() => form.is_free, (newValue) => {
    if (newValue) {
        form.price = '';
    }
});

const submit = () => {
    form.post('/albums/store', {
        forceFormData: true,
        // ✅ CORRECTION : On extrait "page" directement envoyé en paramètre par Inertia
        onSuccess: (page) => {
            // 1. Lecture du message flash envoyé par le contrôleur sans casser le contexte de Vue
            const successMessage = page.props.flash?.success;

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
  <!-- Message d'erreur global si blocage -->
    <div v-if="!canPublish" class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6 flex items-start gap-3">
        <div class="text-red-400 mt-0.5">
            <!-- Icône d'alerte de ton choix -->
            <span class="font-bold">⚠️ Attention :</span>
        </div>
        <div class="text-sm text-gray-300">
            <p v-if="!isArtist">Vous devez posséder un profil artiste validé pour publier un album.</p>
            <p v-else-if="!isSubscriptionActive">Votre abonnement est expiré. Veuillez régulariser votre paiement.</p>
            <p v-else-if="hasReachedLimit">
                Vous avez atteint le quota maximal de votre forfait (<span class="text-white font-semibold">{{ albumCount }}/{{ albumLimit }} albums</span>). Passez au niveau supérieur pour publier davantage !
            </p>
        </div>
    </div>

  <div class="border border-[#33437e] backdrop-blur-md max-w-md mx-auto mt-10 p-6 bg-black/40 rounded-lg shadow flex flex-col items-center justify-center text-white w-[500px]">

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

      <!-- Ton bouton de soumission (Submit Button) -->
    <button
        type="submit"
        :disabled="!canPublish || form.processing"
        class="w-full py-3 px-6 rounded-full font-bold text-sm transition-all duration-200 flex items-center justify-center gap-2"
        :class="canPublish && !form.processing
            ? 'bg-[#33437e] hover:bg-[#33437e]/90 text-white cursor-pointer hover:-translate-y-0.5 shadow-lg'
            : 'bg-gray-800 text-gray-500 cursor-not-allowed opacity-60'"
    >
        <span v-if="form.processing">Création en cours...</span>
        <span v-else>Publier l'album</span>
    </button>

    </form>
  </div>
</template>
