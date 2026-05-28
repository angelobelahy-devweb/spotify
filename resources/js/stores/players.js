import { defineStore } from "pinia";

export const usePlayerStore = defineStore("player", {
    state: () => ({
        currentTrack: null,
        isPlaying: false,
    }),

    actions: {
        play(track) {
            this.currentTrack = track;
            this.isPlaying = true;
        },

        pause() {
            this.isPlaying = false;
        },
    },
});