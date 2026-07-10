<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import {
    Shield, Zap, Crown, ArrowLeft, Music, Disc, Radio,
    Heart, Eye, MessageCircle, Clock, Play, Pause,
    MoreHorizontal, Share2, Volume2, ListMusic,
    Star, Calendar, User, Headphones, Award, ShoppingCart, CheckCircle
} from 'lucide-vue-next'
import { computed } from 'vue'
import { playerStore } from '@/lib/playerStore'
import { openAuthModal } from '@/lib/authModalStore'

const props = defineProps({
    artist: Object,
    albums: Array,
    tracks: Array,
    stats: Object
})

const page = usePage()
const isLoggedIn = computed(() => Boolean(page.props.auth.user))
const currentTrack = computed(() => playerStore.currentTrack)
const isPlaying = computed(() => playerStore.isPlaying)
const progress = computed(() => playerStore.progress || 0)
const currentTime = computed(() => playerStore.currentTime || 0)
const duration = computed(() => playerStore.duration || 0)

// Computed properties
const subscriptionBadge = computed(() => {
    const tiers = {
        free: { icon: Shield, color: 'text-zinc-400', bg: 'bg-zinc-500/10', border: 'border-zinc-500/20' },
        basic: { icon: Shield, color: 'text-gray-300', bg: 'bg-gray-500/10', border: 'border-gray-500/20' },
        premium: { icon: Zap, color: 'text-blue-400', bg: 'bg-blue-500/10', border: 'border-blue-500/20' },
        vip: { icon: Crown, color: 'text-amber-400', bg: 'bg-amber-500/10', border: 'border-amber-500/20' }
    }
    return tiers[props.artist.subscription_tier] || tiers.free
})

const totalPlays = computed(() => {
    return props.tracks?.reduce((sum, track) => sum + (track.plays_count || 0), 0) || 0
})

const allTracksComments = computed(() => {
    if (!props.tracks) return []
    let comments = []
    props.tracks.forEach(track => {
        if (track.comments) {
            track.comments.forEach(comment => {
                comments.push({
                    ...comment,
                    track_title: track.title
                })
            })
        }
    })
    return comments.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
})

