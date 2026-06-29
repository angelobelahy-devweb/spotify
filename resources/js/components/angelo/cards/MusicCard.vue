<script setup>
import { Album, MessageCircle } from 'lucide-vue-next'
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue';
import { Play, Pause, HeartIcon } from 'lucide-vue-next';
import { playerStore } from '@/lib/playerStore';
import { openAuthModal } from '@/lib/authModalStore';

const props = defineProps({
    title: String,
    artist: String,
    image: String,
    genre: String,
    album: String,
    comment: String,
    duration: String,
    file_path: String,
})




const emit = defineEmits(['playTrack']);

const page = usePage();
const isLoggedIn = computed(() => Boolean(page.props.auth.user));

const trackData = computed(() => ({
  id: props.id,
  title: props.title,
  artist: props.artist,
  image: props.image,
  file_path: props.file_path,
}));

const isCurrentTrackPlaying = computed(
  () => playerStore.currentTrack?.file_path === props.file_path && playerStore.isPlaying
);

const isFavorite = computed(() => playerStore.isFavorite(trackData.value));

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
  } catch (error) {
    console.error(error);
  }
};

const handlePlayTrack = (event) => {
  event.stopPropagation();

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
            <button
                class="
                text-gray-400
                hover:text-[#fae311]
                transition-all
                mt-2
                cursor-pointer
                "
            >
                <HeartIcon class="fill-gray-400 text-gray-400 hover:text-[#fae311] hover:fill-[#fae311] w-5 h-5" />
            </button>
            <span class="text-gray-400">{{ duration }}</span>
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
