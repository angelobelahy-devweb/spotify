<script setup>
import { Album, MessageCircle } from 'lucide-vue-next'
import { Link, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue';
import { Play, Pause, HeartIcon } from 'lucide-vue-next';
import { playerStore } from '@/lib/playerStore';
import { openAuthModal } from '@/lib/authModalStore';

const props = defineProps({
    id: Number,
    title: String,
    artist: String,
    image: String,
    genre: String,
    album: String,
    comment: String,
    duration: String,
    file_path: String,
    favorites_count: Number,
})




const emit = defineEmits(['playTrack']);

const page = usePage();
const isLoggedIn = computed(() => Boolean(page.props.auth.user));

const normalizeTrackPath = (filePath) => {
  if (!filePath || typeof filePath !== 'string') return '';
  const normalized = filePath.trim();
  if (!normalized) return '';
  if (normalized.startsWith('http://') || normalized.startsWith('https://')) return normalized;
  if (normalized.startsWith('/storage/')) return normalized;
  if (normalized.startsWith('storage/')) return `/${normalized}`;
  return `/storage/${normalized}`;
};

const trackData = computed(() => ({
  id: props.id ?? null,
  title: props.title,
  artist: props.artist,
  image: props.image,
  file_path: props.file_path,
}));

const isCurrentTrackPlaying = computed(() => {
  const currentTrackPath = normalizeTrackPath(playerStore.currentTrack?.file_path);
  return currentTrackPath && currentTrackPath === normalizeTrackPath(props.file_path) && playerStore.isPlaying;
});

const favoritePulse = ref(false);
const isFavorite = computed(() => playerStore.isFavorite(trackData.value));
const favoritesCount = computed(() => {
  const storedCount = playerStore.getFavoriteCount(trackData.value);
  return storedCount ?? props.favorites_count ?? 0;
});

const getCsrfTokenFromCookie = () => {
  const match = document.cookie.match(/(^|;)\s*XSRF-TOKEN=([^;]+)/);
  return match ? decodeURIComponent(match[2]) : null;
};

const updateLocalFavorite = (favoriteTrack, added) => {
  const key = favoriteTrack?.id ?? favoriteTrack?.file_path ?? favoriteTrack?.title;
  const existingIndex = playerStore.favorites.findIndex(
    (item) => (item?.id ?? item?.file_path ?? item?.title) === key
  );

  if (added) {
    if (existingIndex === -1) {
      playerStore.favorites.push(favoriteTrack);
    }
  } else if (existingIndex >= 0) {
    playerStore.favorites.splice(existingIndex, 1);
  }
};

const toggleFavorite = async (event) => {
  event.stopPropagation();

  if (!isLoggedIn.value) {
    openAuthModal('Veuillez vous connecter pour ajouter ce morceau à vos favoris.');
    return;
  }

  if (!props.id) {
    return;
  }

  try {
    const response = await fetch('/favorites/toggle', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-XSRF-TOKEN': getCsrfTokenFromCookie() ?? '',
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'same-origin',
      body: JSON.stringify({ track_id: props.id }),
    });

    if (!response.ok) {
      if (response.status === 401 || response.status === 419 || response.redirected) {
        openAuthModal('Veuillez vous connecter pour ajouter ce morceau à vos favoris.');
        return;
      }
      console.error('Toggle favorite failed', response.status, await response.text());
      return;
    }

    const data = await response.json();
    updateLocalFavorite(data.track, data.favorite);
    playerStore.setFavoriteCount(data.track, data.track.favorites_count);
    favoritePulse.value = true;
    setTimeout(() => {
      favoritePulse.value = false;
    }, 350);
  } catch (error) {
    console.error(error);
  }
};

const handlePlayTrack = (event) => {
  if (event && event.stopPropagation) {
    event.stopPropagation();
  }

  if (!isLoggedIn.value) {
    openAuthModal('Veuillez vous connecter pour lancer la musique.');
    return;
  }

  emit('playTrack');
};
</script>

<template>
    <div
    class="
    bg-[#33437e]/20
    border border-[#33437e]
    backdrop-blur-xl
    transition
    p-2
    rounded-xs
    "
>
        <div class="relative">
            <div class="relative">
                <img
                    :src="image"
                    class="
                    w-full
                    h-[150px]
                    object-cover
                    rounded-lg
                    "
                />
                <strong class="badge text-white bg-[#33437e]/80 px-2 text-xs absolute top-2 right-2">{{ genre }}</strong>
            </div>
            <button
                @click.stop="handlePlayTrack"
                class="
                absolute
                bottom-3
                right-3
                bg-[#33437e]
                p-2
                rounded-full
                hover:scale-110
                active:scale-90
                transition-all
                cursor-pointer
                "
            >
                <component :is="isCurrentTrackPlaying ? Pause : Play" class="text-white fill-white w-4 h-4" />
            </button>

        </div>
        <!-- Heart -->
        <div class="w-full flex justify-between items-center">
            <div class="flex items-center gap-2">
              <button
                @click.stop="toggleFavorite"
                :class="[
                  'transition-transform duration-200 mt-2 cursor-pointer',
                  isFavorite ? 'text-[#fae311]' : 'text-gray-400 hover:text-[#fae311]',
                  favoritePulse ? 'scale-110' : ''
                ]"
              >
                <HeartIcon :class="[
                  isFavorite ? 'fill-[#fae311]' : 'fill-gray-400',
                  'w-5 h-5'
                ]" />
              </button>
              <span class="text-gray-400 text-xs mt-2">{{ favoritesCount }}</span>
              <span class="text-gray-400">{{ duration }}</span>
            </div>
        </div>
        <div class="flex justify-center w-full text-gray-400 items-center gap-1">
            <Album class="w-3 h-3" /><span class="text-center  text-[10px]">{{ album }}</span>
        </div>
        <h2 class="text-white font-bold mt-4 text-sm">
            {{ title }}
        </h2>

        <p class="text-gray-400 text-xs">
            {{ artist }}
        </p>
        <div class="w-full flex justify-end">
            <Link :href="comment" class="text-white text-sm bg-[#33437e] p-2 rounded-md">
                <MessageCircle class="w-4 h-4" />
            </Link>
        </div>
    </div>
</template>