// Methods
const formatDate = (date) => {
    if (!date) return '---'
    return new Date(date).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const formatDuration = (seconds) => {
    if (!seconds) return '0:00';
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
};

const formatNumber = (num) => {
    if (!num) return 0
    if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M'
    if (num >= 1000) return (num / 1000).toFixed(1) + 'K'
    return num
}

// Fonction de contrôle de lecture
const togglePlayback = () => {
    playerStore.toggle()
}

const playTrack = (track) => {
    if (!isLoggedIn.value) {
        openAuthModal('Veuillez vous connecter pour lancer la musique.')
        return
    }

    if (!track) return

    if (currentTrack.value?.id === track.id) {
        playerStore.toggle()
    } else {
        playerStore.play(track, props.tracks)
    }
}

const updateProgress = (event) => {
    if (!currentTrack.value || !duration.value) return
    const value = Number(event.target.value)
    playerStore.seek(value * duration.value)
}

const getTrackUrl = (track) => {
    if (!track || !track.file_path) return ''
    if (track.file_path.startsWith('http://') || track.file_path.startsWith('https://')) return track.file_path
    if (track.file_path.startsWith('/storage/')) return track.file_path
    if (track.file_path.startsWith('storage/')) return `/${track.file_path}`
    return `/storage/${track.file_path}`
}

const getAlbumCover = (coverPath) => {
    if (!coverPath) return '/images/default-album.jpg'
    if (coverPath.startsWith('http://') || coverPath.startsWith('https://')) return coverPath
    if (coverPath.startsWith('/storage/')) return coverPath
    if (coverPath.startsWith('storage/')) return `/${coverPath}`
    return `/storage/${coverPath}`
}

const buyAlbum = (albumId) => {
    router.post(`/albums/${albumId}/checkout`)
}
</script>

<template>
    <Head :title="`Profil de ${artist.surname}`" />

    <div class="min-h-screen bg-[#0d0d13] text-white font-sans pb-32">
        <div class="max-w-6xl mx-auto pt-6 px-4">
            <div class="flex items-center justify-between">
                <Link href="/artistes" class="inline-flex items-center gap-2 text-sm text-[#6b7bb8] hover:text-white transition-colors">
                    <ArrowLeft :size="16" />
                    Retour aux artistes
                </Link>
                <button class="p-2 rounded-full hover:bg-[#2a2a3e] transition-colors">
                    <Share2 :size="18" class="text-[#6b7bb8]" />
                </button>
            </div>
        </div>

        <div class="max-w-6xl mx-auto mt-6 px-4">
            <div class="relative bg-gradient-to-r from-[#1a1a26] to-[#121212] border border-[#2a2a3e] rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center gap-6 shadow-2xl">
                <div class="relative w-32 h-32 md:w-40 md:h-40 flex-shrink-0 group">
                    <img
                        :src="artist.user?.pdp ? `/storage/${artist.user.pdp}` : '/images/default-avatar.png'"
                        :alt="artist.surname"
                        class="w-full h-full object-cover rounded-full border-4 border-[#33437e] shadow-xl transition-transform duration-300 group-hover:scale-105"
                    />
                    <div class="absolute inset-0 rounded-full bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <Headphones :size="24" class="text-white" />
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left space-y-3">
                    <div class="flex flex-col md:flex-row md:items-center gap-3 justify-center md:justify-start">
                        <h1 class="text-3xl font-bold tracking-wide text-[#e4e8f3d0]">{{ artist.surname }}</h1>

                        <div class="inline-flex items-center justify-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold w-max mx-auto md:mx-0"
                             :class="subscriptionBadge.bg + ' ' + subscriptionBadge.color + ' ' + subscriptionBadge.border">
                            <component :is="subscriptionBadge.icon" :size="14" />
                            <span class="capitalize">{{ artist.subscription_tier }}</span>
                        </div>

                        <div class="flex items-center gap-1 text-emerald-400 text-sm mx-auto md:mx-0">
                            <CheckCircle :size="14" />
                            <span>Vérifié</span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-400 max-w-2xl leading-relaxed">
                        {{ artist.description || "Cet artiste n'a pas encore rédigé de description." }}
                    </p>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-400 justify-center md:justify-start pt-2">
                        <div class="flex items-center gap-1.5">
                            <Headphones :size="16" />
                            <span>{{ formatNumber(totalPlays) }} écoutes</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <Music :size="16" />
                            <span>{{ tracks?.length || 0 }} titres</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <ListMusic :size="16" />
                            <span>{{ albums?.length || 0 }} albums</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto mt-8 px-4 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-[#1a1a26]/50 border border-[#2a2a3e] rounded-xl p-5 space-y-4">
                    <h3 class="text-sm font-semibold text-[#6b7bb8] uppercase tracking-wider flex items-center gap-2">
                        <User :size="16" />
                        À propos
                    </h3>
                    <div class="text-xs space-y-3 text-gray-400">
                        <p>Membre depuis : <span class="text-white font-medium">{{ formatDate(artist.created_at) }}</span></p>
                        <p>Statut du compte : <span class="text-emerald-400 font-medium">Actif</span></p>
                        <p>Abonnement : <span class="text-white font-medium capitalize">{{ artist.subscription_tier }}</span></p>
                        <div class="pt-2 border-t border-[#2a2a3e]">
                            <div class="flex items-center justify-between text-sm">
                                <span>Total des écoutes</span>
                                <span class="text-white font-bold">{{ formatNumber(totalPlays) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-[#1a1a26]/50 border border-[#2a2a3e] rounded-xl p-5 space-y-4">
                    <h3 class="text-sm font-semibold text-[#6b7bb8] uppercase tracking-wider flex items-center gap-2">
                        <Calendar :size="16" />
                        Statistiques globales
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-[#0d0d13] rounded-lg p-3 text-center">
                            <div class="text-xl font-bold text-white">{{ stats?.albums_count || 0 }}</div>
                            <div class="text-xs text-gray-400">Albums</div>
                        </div>
                        <div class="bg-[#0d0d13] rounded-lg p-3 text-center">
                            <div class="text-xl font-bold text-white">{{ stats?.tracks_count || 0 }}</div>
                            <div class="text-xs text-gray-400">Titres</div>
                        </div>
                        <div class="bg-[#0d0d13] rounded-lg p-3 text-center col-span-2">
                            <div class="text-xl font-bold text-emerald-400">{{ formatNumber(totalPlays) }}</div>
                            <div class="text-xs text-gray-400">Écoutes cumulées</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-[#1a1a26]/50 border border-[#2a2a3e] rounded-xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-[#6b7bb8] uppercase tracking-wider flex items-center gap-2">
                            <Headphones :size="16" />
                            Titres populaires
                        </h3>
                    </div>

                    <div class="space-y-2">
                        <div v-if="!tracks || tracks.length === 0" class="text-sm text-gray-500 py-4 text-center">
                            Aucun morceau disponible.
                        </div>
                        <div v-for="(track, index) in tracks" :key="track.id"
                             class="flex items-center gap-3 p-2 rounded-lg hover:bg-[#2a2a3e] transition-colors group cursor-pointer"
                             @click="playTrack(track)">
                            <span class="text-sm text-gray-500 w-6 text-center group-hover:hidden" :class="{'text-emerald-400': currentTrack?.id === track.id}">{{ index + 1 }}</span>
                            <div class="w-6 h-6 flex items-center justify-center group-hover:flex hidden">
                                <Play v-if="currentTrack?.id !== track.id || !isPlaying" :size="16" class="text-[#6b7bb8]" />
                                <Pause v-else :size="16" class="text-emerald-400" />
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-white truncate" :class="{'text-emerald-400': currentTrack?.id === track.id}">
                                    {{ track.title }}
                                </div>
                                <div class="text-xs text-gray-400">{{ track.album?.title || 'Single' }}</div>
                            </div>

                            <div class="flex items-center gap-4 text-xs text-gray-400">
                                <span class="hidden sm:flex items-center gap-1">
                                    <Eye :size="12" />
                                    {{ formatNumber(track.plays_count) }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <Clock :size="12" />
                                    {{ formatDuration(track.duration) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-[#1a1a26]/50 border border-[#2a2a3e] rounded-xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-[#6b7bb8] uppercase tracking-wider flex items-center gap-2">
                            <Disc :size="16" />
                            Albums
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-if="!albums || albums.length === 0" class="text-sm text-gray-500 py-4 text-center col-span-2">
                            Aucun album publié pour le moment.
                        </div>
                        <div v-for="album in albums" :key="album.id"
                             class="bg-[#0d0d13] rounded-lg overflow-hidden border border-[#2a2a3e] hover:border-[#33437e] transition-all group">
                            <div class="aspect-square relative">
                                <img :src="album.image ? `/storage/${album.image}` : '/images/default-album.jpg'"
                                     :alt="album.title"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            </div>
                            <div class="p-3 space-y-2">
                                <h4 class="font-medium text-sm text-white truncate">{{ album.title }}</h4>
                                <div class="flex items-center justify-between text-xs text-gray-400">
                                    <span>{{ album.year || new Date(album.created_at).getFullYear() }} • {{ album.tracks_count || 0 }} titres</span>
                                    <span v-if="album.price > 0" class="text-amber-400 font-semibold">{{ album.price }} €</span>
                                    <span v-else class="text-emerald-400 font-medium">Gratuit</span>
                                </div>

                                <button v-if="album.price > 0" @click.stop="buyAlbum(album.id)"
                                        class="w-full mt-2 py-1.5 text-xs font-medium bg-amber-600 hover:bg-amber-500 rounded transition-colors text-white flex items-center justify-center gap-1.5">
                                    <ShoppingCart :size="13" />
                                    Acheter l'album
                                </button>
                                <Link v-else :href="`/albums/detail/${album.slug || album.id}`"
                                      class="w-full mt-2 py-1.5 text-xs font-medium bg-[#33437e] hover:bg-[#4a5a9e] rounded transition-colors text-white flex items-center justify-center">
                                    Écouter l'album
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-[#1a1a26]/50 border border-[#2a2a3e] rounded-xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-[#6b7bb8] uppercase tracking-wider flex items-center gap-2">
                            <MessageCircle :size="16" />
                            Commentaires sur ses morceaux
                        </h3>
                    </div>

                    <div class="space-y-4">
                        <div v-if="allTracksComments.length === 0" class="text-sm text-gray-500 py-4 text-center">
                            Aucun commentaire pour le moment.
                        </div>
                        <div v-for="comment in allTracksComments.slice(0, 5)" :key="comment.id" class="flex gap-3">

                            <img
                                v-if="comment.user?.pdp"
                                :src="`/storage/${comment.user.pdp}`"
                                :alt="comment.user.name"
                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover flex-shrink-0"
                            />

                            <span
                                v-else
                                class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold"
                            >
                                {{ (comment.user?.name || 'U').charAt(0).toUpperCase() }}
                            </span>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-white">{{ comment.user?.name || 'Anonyme' }}</span>
                                        <span class="text-xs text-gray-500">{{ formatDate(comment.created_at) }}</span>
                                    </div>
                                    <span class="text-[10px] bg-[#2a2a3e] text-[#6b7bb8] px-2 py-0.5 rounded">
                                        Sur : {{ comment.track_title }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-400 mt-0.5">
                                    "{{ comment.content }}"
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed bottom-0 left-0 right-0 bg-[#0d0d13]/95 backdrop-blur-md border-t border-[#2a2a3e] p-4 flex items-center justify-between z-50 shadow-[0_-10px_30px_rgba(0,0,0,0.6)]">

            <div class="flex items-center gap-3 min-w-[240px]">
                <div class="w-12 h-12 rounded bg-[#1a1a26] border border-[#2a2a3e] flex-shrink-0 overflow-hidden flex items-center justify-center shadow-inner">
                    <img v-if="currentTrack?.album?.image"
                         :src="getAlbumCover(currentTrack.album.image)"
                         class="w-full h-full object-cover" />
                    <Music v-else :size="20" class="text-[#6b7bb8]" />
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">
                        {{ currentTrack ? 'En cours de lecture' : 'Aucune piste sélectionnée' }}
                    </span>
                    <span class="text-sm font-semibold text-white truncate" :class="{'text-emerald-400': isPlaying}">
                        {{ currentTrack ? currentTrack.title : 'Aucune piste' }}
                    </span>
                    <span class="text-xs text-gray-400 truncate">
                        {{ currentTrack ? currentTrack.artist : artist.surname }}
                    </span>
                </div>
            </div>

            <div class="flex-1 flex flex-col justify-center px-4">
                <div class="flex items-center justify-between text-xs text-gray-400 mb-2">
                    <span>{{ formatDuration(currentTime) }}</span>
                    <span>{{ formatDuration(duration) }}</span>
                </div>
                <input
                    type="range"
                    min="0"
                    max="1"
                    step="0.001"
                    :value="progress"
                    @input="updateProgress"
                    :disabled="!currentTrack"
                    class="w-full accent-white"
                />
            </div>

            <div class="flex items-center gap-4">
                <button class="p-2 rounded-full text-gray-400 hover:text-white transition-colors">
                    <Heart :size="18" />
                </button>

                <button
                    @click="currentTrack ? togglePlayback() : null"
                    class="p-3 rounded-full bg-[#33437e] hover:bg-[#4a5a9e] text-white transition-all transform active:scale-95 shadow-lg flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="!currentTrack"
                >
                    <Pause v-if="isPlaying" :size="18" class="fill-current text-white" />
                    <Play v-else :size="18" class="fill-current text-white ml-0.5" />
                </button>
            </div>
        </div>

    </div>
</template>
