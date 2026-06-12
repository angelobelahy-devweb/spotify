<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Play, Pause, HeartIcon, Music2 } from 'lucide-vue-next';
import { playerStore } from '@/lib/playerStore';
import { openAuthModal } from '@/lib/authModalStore';

const page = usePage();
const isLoggedIn = computed(() => Boolean(page.props.auth.user));
const currentTrack = computed(() => playerStore.currentTrack);
const isPlaying = computed(() => playerStore.isPlaying);
const isFavorite = computed(() => playerStore.isFavorite(currentTrack.value));

const getCsrfTokenFromCookie = () => {
    const match = document.cookie.match(/(^|;)\s*XSRF-TOKEN=([^;]+)/);
    return match ? decodeURIComponent(match[2]) : null;
};

const updateLocalFavorite = (favoriteTrack, added) => {
    const key = favoriteTrack?.id ?? favoriteTrack?.file_path ?? favoriteTrack?.title;
    const existingIndex = playerStore.favorites.findIndex((item) => (item?.id ?? item?.file_path ?? item?.title) === key);

    if (added) {
        if (existingIndex === -1) {
            playerStore.favorites.push(favoriteTrack);
        }
    } else if (existingIndex >= 0) {
        playerStore.favorites.splice(existingIndex, 1);
    }
};

const togglePlayback = (event) => {
    event.stopPropagation();

    if (!isLoggedIn.value) {
        openAuthModal('Veuillez vous connecter pour lancer la musique.');
        return;
    }

    playerStore.toggle();
};

const toggleFavorite = async (event) => {
    event.stopPropagation();

    if (!currentTrack.value) {
        return;
    }

    if (!isLoggedIn.value) {
        openAuthModal('Veuillez vous connecter pour ajouter ce morceau à vos favoris.');
        return;
    }

    if (!currentTrack.value.id) {
        playerStore.toggleFavorite(currentTrack.value);
        return;
    }

    try {
        const response = await fetch('/favorites/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-XSRF-TOKEN': getCsrfTokenFromCookie() ?? '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ track_id: currentTrack.value.id })
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
</script>
<template>
    <div
        class="
        bg-[#121212]/90
        border-t
        border-white/10
        p-3
        backdrop-blur-xl
        cursor-pointer
        "
    >
        <div class="flex items-center justify-between gap-4">

            <div class="flex items-center gap-4 min-w-0">
                <div class="w-16 h-16 overflow-hidden rounded-lg shadow-lg flex justify-center items-center bg-slate-900">
                    <template v-if="currentTrack">
                        <img :src="currentTrack.image || '/assets/images/album.JPG'" class="w-full h-full object-cover" />
                    </template>
                    <template v-else>
                        <Music2 class="w-8 h-8 text-gray-400" />
                    </template>
                </div>

                <div class="flex flex-col gap-1 min-w-0">
                    <p class="text-sm text-gray-400">{{ currentTrack ? 'En cours de lecture' : 'Aucune piste sélectionnée' }}</p>
                    <h2 class="text-white font-semibold truncate">{{ currentTrack?.title ?? 'Aucune piste' }}</h2>
                    <p class="text-xs text-gray-400 truncate">{{ currentTrack?.artist ?? '...' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button
                    type="button"
                    @click.stop="toggleFavorite"
                    class="text-gray-400 hover:text-[#fae311] transition-colors"
                    :disabled="!currentTrack"
                >
                    <HeartIcon
                        :class="[
                            'w-5 h-5',
                            isFavorite ? 'fill-[#fae311] text-[#fae311]' : 'fill-none text-gray-400'
                        ]"
                    />
                </button>

                <button
                    type="button"
                    @click.stop="togglePlayback"
                    class="bg-[#33437e] p-3 rounded-full hover:scale-110 active:scale-95 transition-all"
                    :disabled="!currentTrack"
                >
                    <component :is="isPlaying ? Pause : Play" class="w-6 h-6 text-white" />
                </button>
            </div>

        </div>
    </div>
</template>