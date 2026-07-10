import { reactive } from 'vue';

const getTrackKey = (track) => track?.id ?? track?.file_path ?? track?.title;

const normalizeTrackPath = (filePath) => {
    if (!filePath || typeof filePath !== 'string') return '';
    const normalized = filePath.trim();
    if (!normalized) return '';
    if (normalized.startsWith('http://') || normalized.startsWith('https://')) {
        return normalized;
    }
    if (normalized.startsWith('/storage/')) {
        return normalized;
    }
    if (normalized.startsWith('storage/')) {
        return `/${normalized}`;
    }
    return `/storage/${normalized}`;
};

const normalizeAssetPath = (assetPath) => {
    if (!assetPath || typeof assetPath !== 'string') return '';
    const normalized = assetPath.trim();
    if (!normalized) return '';
    if (normalized.startsWith('http://') || normalized.startsWith('https://')) {
        return normalized;
    }
    if (normalized.startsWith('/storage/')) {
        return normalized;
    }
    if (normalized.startsWith('storage/')) {
        return `/${normalized}`;
    }
    return `/storage/${normalized}`;
};

const normalizeTrack = (track) => {
    if (!track || typeof track !== 'object') return track;

    const image = track.image
        ? normalizeAssetPath(track.image)
        : normalizeAssetPath(track.album?.image);

    const artist = track.artist
        || track.album?.artist?.surname
        || track.album?.artist?.name
        || track.album?.artist_name
        || '';

    const album = track.album && typeof track.album === 'object'
        ? track.album
        : { title: track.album };

    return {
        ...track,
        file_path: normalizeTrackPath(track.file_path),
        image: image || track.image || '',
        artist,
        album,
    };
};

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
    favoriteCounts: {},

    setFavoriteCount(track, count) {
        const key = getTrackKey(track);
        if (!key) return;
        this.favoriteCounts[key] = Number(count || 0);
    },

    getFavoriteCount(track) {
        const key = getTrackKey(track);
        if (!key) return undefined;
        return this.favoriteCounts[key];
    },

    play(track, playlist = []) {
        if (!track) return;

        const normalizedTrack = normalizeTrack(track);
        const normalizedPlaylist = Array.isArray(playlist) && playlist.length
            ? playlist.map(normalizeTrack)
            : this.currentPlaylist.length
                ? this.currentPlaylist.map(normalizeTrack)
                : [normalizedTrack];

        normalizedPlaylist.forEach((item) => {
            if (typeof item.favorites_count !== 'undefined') {
                this.setFavoriteCount(item, item.favorites_count);
            }
        });

        if (typeof normalizedTrack.favorites_count !== 'undefined') {
            this.setFavoriteCount(normalizedTrack, normalizedTrack.favorites_count);
        }

        const normalizedTrackPath = normalizedTrack.file_path;
        if (!normalizedTrackPath) return;

        if (this.currentTrack && normalizeTrackPath(this.currentTrack.file_path) === normalizedTrackPath && this.audioElement) {
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
            return;
        }

        if (this.audioElement) {
            this.audioElement.pause();
            this.audioElement.src = '';
        }

        this.currentPlaylist = normalizedPlaylist;

        this.currentIndex = this.currentPlaylist.findIndex(
            (item) => normalizeTrackPath(item.file_path) === normalizedTrackPath
        );

        if (this.currentIndex === -1) {
            this.currentIndex = 0;
        }

        this.currentTrack = normalizedTrack;
        this.isPlaying = true;
        this.currentTime = 0;
        this.duration = 0;
        this.progress = 0;

        this.audioElement = new Audio(normalizedTrackPath);
        this.audioElement.volume = this.volume;

        this.audioElement.addEventListener('loadedmetadata', () => {
            this.duration = this.audioElement.duration || 0;
        });

        this.audioElement.addEventListener('timeupdate', () => {
            this.currentTime = this.audioElement.currentTime;
            this.duration = this.audioElement.duration || this.duration;
            this.progress = this.duration
                ? this.currentTime / this.duration
                : 0;
        });

        this.audioElement.addEventListener('error', () => {
            const audioError = this.audioElement?.error;
            const errorInfo = audioError
                ? `code ${audioError.code}${audioError.message ? `: ${audioError.message}` : ''}`
                : 'unknown audio error';
            console.error('Audio loading error:', normalizedTrackPath, errorInfo, this.audioElement);
            this.isPlaying = false;
        });

        this.audioElement.addEventListener('ended', () => {
            this.isPlaying = false;

            if (this.canNext()) {
                this.next();
            }
        });

        this.audioElement.play().then(() => {
            this.isPlaying = true;
        }).catch((error) => {
            console.error('Audio playback failed:', normalizedTrackPath, error);
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

    stop() {
        if (this.audioElement) {
            this.audioElement.pause();
            this.audioElement.src = '';
            this.audioElement = null;
        }

        this.isPlaying = false;
        this.currentTrack = null;
        this.currentPlaylist = [];
        this.currentIndex = -1;
        this.currentTime = 0;
        this.duration = 0;
        this.progress = 0;
    },

    seek(time) {
        if (!this.audioElement || !this.duration) return;

        this.audioElement.currentTime = Math.max(
            0,
            Math.min(time, this.duration)
        );

        this.currentTime = this.audioElement.currentTime;
        this.progress = this.duration
            ? this.currentTime / this.duration
            : 0;
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
        return (
            this.currentPlaylist.length > 0 &&
            this.currentIndex < this.currentPlaylist.length - 1
        );
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

        return this.favorites.some(
            (favoriteTrack) => getTrackKey(favoriteTrack) === key
        );
    },

    toggleFavorite(track) {
        if (!track) return;

        const key = getTrackKey(track);

        const existingIndex = this.favorites.findIndex(
            (favoriteTrack) => getTrackKey(favoriteTrack) === key
        );

        const currentCount = this.getFavoriteCount(track) ?? track.favorites_count ?? 0;

        if (existingIndex >= 0) {
            this.favorites.splice(existingIndex, 1);
            this.setFavoriteCount(track, Math.max(0, currentCount - 1));
        } else {
            this.favorites.push(track);
            this.setFavoriteCount(track, currentCount + 1);
        }
    }
});