import { reactive } from 'vue';

const getTrackKey = (track) => track?.id ?? track?.file_path ?? track?.title;

export const playerStore = reactive({
    currentTrack: null,
    currentPlaylist: [],
    currentIndex: -1,
    isPlaying: false,
    audioElement: null,
    currentTime: 0,
    duration: 0,
    progress: 0,
    volume: 1,
    favorites: [],

    play(track, playlist = []) {
        if (!track) return;

        if (this.currentTrack?.file_path === track.file_path && this.audioElement) {
            if (!this.isPlaying) {
                this.audioElement.play().then(() => {
                    this.isPlaying = true;
                }).catch(() => {
                    this.isPlaying = false;
                });
            }
            return;
        }

        if (this.audioElement) {
            this.audioElement.pause();
            this.audioElement.src = '';
        }

        this.currentPlaylist = Array.isArray(playlist) && playlist.length ? playlist : this.currentPlaylist.length ? this.currentPlaylist : [track];
        this.currentIndex = this.currentPlaylist.findIndex((item) => item.file_path === track.file_path);
        if (this.currentIndex === -1) {
            this.currentIndex = 0;
        }

        this.currentTrack = track;
        this.isPlaying = true;
        this.currentTime = 0;
        this.duration = 0;
        this.progress = 0;

        this.audioElement = new Audio(track.file_path);
        this.audioElement.volume = this.volume;

        this.audioElement.addEventListener('loadedmetadata', () => {
            this.duration = this.audioElement.duration || 0;
        });

        this.audioElement.addEventListener('timeupdate', () => {
            this.currentTime = this.audioElement.currentTime;
            this.duration = this.audioElement.duration || this.duration;
            this.progress = this.duration ? this.currentTime / this.duration : 0;
        });

        this.audioElement.addEventListener('ended', () => {
            this.isPlaying = false;
            if (this.canNext()) {
                this.next();
            }
        });

        this.audioElement.play().then(() => {
            this.isPlaying = true;
        }).catch(() => {
            this.isPlaying = false;
        });
    },

    toggle() {
        if (!this.audioElement) return;

        if (this.isPlaying) {
            this.audioElement.pause();
            this.isPlaying = false;
        } else {
            this.audioElement.play().then(() => {
                this.isPlaying = true;
            }).catch(() => {
                this.isPlaying = false;
            });
        }
    },

    seek(time) {
        if (!this.audioElement || !this.duration) return;
        this.audioElement.currentTime = Math.max(0, Math.min(time, this.duration));
        this.currentTime = this.audioElement.currentTime;
        this.progress = this.duration ? this.currentTime / this.duration : 0;
    },

    setVolume(value) {
        this.volume = Math.max(0, Math.min(value, 1));
        if (this.audioElement) {
            this.audioElement.volume = this.volume;
        }
    },

    setFavorites(favorites) {
        if (!Array.isArray(favorites)) {
            return;
        }

        this.favorites.splice(0, this.favorites.length, ...favorites);
    },

    canPrev() {
        return this.currentPlaylist.length > 0 && this.currentIndex > 0;
    },

    canNext() {
        return this.currentPlaylist.length > 0 && this.currentIndex < this.currentPlaylist.length - 1;
    },

    prev() {
        if (!this.canPrev()) {
            if (this.audioElement) {
                this.seek(0);
            }
            return;
        }
        const previousTrack = this.currentPlaylist[this.currentIndex - 1];
        if (previousTrack) {
            this.play(previousTrack, this.currentPlaylist);
        }
    },

    next() {
        if (!this.canNext()) return;
        const nextTrack = this.currentPlaylist[this.currentIndex + 1];
        if (nextTrack) {
            this.play(nextTrack, this.currentPlaylist);
        }
    },

    isFavorite(track) {
        if (!track) return false;
        const key = getTrackKey(track);
        return this.favorites.some((favoriteTrack) => getTrackKey(favoriteTrack) === key);
    },

    toggleFavorite(track) {
        if (!track) return;
        const key = getTrackKey(track);
        const existingIndex = this.favorites.findIndex(
            (favoriteTrack) => getTrackKey(favoriteTrack) === key
        );

        if (existingIndex >= 0) {
            this.favorites.splice(existingIndex, 1);
        } else {
            this.favorites.push(track);
        }
    }
});
