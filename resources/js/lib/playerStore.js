import { reactive } from 'vue';

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
