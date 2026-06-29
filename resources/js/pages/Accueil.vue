<script setup>
import PrimaryButton from '@/components/angelo/button/PrimaryButton.vue';
import MusicCard from '@/components/angelo/cards/MusicCard.vue';
import { Inbox, Hourglass, ArrowUp, Facebook, Youtube, Linkedin, Instagram, Music, Search, X, ArrowLeft, ArrowRight } from 'lucide-vue-next';
import { ref, computed, watch, nextTick } from 'vue';
import { Link } from '@inertiajs/vue3';
import logo from '@/assets/images/jbl.png';

// Import Swiper
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import { Navigation } from 'swiper/modules';

const props = defineProps({
  tracks: Array,
  genres: Array,
});

// ========== FORMULAIRE DE CONTACT ==========
const form = ref({
    firstName: '',
    lastName: '',
    email: '',
    subject: '',
    message: '',
    consent: false
});

const submitForm = () => {
    console.log('Formulaire soumis :', form.value);
    alert('✅ Votre message a été envoyé avec succès !');
    form.value = {
        firstName: '',
        lastName: '',
        email: '',
        subject: '',
        message: '',
        consent: false
    };
};

// ========== FILTRES ==========
const selectedGenre = ref('all');
const searchQuery = ref('');
const searchInputRef = ref(null);

// Tracks filtrées
const filteredTracks = computed(() => {
  if (!props.tracks) return [];
  
  let result = props.tracks;
  
  // Filtre par genre
  if (selectedGenre.value !== 'all') {
    result = result.filter(track => 
      track.genres?.some(genre => String(genre.id) === String(selectedGenre.value))
    );
  }
  
  // Filtre par recherche
  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase().trim();
    result = result.filter(track => 
      track.title.toLowerCase().includes(query) ||
      track.album?.artist?.surname?.toLowerCase().includes(query) ||
      track.album?.title?.toLowerCase().includes(query)
    );
  }
  
  return result;
});

const filteredCount = computed(() => filteredTracks.value.length);

// ========== CONFIGURATION SWIPER ==========
const swiperConfig = computed(() => {
  const totalItems = filteredTracks.value.length;
  
  if (totalItems <= 1) {
    return {
      slidesPerView: 1,
      spaceBetween: 10,
      navigation: false,
      modules: [],
      slidesPerGroup: 1,
    };
  }
  
  return {
    modules: [Navigation],
    navigation: {
      enabled: totalItems > 3,
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    slidesPerView: 'auto',
    spaceBetween: 16,
    slidesPerGroup: 1,
    watchSlidesProgress: true,
    observer: true,
    observeParents: true,
    loop: false,
    grabCursor: true,
    resistance: true,
    resistanceRatio: 0.85,
    speed: 400,
    breakpoints: {
      0: {
        slidesPerView: 1,
        spaceBetween: 10,
      },
      640: {
        slidesPerView: 2,
        spaceBetween: 16,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 16,
      },
      1024: {
        slidesPerView: 4,
        spaceBetween: 16,
      },
      1280: {
        slidesPerView: 5,
        spaceBetween: 16,
      },
      1536: {
        slidesPerView: 6,
        spaceBetween: 16,
      },
    },
  };
});

const swiperKey = ref(0);

// Réinitialiser Swiper quand les filtres changent
watch([selectedGenre, searchQuery], async () => {
  await nextTick();
  swiperKey.value += 1;
});

// ========== FORMAT DURATION ==========
const formatDuration = (seconds) => {
    if (!seconds && seconds !== 0) return '0:00';
    const min = Math.floor(seconds / 60);
    const sec = seconds % 60;
    return `${min}:${sec.toString().padStart(2, '0')}`;
};

// ========== SCROLL TOP ==========
const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    const heroSection = document.querySelector('section');
    if (heroSection) {
        heroSection.scrollIntoView({ behavior: 'smooth' });
    }
};

// ========== NETTOYER LA RECHERCHE ==========
const clearSearch = () => {
    searchQuery.value = '';
    if (searchInputRef.value) {
        searchInputRef.value.focus();
    }
};
</script>

