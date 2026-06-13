<script setup>
import { computed } from 'vue';
import { ChevronFirst, ChevronLast, HeartIcon, Play, Pause, Volume2 } from 'lucide-vue-next';
import { playerStore } from '@/lib/playerStore';

const currentTrack = computed(() => playerStore.currentTrack);
const isPlaying = computed(() => playerStore.isPlaying);
const progress = computed(() => playerStore.progress || 0);
const currentTime = computed(() => playerStore.currentTime || 0);
const duration = computed(() => playerStore.duration || 0);
const volume = computed(() => playerStore.volume);
const isFavorite = computed(() => playerStore.isFavorite(currentTrack.value));
const canPrev = computed(() => playerStore.canPrev());
const canNext = computed(() => playerStore.canNext());

const formatTime = (time) => {
    if (!time || isNaN(time)) return '0:00';
    const minutes = Math.floor(time / 60);
    const seconds = Math.floor(time % 60).toString().padStart(2, '0');
    return `${minutes}:${seconds}`;
};

const togglePlayback = () => {
    playerStore.toggle();
};

const playPrev = () => {
    playerStore.prev();
};

const playNext = () => {
    playerStore.next();
};

const updateProgress = (event) => {
    const value = Number(event.target.value);
    playerStore.seek(value * duration.value);
};

const updateVolume = (event) => {
    const value = Number(event.target.value);
    playerStore.setVolume(value);
};

const toggleFavorite = () => {
    if (currentTrack.value) {
        playerStore.toggleFavorite(currentTrack.value);
    }
};
</script>
<template>
    <div class="p-4 bg-[#121212]/95 rounded-2xl shadow-2xl text-white w-full">
        <div class="flex flex-col gap-6">


            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-24 h-24 rounded-full overflow-hidden shadow-lg bg-slate-900">
                        <img
                            :src="currentTrack?.image || '/assets/images/album.JPG'"
                            class="w-full h-full object-cover"
                            alt="Cover"
                        />
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs uppercase text-gray-400">{{ currentTrack ? 'En cours' : 'Aucun titre' }}</p>
                        <h2 class="text-xl font-bold truncate">{{ currentTrack?.title ?? 'Pas de lecture' }}</h2>
                        <p class="text-sm text-gray-400 truncate">{{ currentTrack?.artist ?? '...' }}</p>
                    </div>
                </div>

                <button
                    type="button"
                    @click="toggleFavorite"
                    class="text-gray-400 hover:text-[#fae311] transition-colors"
                    :disabled="!currentTrack"
                >
                    <HeartIcon
                        :class="[
                            'w-6 h-6',
                            isFavorite ? 'fill-[#fae311] text-[#fae311]' : 'fill-none text-gray-400'
                        ]"
                    />
                </button>
            </div>

            <div class="flex items-center justify-center gap-6">
                <button
                    type="button"
                    @click="playPrev"
                    :disabled="!canPrev"
                    class="text-white hover:scale-110 transition-transform disabled:opacity-40 disabled:cursor-default"
                >
                    <ChevronFirst class="w-6 h-6" />
                </button>
                <button
                    type="button"
                    @click="togglePlayback"
                    class="bg-[#33437e] p-4 rounded-full hover:scale-110 transition-transform disabled:opacity-40"
                    :disabled="!currentTrack"
                >
                    <component :is="isPlaying ? Pause : Play" class="w-6 h-6" />
                </button>
                <button
                    type="button"
                    @click="playNext"
                    :disabled="!canNext"
                    class="text-white hover:scale-110 transition-transform disabled:opacity-40 disabled:cursor-default"
                >
                    <ChevronLast class="w-6 h-6" />
                </button>
            </div>

            <div class="flex items-center gap-3 text-xs text-gray-400">
                <span>{{ formatTime(currentTime) }}</span>
                <input
                    type="range"
                    min="0"
                    max="1"
                    step="0.001"
                    :value="progress"
                    @input="updateProgress"
                    class="flex-1 accent-white"
                />
                <span>{{ formatTime(duration) }}</span>
            </div>

            <div class="flex items-center gap-3">
                <Volume2 class="w-5 h-5 text-white" />
                <input
                    type="range"
                    min="0"
                    max="1"
                    step="0.01"
                    :value="volume"
                    @input="updateVolume"
                    class="flex-1 accent-white"
                />
            </div>

        </div>
    </div>
</template>