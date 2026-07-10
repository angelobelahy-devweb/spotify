<script setup>
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { playerStore } from '@/lib/playerStore';
import { openAuthModal } from '@/lib/authModalStore';

const page = usePage();
const favorites = computed(() => page.props.favorites ?? []);
const favoriteTracks = ref(favorites.value);
const isLoggedIn = computed(() => Boolean(page.props.auth.user));

const getCsrfTokenFromCookie = () => {
    const match = document.cookie.match(/(^|;)\s*XSRF-TOKEN=([^;]+)/);
    return match ? decodeURIComponent(match[2]) : null;
};

const updateFavoriteList = (track, isFavorited) => {
    const key = track?.id ?? track?.file_path ?? track?.title;
    if (!key) return;

    const index = favoriteTracks.value.findIndex((item) =>
        (item?.id ?? item?.file_path ?? item?.title) === key
    );

    if (!isFavorited && index >= 0) {
        favoriteTracks.value.splice(index, 1);
    } else if (isFavorited && index === -1) {
        favoriteTracks.value.push(track);
    }
};

const playTrack = (track) => {
    if (!isLoggedIn.value) {
        openAuthModal('Veuillez vous connecter pour lancer la musique.');
        return;
    }

    playerStore.play(track, favoriteTracks.value);
};

const toggleFavorite = async (track) => {
    if (!isLoggedIn.value) {
        openAuthModal('Veuillez vous connecter pour modifier vos favoris.');
        return;
    }

    if (!track?.id) {
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
            body: JSON.stringify({ track_id: track.id })
        });

        if (!response.ok) {
            if (response.status === 401 || response.status === 419 || response.redirected) {
                openAuthModal('Veuillez vous connecter pour modifier vos favoris.');
                return;
            }
            console.error('Toggle favorite failed', response.status, await response.text());
            return;
        }

        const data = await response.json();
        playerStore.toggleFavorite(data.track);
        updateFavoriteList(data.track, data.favorite);
    } catch (error) {
        console.error(error);
    }
};
</script>

<template>
     <h1 class="text-[#e4e8f3d0] text-2xl my-2">My Favories music</h1>
        <div class="flex flex-col items-center justify-start gap-2 ">
            <template v-if="favorites.length > 0">
                <div
                    v-for="(track, index) in favorites"
                    :key="track.id ?? track.file_path ?? track.title ?? index"
                    class="
                        bg-[#33437e]/20
                        border-2 border-[#33437e]
                        hover:scale-[1.01]
                        transition
                        p-2
                        rounded-sm
                        group
                        w-full
                        shadow-[0_2_20px_red]
                        hover:translate-y-1
                    "
                >
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <strong class="text-gray-400 text-xs">{{ index + 1 }}</strong>
                            <img
                                :src="track.image || '/assets/images/album.JPG'"
                                class="
                                    w-[50px]
                                    h-[50px]
                                    object-cover
                                    rounded-[5px]
                                "
                            />
                            <div class="flex flex-col gap-2">
                                <h2 class="text-white font-bold text-sm">
                                    {{ track.title }}
                                </h2>
                                <p class="text-gray-400 text-xs">
                                    {{ track.artist }}
                                </p>
                            </div>
                            <button
                                type="button"
                                @click.stop="toggleFavorite(track)"
                                class="
                                    hover:text-gray-400
                                    text-[#fae311]
                                    transition-all
                                    cursor-pointer
                                "
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>
                        </div>
                        <button
                            type="button"
                            @click.stop="playTrack(track)"
                            class="
                                bg-[#33437e]
                                p-2
                                rounded-full
                                hover:scale-110
                                active:scale-90
                                transition-all
                                cursor-pointer
                                w-[max-content]
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" class="w-4 h-4">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
            <div v-else class="text-gray-400 py-6">
                Aucun favori pour le moment.
            </div>
        </div>
</template>