<template>
  <div class="home-container">
    <!-- ========== HERO BIENVENUE ========== -->
    <section class="relative text-white py-8 px-4 overflow-hidden mb-2 rounded-2xl">
      <div class="absolute inset-0 bg-gradient-to-br from-[#0a0a0ad5] via-[#1a1a2ed2] to-[#0f3360c9]"></div>
      <div class="absolute inset-0 opacity-30">
        <div class="absolute top-0 right-0 w-96 h-96 bg-[#33437e] rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-[#5d6b9c] rounded-full blur-3xl"></div>
      </div>

      <div class="relative z-10 max-w-5xl mx-auto text-center">
        <div class="absolute top-0 right-0 w-10">
          <Link href="/" class="flex items-center gap-2 text-white font-bold text-xl tracking-tight hover:text-primary transition duration-200">
            <img :src="logo" alt="logo" class="w-8">
          </Link>
        </div>

        <span class="inline-block bg-[#33437e]/20 backdrop-blur-sm px-4 py-1 rounded-full text-xs font-semibold tracking-wider uppercase border border-[#33437e]/30 mb-4">
          <i class="fa-regular fa-gem mr-2 text-[#33437e]"></i> Bienvenue sur MyLife
        </span>

        <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight">
          La musique <br/>
          <span class="bg-gradient-to-r from-[#33437e] via-[#5d6b9c] to-[#33437e] bg-clip-text text-transparent">
            n'a jamais été
          </span>
          <br/>
          <span class="text-white">aussi bonne</span>
        </h1>

        <p class="text-base md:text-lg text-gray-300 max-w-2xl mx-auto mt-4">
          Découvrez une expérience musicale sans limites. Écoutez en haute qualité, sans publicité et où que vous soyez.
        </p>

        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-3xl mx-auto">
          <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
            <div class="text-2xl font-bold text-white">100M+</div>
            <div class="text-xs text-gray-400">Titres disponibles</div>
          </div>
          <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
            <div class="text-2xl font-bold text-white">5M+</div>
            <div class="text-xs text-gray-400">Podcasts exclusifs</div>
          </div>
          <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
            <div class="text-2xl font-bold text-white">184</div>
            <div class="text-xs text-gray-400">Pays couverts</div>
          </div>
          <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
            <div class="text-2xl font-bold text-white">99%</div>
            <div class="text-xs text-gray-400">Satisfaction client</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ========== FILTRES ET RECHERCHE ========== -->
    <div class="relative">
      <div class="flex flex-wrap gap-4 items-center mb-4">
        <!-- Filtre genre -->
        <div class="relative">
          <select 
            v-model="selectedGenre" 
            class="select select-[#33437e] cursor-pointer bg-[#33437e] select-sm outline-none border-none"
          >
            <option value="all" class="bg-[#33437e]">Tous les genres</option>
            <option v-for="genre in genres" :key="genre.id" :value="String(genre.id)" class="bg-[#33437e]">
              {{ genre.name }}
            </option>
          </select>
        </div>

        <!-- Barre de recherche -->
        <div class="relative flex-1 min-w-[200px]">
          <div class="relative">
            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <input 
              ref="searchInputRef"
              v-model="searchQuery"
              type="text"
              placeholder="Rechercher un titre, un artiste ou un album..."
              class="w-full bg-white/5 border border-white/10 rounded-lg pl-10 pr-10 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-[#33437e] focus:ring-2 focus:ring-[#33437e]/30 transition"
            />
            <button 
              v-if="searchQuery"
              @click="clearSearch"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition"
            >
              <X class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>

      <!-- Compteur -->
      <div class="text-sm text-gray-400 mb-2">
        {{ filteredCount }} résultat{{ filteredCount > 1 ? 's' : '' }}
      </div>

      <!-- ========== SECTION NOUVEAUTÉ ========== -->
      <h1 class="text-[#e4e8f3d0] text-2xl my-4 font-semibold">Nouveauté</h1>
      <div class="w-full p-2">
        <!-- 0 résultat -->
        <div v-if="filteredTracks.length === 0" class="text-center py-12">
          <Music class="w-16 h-16 text-gray-500 mx-auto mb-4" />
          <p class="text-gray-400 text-lg">Aucune musique trouvée</p>
          <p class="text-gray-500 text-sm">Essayez de modifier votre recherche ou votre filtre</p>
        </div>

        <!-- 1 item -->
        <div v-else-if="filteredTracks.length === 1" class="flex justify-center">
          <div class="w-full max-w-xs">
            <MusicCard
              :title="filteredTracks[0].title"
              :artist="filteredTracks[0].album.artist.surname"
              :duration="formatDuration(filteredTracks[0].duration)"
              :album="filteredTracks[0].album.title"
              :genre="filteredTracks[0].genres?.[0]?.name ?? 'N/A'"
              :comment="`/comment/${filteredTracks[0].slug}`"
              :image="`/storage/${filteredTracks[0].album.image}`"
              class="w-full"
            />
          </div>
        </div>

        <!-- 2 items -->
        <div v-else-if="filteredTracks.length === 2" class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
          <MusicCard
            v-for="track in filteredTracks"
            :key="track.id"
            :title="track.title"
            :artist="track.album.artist.surname"
            :duration="formatDuration(track.duration)"
            :album="track.album.title"
            :genre="track.genres?.[0]?.name ?? 'N/A'"
            :comment="`/comment/${track.slug}`"
            :image="`/storage/${track.album.image}`"
            class="w-full"
          />
        </div>

        <!-- 3+ items - Swiper -->
        <div v-else class="swiper-wrapper-container">
          <div class="relative swiper-container">
            <Swiper 
              :key="swiperKey"
              v-bind="swiperConfig"
              class="main-swiper"
            >
              <SwiperSlide 
                v-for="track in filteredTracks" 
                :key="track.id"
                class="swiper-slide-item"
              >
                <MusicCard
                  :title="track.title"
                  :artist="track.album.artist.surname"
                  :duration="formatDuration(track.duration)"
                  :album="track.album.title"
                  :genre="track.genres?.[0]?.name ?? 'N/A'"
                  :comment="`/comment/${track.slug}`"
                  :image="`/storage/${track.album.image}`"
                  class="music-card-item"
                />
              </SwiperSlide>
            </Swiper>

            <!-- Boutons de navigation -->
            <button v-if="filteredTracks.length > 3" class="bg-[#33437e] p-2 rounded-full absolute top-1/2 left-0 transform -translate-y-1/2 z-10 hover:scale-100 active:scale-90 transition-all cursor-pointer swiper-button-prev">
                <ArrowLeft class="w-5 h-5 text-white" />
            </button>
            <button v-if="filteredTracks.length > 3" class="bg-[#33437e] p-2 rounded-full absolute top-1/2 right-0 transform -translate-y-1/2 z-10 hover:scale-100 active:scale-90 transition-all cursor-pointer swiper-button-next">
                <ArrowRight class="w-5 h-5 text-white" />
            </button>
          </div>
        </div>
      </div>

      <!-- ========== SECTION POPULAIRES ========== -->
      <h1 class="text-[#e4e8f3d0] text-2xl my-4 font-semibold mt-8">Populaires</h1>
      <div class="w-full p-2">
        <div v-if="filteredTracks.length === 0" class="text-center py-12">
          <Music class="w-16 h-16 text-gray-500 mx-auto mb-4" />
          <p class="text-gray-400 text-lg">Aucune musique trouvée</p>
          <p class="text-gray-500 text-sm">Essayez de modifier votre recherche ou votre filtre</p>
        </div>
        <div v-else-if="filteredTracks.length === 1" class="flex justify-center">
          <div class="w-full max-w-xs">
            <MusicCard
              :title="filteredTracks[0].title"
              :artist="filteredTracks[0].album.artist.surname"
              :duration="formatDuration(filteredTracks[0].duration)"
              :album="filteredTracks[0].album.title"
              :genre="filteredTracks[0].genres?.[0]?.name ?? 'N/A'"
              :comment="`/comment/${filteredTracks[0].slug}`"
              :image="`/storage/${filteredTracks[0].album.image}`"
              class="w-full"
            />
          </div>
        </div>
        <div v-else-if="filteredTracks.length === 2" class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
          <MusicCard
            v-for="track in filteredTracks"
            :key="track.id"
            :title="track.title"
            :artist="track.album.artist.surname"
            :duration="formatDuration(track.duration)"
            :album="track.album.title"
            :genre="track.genres?.[0]?.name ?? 'N/A'"
            :comment="`/comment/${track.slug}`"
            :image="`/storage/${track.album.image}`"
            class="w-full"
          />
        </div>
        <div v-else class="swiper-wrapper-container">
          <div class="relative swiper-container">
            <Swiper 
              :key="swiperKey + '-pop'"
              v-bind="swiperConfig"
              class="main-swiper"
            >
              <SwiperSlide 
                v-for="track in filteredTracks" 
                :key="track.id"
                class="swiper-slide-item"
              >
                <MusicCard
                  :title="track.title"
                  :artist="track.album.artist.surname"
                  :duration="formatDuration(track.duration)"
                  :album="track.album.title"
                  :genre="track.genres?.[0]?.name ?? 'N/A'"
                  :comment="`/comment/${track.slug}`"
                  :image="`/storage/${track.album.image}`"
                  class="music-card-item"
                />
              </SwiperSlide>
            </Swiper>
            <button v-if="filteredTracks.length > 3" class="bg-[#33437e] p-2 rounded-full absolute top-1/2 left-0 transform -translate-y-1/2 z-10 hover:scale-100 active:scale-90 transition-all cursor-pointer swiper-button-prev">
                <ArrowLeft class="w-5 h-5 text-white" />
            </button>
            <button v-if="filteredTracks.length > 3" class="bg-[#33437e] p-2 rounded-full absolute top-1/2 right-0 transform -translate-y-1/2 z-10 hover:scale-100 active:scale-90 transition-all cursor-pointer swiper-button-next">
                <ArrowRight class="w-5 h-5 text-white" />
            </button>
          </div>
        </div>
      </div>

      <!-- ========== SECTION SUGGESTIONS ========== -->
      <h1 class="text-[#e4e8f3d0] text-2xl my-4 font-semibold mt-8">Suggestions</h1>
      <div class="w-full p-2">
        <div v-if="filteredTracks.length === 0" class="text-center py-12">
          <Music class="w-16 h-16 text-gray-500 mx-auto mb-4" />
          <p class="text-gray-400 text-lg">Aucune musique trouvée</p>
          <p class="text-gray-500 text-sm">Essayez de modifier votre recherche ou votre filtre</p>
        </div>
        <div v-else-if="filteredTracks.length === 1" class="flex justify-center">
          <div class="w-full max-w-xs">
            <MusicCard
              :title="filteredTracks[0].title"
              :artist="filteredTracks[0].album.artist.surname"
              :duration="formatDuration(filteredTracks[0].duration)"
              :album="filteredTracks[0].album.title"
              :genre="filteredTracks[0].genres?.[0]?.name ?? 'N/A'"
              :comment="`/comment/${filteredTracks[0].slug}`"
              :image="`/storage/${filteredTracks[0].album.image}`"
              class="w-full"
            />
          </div>
        </div>
        <div v-else-if="filteredTracks.length === 2" class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
          <MusicCard
            v-for="track in filteredTracks"
            :key="track.id"
            :title="track.title"
            :artist="track.album.artist.surname"
            :duration="formatDuration(track.duration)"
            :album="track.album.title"
            :genre="track.genres?.[0]?.name ?? 'N/A'"
            :comment="`/comment/${track.slug}`"
            :image="`/storage/${track.album.image}`"
            class="w-full"
          />
        </div>
        <div v-else class="swiper-wrapper-container">
          <div class="relative swiper-container">
            <Swiper 
              :key="swiperKey + '-sug'"
              v-bind="swiperConfig"
              class="main-swiper"
            >
              <SwiperSlide 
                v-for="track in filteredTracks" 
                :key="track.id"
                class="swiper-slide-item"
              >
                <MusicCard
                  :title="track.title"
                  :artist="track.album.artist.surname"
                  :duration="formatDuration(track.duration)"
                  :album="track.album.title"
                  :genre="track.genres?.[0]?.name ?? 'N/A'"
                  :comment="`/comment/${track.slug}`"
                  :image="`/storage/${track.album.image}`"
                  class="music-card-item"
                />
              </SwiperSlide>
            </Swiper>
            <button v-if="filteredTracks.length > 3" class="bg-[#33437e] p-2 rounded-full absolute top-1/2 left-0 transform -translate-y-1/2 z-10 hover:scale-100 active:scale-90 transition-all cursor-pointer swiper-button-prev">
                <ArrowLeft class="w-5 h-5 text-white" />
            </button>
            <button v-if="filteredTracks.length > 3" class="bg-[#33437e] p-2 rounded-full absolute top-1/2 right-0 transform -translate-y-1/2 z-10 hover:scale-100 active:scale-90 transition-all cursor-pointer swiper-button-next">
                <ArrowRight class="w-5 h-5 text-white" />
            </button>
          </div>
        </div>
      </div>

      <!-- ========== CONTACT ========== -->
      <section class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <span class="inline-block bg-[#33437e]/10 backdrop-blur-sm px-4 py-1 rounded-full text-xs font-semibold tracking-wider uppercase border border-[#33437e]/20 mb-4">
            <i class="fa-regular fa-envelope mr-2 text-[#33437e]"></i> Contactez-nous
          </span>
          <h2 class="text-3xl md:text-4xl font-bold">
            Une question ? <span class="text-[#33437e]">Écrivez-nous</span>
          </h2>
          <p class="text-gray-400 mt-2">Notre équipe vous répond dans les plus brefs délais</p>
        </div>

        <div class="max-w-4xl mx-auto">
          <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 relative w-full">
            <!-- Formulaire -->
            <div class="lg:col-span-3 bg-black/80 backdrop-blur-sm rounded-2xl p-4 md:p-6 border border-white/10">
              <form @submit.prevent="submitForm" class="space-y-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                  <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">
                      Prénom <span class="text-[#33437e]">*</span>
                    </label>
                    <input
                      type="text"
                      v-model="form.firstName"
                      placeholder="Jean"
                      class="w-full bg-white/5 border border-white/10 rounded-lg px-2 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-[#33437e] focus:ring-2 focus:ring-[#33437e]/30 transition"
                      required
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">
                      Nom <span class="text-[#33437e]">*</span>
                    </label>
                    <input
                      type="text"
                      v-model="form.lastName"
                      placeholder="Dupont"
                      class="w-full bg-white/5 border border-white/10 rounded-lg px-2 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-[#33437e] focus:ring-2 focus:ring-[#33437e]/30 transition"
                      required
                    />
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-1.5">
                    Email <span class="text-[#33437e]">*</span>
                  </label>
                  <input
                    type="email"
                    v-model="form.email"
                    placeholder="jean.dupont@email.com"
                    class="w-full bg-white/5 border border-white/10 rounded-lg px-2 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-[#33437e] focus:ring-2 focus:ring-[#33437e]/30 transition"
                    required
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-1.5">
                    Sujet <span class="text-[#33437e]">*</span>
                  </label>
                  <select
                    v-model="form.subject"
                    class="w-full bg-white/5 border border-white/10 rounded-lg px-2 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-[#33437e] focus:ring-2 focus:ring-[#33437e]/30 transition appearance-none cursor-pointer"
                    required
                  >
                    <option value="" class="bg-[#1a1a2e]">Choisissez un sujet</option>
                    <option value="question" class="bg-[#1a1a2e]">Question sur l'abonnement</option>
                    <option value="support" class="bg-[#1a1a2e]">Support technique</option>
                    <option value="feedback" class="bg-[#1a1a2e]">Feedback / Suggestion</option>
                    <option value="partnership" class="bg-[#1a1a2e]">Partenariat</option>
                    <option value="other" class="bg-[#1a1a2e]">Autre</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-1.5">
                    Message <span class="text-[#33437e]">*</span>
                  </label>
                  <textarea
                    v-model="form.message"
                    rows="4"
                    placeholder="Décrivez votre demande en détails..."
                    class="w-full bg-white/5 border border-white/10 rounded-lg px-2 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-[#33437e] focus:ring-2 focus:ring-[#33437e]/30 transition resize-none"
                    required
                  ></textarea>
                </div>

                <div class="flex items-center gap-2">
                  <input
                    type="checkbox"
                    v-model="form.consent"
                    class="w-4 h-4 bg-white/5 border border-white/20 rounded focus:ring-[#33437e] focus:ring-2 text-[#33437e] cursor-pointer"
                    required
                  />
                  <label class="text-sm text-gray-400">
                    J'accepte que mes données soient traitées conformément à la
                    <a href="#" class="text-[#33437e] hover:underline">politique de confidentialité</a>
                  </label>
                </div>

                <button
                  type="submit"
                  class="w-full bg-[#33437e] hover:bg-[#324588] text-white font-bold py-3 rounded-lg transition flex items-center justify-center gap-2 shadow-lg shadow-[#33437e]/30 group"
                >
                  <i class="fa-regular fa-paper-plane group-hover:translate-x-1 transition"></i>
                  Envoyer le message
                </button>
              </form>
            </div>

            <div class="lg:col-span-2 space-y-6">
              <div class="bg-black/80 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                <h3 class="font-bold text-lg mb-4">Nous contacter</h3>
                <div class="space-y-4">
                  <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#33437e]/20 flex items-center justify-center text-[#33437e] flex-shrink-0 mt-0.5">
                      <Inbox class="w-5 h-5" />
                    </div>
                    <div>
                      <p class="text-sm text-gray-400">Email</p>
                      <p class="text-sm font-medium">contact@spotify-premium.com</p>
                    </div>
                  </div>
                  <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#33437e]/20 flex items-center justify-center text-[#33437e] flex-shrink-0 mt-0.5">
                      <Hourglass class="w-5 h-5" />
                    </div>
                    <div>
                      <p class="text-sm text-gray-400">Délai de réponse</p>
                      <p class="text-sm font-medium">Sous 24 heures</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="bg-black/80 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                <h3 class="font-bold text-lg mb-4">Suivez-nous</h3>
                <div class="flex gap-3">
                  <a href="#" class="w-10 h-10 rounded-full bg-white/5 hover:bg-[#33437e]/20 border border-white/10 flex items-center justify-center transition">
                    <Facebook class="w-4 h-4 text-gray-300 hover:text-white" />
                  </a>
                  <a href="#" class="w-10 h-10 rounded-full bg-white/5 hover:bg-[#33437e]/20 border border-white/10 flex items-center justify-center transition">
                    <Linkedin class="w-4 h-4 text-gray-300 hover:text-white" />
                  </a>
                  <a href="#" class="w-10 h-10 rounded-full bg-white/5 hover:bg-[#33437e]/20 border border-white/10 flex items-center justify-center transition">
                    <Youtube class="w-4 h-4 text-gray-300 hover:text-white" />
                  </a>
                  <a href="#" class="w-10 h-10 rounded-full bg-white/5 hover:bg-[#33437e]/20 border border-white/10 flex items-center justify-center transition">
                    <Instagram class="w-4 h-4 text-gray-300 hover:text-white" />
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ========== SCROLL TOP ========== -->
      <div class="w-full flex justify-end">
        <button 
          class="cursor-pointer h-10 w-10 rounded-full bg-[#070f2e] p-2 text-[#33437e] backdrop-blur-sm transition hover:bg-[#070f2e]/80"
          @click="scrollToTop" 
          aria-label="Retour en haut"
        >
          <ArrowUp class="h-full w-full" />
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Conteneur principal */
.home-container {
  overflow-x: hidden !important;
  max-width: 100%;
}

