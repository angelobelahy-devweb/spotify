<script setup>
import { computed } from 'vue';
import { Play, Pause, HeartIcon } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';
import { playerStore } from '@/lib/playerStore';
import { openAuthModal } from '@/lib/authModalStore';

const props = defineProps({
    id: [String, Number],
    index: Number,
    title: String,
    duration: String,
    image: String,
    artist: String,
    file_path: String
});

const emit = defineEmits(['playTrack']);

const page = usePage();
const isLoggedIn = computed(() => Boolean(page.props.auth.user));

const trackData = {
    id: props.id,
    title: props.title,
    artist: props.artist,
    image: props.image,
    file_path: props.file_path,
};

const isCurrentTrackPlaying = computed(
    () =>
        playerStore.currentTrack?.file_path === props.file_path &&
        playerStore.isPlaying
);

const isFavorite = computed(() => playerStore.isFavorite(trackData));

const getCsrfTokenFromCookie = () => {
    const match = document.cookie.match(/(^|;)\s*XSRF-TOKEN=([^;]+)/);
    return match ? decodeURIComponent(match[2]) : null;
};

const updateLocalFavorite = (favoriteTrack, added) => {
    const key =
        favoriteTrack?.id ??
        favoriteTrack?.file_path ??
        favoriteTrack?.title;

    const existingIndex = playerStore.favorites.findIndex(
        (item) =>
            (item?.id ?? item?.file_path ?? item?.title) === key
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
        openAuthModal(
            'Veuillez vous connecter pour ajouter ce morceau à vos favoris.'
        );
        return;
    }

    if (!props.id) return;

    try {
        const response = await fetch('/favorites/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-XSRF-TOKEN': getCsrfTokenFromCookie() ?? '',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                track_id: props.id,
            }),
        });

        if (!response.ok) {
            if (
                response.status === 401 ||
                response.status === 419 ||
                response.redirected
            ) {
                openAuthModal(
                    'Veuillez vous connecter pour ajouter ce morceau à vos favoris.'
                );
                return;
            }

            console.error(
                'Toggle favorite failed',
                response.status,
                await response.text()
            );
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
        openAuthModal(
            'Veuillez vous connecter pour lancer la musique.'
        );
        return;
    }

    emit('playTrack');
};
</script>

<template>
    <div
        @click="handlePlayTrack"
        class="
            grid
            grid-cols-[40px_1fr_auto]
            items-center
            gap-4
            p-3
            hover:bg-white/5
            rounded-lg
            transition
            group
            cursor-pointer
        "
    >
        <div class="text-4xl font-thin opacity-30 tabular-nums">
            {{ index }}
        </div>

        <div class="flex items-center gap-4">
            <img :src="image" class="w-14 h-14 rounded-md object-cover">

            <div>
                <h2 class="text-white font-semibold">
                    {{ title }}
                </h2>

                <p class="text-sm text-gray-400">
                    {{ artist }}
                </p>
            </div>

            <button
                type="button"
                @click.stop="toggleFavorite"
                class="
                    hover:text-gray-400
                    text-[#fae311]
                    transition-all
                    cursor-pointer
                "
            >
                <HeartIcon
                    :class="[
                        'w-5 h-5',
                        isFavorite
                            ? 'fill-[#fae311] text-[#fae311]'
                            : 'fill-[#fae311] text-[#fae311]'
                    ]"
                />
            </button>
        </div>

        <div class="flex gap-2 items-center">
            <span class="text-gray-400">
                {{ duration }}
            </span>

            <button
                type="button"
                @click.stop="handlePlayTrack"
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
                <component
                    :is="isCurrentTrackPlaying ? Pause : Play"
                    class="text-white fill-white w-4 h-4"
                />
            </button>
        </div>
    </div>
</template>