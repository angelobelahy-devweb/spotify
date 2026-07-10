<script setup>
import PrimaryButton from '@/components/angelo/button/PrimaryButton.vue';
import MusicCard from '@/components/angelo/cards/MusicCard.vue';
import { Inbox, Hourglass, ArrowUp, Facebook, Youtube, Linkedin, Instagram, Music, Search, X, ArrowLeft, ArrowRight } from 'lucide-vue-next';
import { ref, computed, watch, nextTick } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { playerStore } from '@/lib/playerStore';
import { openAuthModal } from '@/lib/authModalStore';
import logo from '@/assets/images/font-pers.png';

// Import Swiper
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import { Navigation } from 'swiper/modules';

const props = defineProps({
  tracks: Array,
  genres: Array,
  stats: Object,
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

const getTopGenreIds = (tracks, limit = 3) => {
  const genreCounts = tracks.reduce((acc, track) => {
    track.genres?.forEach((genre) => {
      const id = String(genre.id);
      acc[id] = (acc[id] || 0) + 1;
    });
    return acc;
  }, {});

  return Object.entries(genreCounts)
    .sort(([, aCount], [, bCount]) => bCount - aCount)
    .slice(0, limit)
    .map(([genreId]) => genreId);
};

const favoriteTrackIds = computed(() => {
  return new Set((page.props.favorites ?? []).map((favorite) => String(favorite.id)));
});

const favoriteGenreIds = computed(() => {
  if (!props.tracks) return new Set();

  return new Set(
    props.tracks
      .filter((track) => favoriteTrackIds.value.has(String(track.id)))
      .flatMap((track) => track.genres?.map((genre) => String(genre.id)) || [])
  );
});

const nouveautes = computed(() => {
  if (!props.tracks) return [];

  const now = new Date();
  const recentThreshold = new Date(now);
  recentThreshold.setDate(now.getDate() - 30);

  return filteredTracks.value
    .filter((track) => {
      const createdAt = new Date(track.created_at);
      return !Number.isNaN(createdAt.getTime()) && createdAt >= recentThreshold;
    })
    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

const populaires = computed(() => {
  if (!props.tracks) return [];

  return [...filteredTracks.value]
    .filter((track) => (track.favorites_count || 0) >= 5)
    .sort((a, b) => (b.favorites_count || 0) - (a.favorites_count || 0));
});

const suggestions = computed(() => {
  if (!props.tracks) return [];

  const excludedIds = new Set([
    ...nouveautes.value.map((track) => track.id),
    ...populaires.value.map((track) => track.id),
  ]);

  const remaining = filteredTracks.value.filter((track) => !excludedIds.has(track.id));
  const lovedGenres = favoriteGenreIds.value.size > 0 ? favoriteGenreIds.value : new Set(getTopGenreIds(filteredTracks.value, 3));

  return remaining
    .filter((track) =>
      track.genres?.some((genre) => lovedGenres.has(String(genre.id)))
    );
});

const filteredCount = computed(() => filteredTracks.value.length);

const availableTracksCount = computed(() => {
  if (props.stats?.tracks != null) {
    return props.stats.tracks;
  }
  return props.tracks?.length ?? 0;
});

const artistCount = computed(() => {
  if (props.stats?.artists != null) {
    return props.stats.artists;
  }
  return 0;
});

const albumsCount = computed(() => {
  if (props.stats?.albums != null) {
    return props.stats.albums;
  }
  return 0;
});

const genresCount = computed(() => {
  if (props.stats?.genres != null) {
    return props.stats.genres;
  }
  return props.genres?.length ?? 0;
});

const page = usePage();
const isLoggedIn = computed(() => Boolean(page.props.auth.user));

const handlePlayTrack = (track, playlist = []) => {
  if (!isLoggedIn.value) {
    openAuthModal('Veuillez vous connecter pour lancer la musique.');
    return;
  }

  if (!track) return;
  playerStore.play(track, playlist);
};

const getSwiperConfig = (sectionName, totalItems) => {
  const baseConfig = {
    modules: [Navigation],
    navigation: {
      enabled: totalItems > 3,
      nextEl: `.swiper-button-next-${sectionName}`,
      prevEl: `.swiper-button-prev-${sectionName}`,
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

  if (totalItems <= 1) {
    return {
      ...baseConfig,
      slidesPerView: 1,
      spaceBetween: 10,
      navigation: {
        enabled: false,
        nextEl: `.swiper-button-next-${sectionName}`,
        prevEl: `.swiper-button-prev-${sectionName}`,
      },
    };
  }

  return baseConfig;
};

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

const getTrackUrl = (filePath) => {
    if (!filePath || typeof filePath !== 'string') return '';
    const normalized = filePath.trim();
    if (!normalized) return '';
    if (normalized.startsWith('http://') || normalized.startsWith('https://') || normalized.startsWith('/storage/')) {
        return normalized;
    }
    if (normalized.startsWith('storage/')) {
        return `/${normalized}`;
    }
    return `/storage/${normalized}`;
};

const getTrackCardProps = (track) => ({
    id: track.id,
    title: track.title,
    artist: track.artist || track.album?.artist?.surname || track.album?.artist?.name || 'Artiste inconnu',
    duration: formatDuration(track.duration),
    album: track.album?.title || track.album?.name || '',
    genre: track.genres?.[0]?.name ?? 'N/A',
    comment: `/comment/${track.slug}`,
    image: track.image ? getTrackUrl(track.image) : getTrackUrl(track.album?.image),
    file_path: getTrackUrl(track.file_path),
    favorites_count: track.favorites_count ?? 0,
});

const trackCard = (track) => ({
    ...getTrackCardProps(track),
    id: track.id,
});

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
        <div class="absolute top-0 right-0 w-20">
          <Link href="/" class="flex items-center gap-2 text-white font-bold text-xl tracking-tight hover:text-primary transition duration-200">
            <img :src="logo" alt="logo" class="w-[40px]">
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
            <div class="text-2xl font-bold text-white">{{ availableTracksCount }}</div>
            <div class="text-xs text-gray-400">Titres disponibles</div>
          </div>
          <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
            <div class="text-2xl font-bold text-white">{{ artistCount }}</div>
            <div class="text-xs text-gray-400">Artistes existants</div>
          </div>
          <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
            <div class="text-2xl font-bold text-white">{{ albumsCount }}</div>
            <div class="text-xs text-gray-400">Albums disponibles</div>
          </div>
          <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
            <div class="text-2xl font-bold text-white">{{ genresCount }}</div>
            <div class="text-xs text-gray-400">Genres explorés</div>
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
      <h1 class="text-[#e4e8f3d0] text-2xl my-4 font-semibold">Nouveauté <span class="text-sm text-gray-400">({{ nouveautes.length }})</span></h1>
      <div class="w-full p-2">
        <!-- 0 résultat -->
        <div v-if="nouveautes.length === 0" class="text-center py-12">
          <Music class="w-16 h-16 text-gray-500 mx-auto mb-4" />
          <p class="text-gray-400 text-lg">Aucune musique trouvée</p>
          <p class="text-gray-500 text-sm">Essayez de modifier votre recherche ou votre filtre</p>
        </div>

        <!-- 1 item -->
        <div v-else-if="nouveautes.length === 1" class="flex justify-center">
          <div class="w-full max-w-xs">
            <MusicCard
              v-bind="getTrackCardProps(nouveautes[0])"
              @play-track="handlePlayTrack(nouveautes[0], nouveautes)"
              class="w-full"
            />
          </div>
        </div>

        <!-- 2 items -->
        <div v-else-if="nouveautes.length === 2" class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
          <MusicCard
            v-for="track in nouveautes"
            :key="track.id"
            v-bind="getTrackCardProps(track)"
            @play-track="handlePlayTrack(track, nouveautes)"
            class="w-full"
          />
        </div>

        <!-- 3+ items - Swiper -->
        <div v-else class="swiper-wrapper-container">
          <div class="relative swiper-container">
            <Swiper
              :key="swiperKey + '-nouveaute'"
              v-bind="getSwiperConfig('nouveaute', nouveautes.length)"
              class="main-swiper"
            >
              <SwiperSlide
                v-for="track in nouveautes"
                :key="track.id"
                class="swiper-slide-item"
              >
                <MusicCard
                  v-bind="getTrackCardProps(track)"
                  @play-track="handlePlayTrack(track, nouveautes)"
                  class="music-card-item"
                />
              </SwiperSlide>
            </Swiper>

            <!-- Boutons de navigation -->
            <button v-if="nouveautes.length > 3" class="bg-[#33437e] p-2 rounded-full absolute top-1/2 left-0 transform -translate-y-1/2 z-10 hover:scale-100 active:scale-90 transition-all cursor-pointer swiper-button-prev-nouveaute">
                <ArrowLeft class="w-5 h-5 text-white" />
            </button>
            <button v-if="nouveautes.length > 3" class="bg-[#33437e] p-2 rounded-full absolute top-1/2 right-0 transform -translate-y-1/2 z-10 hover:scale-100 active:scale-90 transition-all cursor-pointer swiper-button-next-nouveaute">
                <ArrowRight class="w-5 h-5 text-white" />
            </button>
          </div>
        </div>
      </div>

      <!-- ========== SECTION POPULAIRES ========== -->
      <h1 class="text-[#e4e8f3d0] text-2xl my-4 font-semibold mt-8">Populaires <span class="text-sm text-gray-400">({{ populaires.length }})</span></h1>
      <div class="w-full p-2">
        <div v-if="populaires.length === 0" class="text-center py-12">
          <Music class="w-16 h-16 text-gray-500 mx-auto mb-4" />
          <p class="text-gray-400 text-lg">Aucune musique trouvée</p>
          <p class="text-gray-500 text-sm">Essayez de modifier votre recherche ou votre filtre</p>
        </div>
        <div v-else-if="populaires.length === 1" class="flex justify-center">
          <div class="w-full max-w-xs">
            <MusicCard
              v-bind="getTrackCardProps(populaires[0])"
              @play-track="handlePlayTrack(populaires[0], populaires)"
              class="w-full"
            />
          </div>
        </div>
        <div v-else-if="populaires.length === 2" class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
          <MusicCard
            v-for="track in populaires"
            :key="track.id"
            v-bind="getTrackCardProps(track)"
            @play-track="handlePlayTrack(track, populaires)"
            class="w-full"
          />
        </div>
        <div v-else class="swiper-wrapper-container">
          <div class="relative swiper-container">
            <Swiper
              :key="swiperKey + '-pop'"
              v-bind="getSwiperConfig('populaires', populaires.length)"
              class="main-swiper"
            >
              <SwiperSlide
                v-for="track in populaires"
                :key="track.id"
                class="swiper-slide-item"
              >
                <MusicCard
                  v-bind="getTrackCardProps(track)"
                  @play-track="handlePlayTrack(track, populaires)"
                  class="music-card-item"
                />
              </SwiperSlide>
            </Swiper>
            <button v-if="populaires.length > 3" class="bg-[#33437e] p-2 rounded-full absolute top-1/2 left-0 transform -translate-y-1/2 z-10 hover:scale-100 active:scale-90 transition-all cursor-pointer swiper-button-prev-populaires">
                <ArrowLeft class="w-5 h-5 text-white" />
            </button>
            <button v-if="populaires.length > 3" class="bg-[#33437e] p-2 rounded-full absolute top-1/2 right-0 transform -translate-y-1/2 z-10 hover:scale-100 active:scale-90 transition-all cursor-pointer swiper-button-next-populaires">
                <ArrowRight class="w-5 h-5 text-white" />
            </button>
          </div>
        </div>
      </div>

      <!-- ========== SECTION SUGGESTIONS ========== -->
      <h1 class="text-[#e4e8f3d0] text-2xl my-4 font-semibold mt-8">Suggestions <span class="text-sm text-gray-400">({{ suggestions.length }})</span></h1>
      <div class="w-full p-2">
        <div v-if="suggestions.length === 0" class="text-center py-12">
          <Music class="w-16 h-16 text-gray-500 mx-auto mb-4" />
          <p class="text-gray-400 text-lg">Aucune musique trouvée</p>
          <p class="text-gray-500 text-sm">Essayez de modifier votre recherche ou votre filtre</p>
        </div>
        <div v-else-if="suggestions.length === 1" class="flex justify-center">
          <div class="w-full max-w-xs">
            <MusicCard
              v-bind="getTrackCardProps(suggestions[0])"
              @play-track="handlePlayTrack(suggestions[0], suggestions)"
              class="w-full"
            />
          </div>
        </div>
        <div v-else-if="suggestions.length === 2" class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
          <MusicCard
            v-for="track in suggestions"
            :key="track.id"
            v-bind="getTrackCardProps(track)"
            @play-track="handlePlayTrack(track, suggestions)"
            class="w-full"
          />
        </div>
        <div v-else class="swiper-wrapper-container">
          <div class="relative swiper-container">
            <Swiper
              :key="swiperKey + '-sug'"
              v-bind="getSwiperConfig('suggestions', suggestions.length)"
              class="main-swiper"
            >
              <SwiperSlide
                v-for="track in suggestions"
                :key="track.id"
                class="swiper-slide-item"
              >
                <MusicCard
                  v-bind="getTrackCardProps(track)"
                  @play-track="handlePlayTrack(track, suggestions)"
                  class="music-card-item"
                />
              </SwiperSlide>
            </Swiper>
            <button v-if="suggestions.length > 3" class="bg-[#33437e] p-2 rounded-full absolute top-1/2 left-0 transform -translate-y-1/2 z-10 hover:scale-100 active:scale-90 transition-all cursor-pointer swiper-button-prev-suggestions">
                <ArrowLeft class="w-5 h-5 text-white" />
            </button>
            <button v-if="suggestions.length > 3" class="bg-[#33437e] p-2 rounded-full absolute top-1/2 right-0 transform -translate-y-1/2 z-10 hover:scale-100 active:scale-90 transition-all cursor-pointer swiper-button-next-suggestions">
                <ArrowRight class="w-5 h-5 text-white" />
            </button>
          </div>
        </div>
      </div>



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
