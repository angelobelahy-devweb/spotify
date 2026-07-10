<script setup>
import AlbumHeader from '@/components/angelo/album/AlbumHeader.vue'
import AlbumSongs from '@/components/angelo/album/AlbumSongs.vue'
import { defineProps, computed, onMounted } from 'vue'
import { ArrowLeft } from 'lucide-vue-next';
import { Link, usePage } from '@inertiajs/vue3'
import { playerStore } from '@/lib/playerStore'
import { openAuthModal } from '@/lib/authModalStore'

const page = usePage()
const isLoggedIn = computed(() => Boolean(page.props.auth?.user))

// Récupération de l'album envoyé par le contrôleur Laravel
const props = defineProps([
    'album'
]);

// Fonction utilitaire pour transformer la durée (ex: 202 secondes -> 3:22)
const formatDuration = (seconds) => {
    if (!seconds) return '0:00';
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
};

// On transforme la liste des tracks pour correspondre aux propriétés attendues par votre composant AlbumSongs
// const formattedSongs = computed(() => {
//     if (!props.album || !props.album.tracks) return [];
    
//     return props.album.tracks.map(track => ({
//         id: track.id,
//         title: track.title,
//         artist: props.album.artist?.surname || 'Artiste inconnu', // On récupère le nom de l'artiste de l'album
//         duration: formatDuration(track.duration), // Durée formatée en mm:ss
//         image: `/storage/${props.album.image}` // L'image du morceau est généralement la pochette de l'album
//     }));
// });
const formattedSongs = computed(() => {
    if (!props.album || !props.album.tracks) return [];
    
    return props.album.tracks.map(track => ({
        id: track.id,
        title: track.title,
        artist: props.album.artist?.surname || 'Artiste inconnu',
        duration: formatDuration(track.duration),
        image: `/storage/${props.album.image}`,
        file_path: `/storage/${track.file_path}`
    }));
});

onMounted(() => {
    const songs = formattedSongs.value;
    if (!isLoggedIn.value) {
        openAuthModal('Veuillez vous connecter pour écouter cet album.');
        return;
    }

    if (songs.length > 0) {
        playerStore.play(songs[0], songs);
    }
});

</script>

<template>
    <div v-if="album" class="backdrop-blur-md h-[max-content] bg-[#33437e]/20
        border border-[#33437e]
        backdrop-blur-xl rounded-xl p-4 md:p-8 text-white">
        
        <Link href="/albums" class="text-gray-400 hover:text-white flex items-center gap-1 text-sm cursor-pointer mb-5">
            <ArrowLeft class="w-4 h-4" /> Retour
        </Link>

        <!-- HEADER -->
        <AlbumHeader
            :title="album.title"
            :artist="album.artist?.surname || 'Artiste inconnu'"
            :image="`/storage/${album.image}`"
            :year="album.release_year"
            :totalSongs="formattedSongs.length" 
        />

        <!-- SONGS DYNAMIQUES -->
        <AlbumSongs
            :songs="formattedSongs"
        />

        

    </div>
    
    <div v-else class="text-center text-gray-400 py-10">
        Chargement de l'album en cours...
    </div>
</template>