/* Styles Swiper */
.swiper-wrapper-container {
  overflow: hidden !important;
  max-width: 100%;
  padding: 0 10px;
  margin: 0 auto;
  position: relative;
}

.swiper-container {
  position: relative;
  overflow: hidden !important;
  max-width: 100%;
  margin: 0 auto;
  padding: 10px 0;
}

.main-swiper {
  overflow: hidden !important;
  max-width: 100%;
  padding: 5px 0;
}

.swiper-slide-item {
  display: flex !important;
  justify-content: center !important;
  align-items: center !important;
  height: auto !important;
  padding: 4px !important;
  max-width: 100% !important;
  flex-shrink: 0 !important;
}

.music-card-item {
  width: 100%;
  min-width: 180px;
  max-width: 220px;
  flex-shrink: 0;
}

/* Boutons de navigation */
.swiper-button-prev,
.swiper-button-next {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 44px;
  height: 44px;
  background: rgba(51, 67, 126, 0.85);
  color: white;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  transition: all 0.3s ease;
  backdrop-filter: blur(4px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  z-index: 10;
}

.swiper-button-prev {
  left: 0;
}

.swiper-button-next {
  right: 0;
}

.swiper-button-prev:hover,
.swiper-button-next:hover {
  background: rgba(51, 67, 126, 1);
  transform: translateY(-50%) scale(1.1);
}

/* Responsive */
@media (max-width: 640px) {
  .swiper-wrapper-container {
    padding: 0 5px;
  }
  
  .swiper-button-prev,
  .swiper-button-next {
    width: 32px;
    height: 32px;
    font-size: 14px;
  }
  
  .swiper-button-prev {
    left: -5px;
  }
  
  .swiper-button-next {
    right: -5px;
  }
  
  .swiper-slide-item {
    padding: 2px !important;
  }
  
  .music-card-item {
    min-width: 140px;
    max-width: 160px;
  }
}

@media (min-width: 641px) and (max-width: 1024px) {
  .music-card-item {
    min-width: 160px;
    max-width: 200px;
  }
}

@media (min-width: 1025px) {
  .music-card-item {
    min-width: 180px;
    max-width: 220px;
  }
}
</style>