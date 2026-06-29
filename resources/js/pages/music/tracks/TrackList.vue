<script setup>
import { Link } from '@inertiajs/vue3'
import { defineProps, ref, computed, watch, nextTick } from 'vue'
import { Music, Plus, ArrowLeft, ArrowRight } from 'lucide-vue-next';
import MusicCard from '@/components/angelo/cards/MusicCard.vue';
import { playerStore } from '@/lib/playerStore';

// Import Swiper
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import { Navigation } from 'swiper/modules';

const props = defineProps({
  tracks: Array,
  genres: Array,
  songs: Array
});

const handlePlayTrack = (song) => {
    playerStore.play(song, props.songs);
};

// Filtres
const selectedGenre = ref('all');
const searchQuery = ref('');

// Tracks filtrées
const filteredTracks = computed(() => {
  if (!props.tracks) return [];
  
  let result = props.tracks;
  
  if (selectedGenre.value !== 'all') {
    result = result.filter(track => 
      track.genres?.some(genre => String(genre.id) === String(selectedGenre.value))
    );
  }
  
  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase().trim();
    result = result.filter(track => 
      track.title.toLowerCase().includes(query) ||
      track.album?.artist?.surname?.toLowerCase().includes(query)
    );
  }
  
  return result;
});

const filteredCount = computed(() => filteredTracks.value.length);

// Configuration Swiper
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

watch([selectedGenre, searchQuery], async () => {
  await nextTick();
  swiperKey.value += 1;
});

function formatDuration(seconds) {
  if (!seconds) return '--:--';
  const mins = Math.floor(seconds / 60);
  const secs = seconds % 60;
  return `${mins}:${secs.toString().padStart(2, '0')}`;
}
</script>

<template>
  <div class="tracks-container">
    <!-- Header -->
    <div class="flex gap-2 items-center">
      <h1 class="text-[#e4e8f3d0] text-2xl my-2">Tracks</h1>
      <Link href="/tracks/create" class="flex gap-2 items-center bg-[#121212]/80 border-2 border-[#33437e] rounded-full w-[max-content] p-1 hover:translate-y-1 transition-all duration-300">
        <div class="bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all duration-300 p-1 rounded-full text-sm font-bold flex items-center justify-center gap-2 w-6 h-6 cursor-pointer disabled:opacity-50">
          <Plus />
        </div>
        <span class="text-xs text-white uppercase">Créer</span>
      </Link>
    </div>
    
    <!-- Filtres -->
    <div class="flex flex-wrap gap-4 items-center mt-2">
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

      <div class="relative flex-1 min-w-[200px]">
        <input 
          v-model="searchQuery"
          type="text"
          placeholder="Rechercher un titre ou un artiste..."
          class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-[#33437e]"
        />
      </div>
    </div>

    <!-- Compteur -->
    <div class="text-sm text-gray-400 mt-2">
      {{ filteredCount }} résultat{{ filteredCount > 1 ? 's' : '' }}
    </div>

    <div>
      <!-- Header -->
      <div class="mb-2">
        <div class="flex items-center gap-3 mb-2">
          <Music class="w-8 h-8 text-[#e4e8f3]" />
          <h1 class="text-4xl font-bold text-[#e4e8f3]">Toutes les Musiques</h1>
        </div>
      </div>
      
      <!-- Affichage des résultats -->
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
              :file_path="filteredTracks[0].file_path"
              @play-track="handlePlayTrack(filteredTracks[0])"
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
            :file_path="track.file_path"
            @play-track="handlePlayTrack(track)"
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
                  :file_path="track.file_path"
                  @play-track="handlePlayTrack(track)"
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
    </div>
  </div>
</template>

<style scoped>
/* Conteneur principal */
.tracks-container {
  overflow-x: hidden !important;
  max-width: 100%;
  padding: 0 4px;
}

/* Wrapper Swiper */
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

.swiper-button-prev { left: 0; }
.swiper-button-next { right: 0; }

.swiper-button-prev:hover,
.swiper-button-next:hover {
  background: rgba(51, 67, 126, 1);
  transform: translateY(-50%) scale(1.1);
}

@media (max-width: 640px) {
  .swiper-wrapper-container { padding: 0 5px; }
  .swiper-button-prev,
  .swiper-button-next {
    width: 32px;
    height: 32px;
    font-size: 14px;
  }
  .swiper-button-prev { left: -5px; }
  .swiper-button-next { right: -5px; }
  .music-card-item {
    min-width: 140px;
    max-width: 160px;
  }
}
</style>

