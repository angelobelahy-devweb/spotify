<?php


Pour écouter vos musiques, nous allons installer un lecteur audio global en bas de votre page. Dès que l'utilisateur cliquera sur une chanson de la liste AlbumSongs.vue, la musique se lancera automatiquement.Pour cela, nous allons utiliser un State partagé très simple avec Vue 3 (reactive) pour que toutes vos pages puissent contrôler le même lecteur de musique.Voici les modifications à faire sur vos composants :1. Créer le lecteur audio partagé (Le Player Store)Créez un nouveau fichier nommé playerStore.js dans votre dossier resources/js/ (par exemple dans resources/js/lib/playerStore.js) :javascriptimport { reactive } from 'vue';

export const playerStore = reactive({
    currentTrack: null, // Stocke la musique en cours d'écoute
    isPlaying: false,   // Statut de lecture
    audioElement: null, // L'objet Audio natif de l'ordinateur

    // Fonction pour lancer une musique
    play(track) {
        // Si une musique joue déjà, on l'arrête complètement
        if (this.audioElement) {
            this.audioElement.pause();
        }

        this.currentTrack = track;
        this.isPlaying = true;

        // On crée le lecteur avec le vrai chemin du fichier MP3 stocké sur Laravel
        this.audioElement = new Audio(track.file_path);
        this.audioElement.play();

        // Si la musique se termine, on change le statut
        this.audioElement.addEventListener('ended', () => {
            this.isPlaying = false;
        });
    },

    // Fonction pour mettre en pause / lecture
    toggle() {
        if (!this.audioElement) return;
        
        if (this.isPlaying) {
            this.audioElement.pause();
            this.isPlaying = false;
        } else {
            this.audioElement.play();
            this.isPlaying = true;
        }
    }
});
Utilisez le code avec précaution.2. Modifier AlbumDetail.vue pour envoyer le fichier audioNous devons modifier la liste des chansons envoyées au composant enfant pour y inclure le vrai chemin du fichier audio (track.file_path).Modifiez la fonction formattedSongs dans votre fichier AlbumDetail.vue :javascriptconst formattedSongs = computed(() => {
    if (!props.album || !props.album.tracks) return [];
    
    return props.album.tracks.map(track => ({
        id: track.id,
        title: track.title,
        artist: props.album.artist?.surname || 'Artiste inconnu',
        duration: formatDuration(track.duration),
        image: `/storage/${props.album.image}`,
        // AJOUT REQUIS : On passe le vrai lien du fichier MP3 public
        file_path: `/storage/${track.file_path}` 
    }));
});
Utilisez le code avec précaution.3. Activer le clic dans votre liste AlbumSongs.vueOuvrez le fichier de votre composant AlbumSongs.vue (le tableau ou la liste qui affiche les lignes des morceaux). Nous allons lui dire d'appeler notre playerStore lors d'un clic.Modifiez-le pour ajouter l'événement de clic sur chaque ligne de chanson :html<script setup>
import { playerStore } from '@/lib/playerStore'; // Importation du lecteur global

defineProps({
    songs: Array
});
</script>

<template>
    <div class="mt-6 flex flex-col gap-2">
        <!-- Boucle sur vos chansons -->
        <div 
            v-for="(song, index) in songs" 
            :key="song.id"
            @click="playerStore.play(song)"
            class="flex items-center justify-between p-3 rounded-lg hover:bg-white/10 cursor-pointer group transition-all"
        >
            <div class="flex items-center gap-4">
                <span class="text-gray-400 text-sm group-hover:text-[#33437e] font-bold w-4">
                    {{ index + 1 }}
                </span>
                <img :src="song.image" class="w-10 h-10 rounded object-cover" />
                <div>
                    <h3 class="font-medium text-sm" :class="{'text-[#33437e]': playerStore.currentTrack?.id === song.id}">
                        {{ song.title }}
                    </h3>
                    <p class="text-xs text-gray-400">{{ song.artist }}</p>
                </div>
            </div>
            
            <span class="text-xs text-gray-400 font-medium">
                {{ song.duration }}
            </span>
        </div>
    </div>
</template>
Utilisez le code avec précaution.4. Afficher la barre de lecture en bas de l'écranPour voir la musique qui joue (et pouvoir mettre en pause), ajoutez ce bloc de code tout en bas de votre fichier PageLayout.vue (ou votre layout par défaut). Il affichera une magnifique barre noire collée au bas de l'écran dès qu'une musique démarrera.html<!-- Tout en bas de votre Layout principal -->
<script setup>
import { playerStore } from '@/lib/playerStore';
import { Play, Pause, Disc } from 'lucide-vue-next';
</script>

<template>
    <div class="relative min-h-screen">
        
        <!-- Votre contenu actuel de l'application -->
        <slot />

        <!-- BARRE DE LECTURE GLOBALE -->
        <div 
            v-if="playerStore.currentTrack" 
            class="fixed bottom-4 left-4 right-4 md:left-1/4 md:right-1/4 bg-[#121212] border border-[#2e2e2e] p-4 rounded-xl flex items-center justify-between shadow-2xl animate-fade-in z-50 text-white"
        >
            <div class="flex items-center gap-3">
                <img :src="playerStore.currentTrack.image" class="w-12 h-12 rounded-lg object-cover" :class="{'animate-spin [animation-duration:8s]': playerStore.isPlaying}" />
                <div>
                    <h4 class="font-bold text-sm truncate max-w-[150px]">{{ playerStore.currentTrack.title }}</h4>
                    <p class="text-xs text-gray-400 truncate max-w-[150px]">{{ playerStore.currentTrack.artist }}</p>
                </div>
            </div>

            <!-- Bouton Pause / Play -->
            <button 
                @click="playerStore.toggle()" 
                class="bg-[#33437e] hover:bg-[#364a92] text-white p-3 rounded-full active:scale-95 transition-all cursor-pointer"
            >
                <Pause v-if="playerStore.isPlaying" class="w-5 h-5" />
                <Play v-else class="w-5 h-5" />
            </button>
        </div>

    </div>
</template>
Utilisez le code avec précaution.Une fois ces éléments reliés, cliquez sur une ligne de votre album : le fichier MP3 stocké dans votre dossier public/tracks se lancera instantanément dans vos haut-parleurs !La musique se lance-t-elle correctement ? Si vous le souhaitez, nous pourrons ensuite ajouter une barre de progression de lecture ou la gestion du morceau suivant / précédent.                         