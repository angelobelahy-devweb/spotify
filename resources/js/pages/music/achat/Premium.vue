<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const words = ['Premium', 'Illimité', 'Sans pub', 'Haute fidélité', 'Hors ligne']
const currentWord = ref(words[0])
const isFading = ref(false)
let currentIndex = 0
let intervalId = null

const changeWord = () => {
    isFading.value = true
    
    setTimeout(() => {
        currentIndex = (currentIndex + 1) % words.length
        currentWord.value = words[currentIndex]
        isFading.value = false
    }, 500)
}

onMounted(() => {
    intervalId = setInterval(changeWord, 2500)
})

onUnmounted(() => {
    if (intervalId) {
        clearInterval(intervalId)
    }
})
</script>

<template>
    <!-- ========== HERO ========== -->
    <section class="relative text-white py-8 px-4 text-center backdrop-blur-md rounded-2xl overflow-hidden">
        <!-- Fond avec coupure nette au milieu -->
        <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(51,67,126,0.3) 0%, rgba(51,67,126,0.3) 50%, transparent 50%, transparent 100%);"></div>
        <!-- Effet de brillance -->
        <div class="absolute inset-0" style="background: radial-gradient(ellipse at 30% 50%, rgba(51,67,126,0.2) 0%, transparent 70%);"></div>
        
        <div class="relative z-10 max-w-4xl mx-auto">
            <span class="inline-block bg-[#212121]/80 backdrop-blur-sm px-4 py-1 rounded-full text-xs font-semibold tracking-wider uppercase border border-white/10 mb-4">
                <i class="fa-regular fa-gem mr-2 text-[#33437e]"></i> Offre limitée
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight">
                Passez à 
                <span style="-webkit-text-stroke: 2px #212121; text-stroke: 2px #212121;" class="text-[#33437e] underline decoration-[#33437e]/30">
                    <span 
                        class="inline-block transition-all duration-500 ease-in-out min-w-[140px]"
                        :class="{
                            'opacity-0 -translate-y-2': isFading,
                            'opacity-100 translate-y-0': !isFading
                        }"
                    >
                        {{ currentWord }}
                    </span>
                </span>
            </h1>
            <p class="text-base md:text-lg text-gray-200 max-w-2xl mx-auto mt-4 opacity-90">
                Écoutez sans limites, téléchargez vos titres et profitez d’un son haute fidélité. 
                Découvrez tous les avantages exclusifs.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="#" class="bg-[#33437e] hover:bg-[#324588] text-white font-bold px-4 py-2 rounded-full transition flex items-center gap-2 shadow-lg shadow-[#33437e]/30">
                    <i class="fa-solid fa-headphones"></i> Essayer 1 mois offert
                </a>
                <a href="#" class="border border-white/30 hover:border-white bg-white/5 hover:bg-white/10 px-4 py-2 rounded-full transition flex items-center gap-2 backdrop-blur-sm">
                    <i class="fa-regular fa-circle-play"></i> Voir les plans
                </a>
            </div>
        </div>
    </section>

    <!-- ========== CARDS : AVANTAGES PREMIUM ========== -->
    <section class="max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold">✨ Avantages Premium</h2>
                <p class="text-gray-400 mt-1">Ce qui rend l’expérience unique</p>
            </div>
            <a href="#" class="text-sm text-[#ffffff] hover:underline flex items-center gap-1">
                Voir tout <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Carte 1 -->
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 card-hover transition">
                <div class="w-12 h-12 rounded-full bg-[#33437e]/50 flex items-center justify-center text-[#33437e] text-xl mb-4">
                    <i class="fa-solid fa-wifi"></i>
                </div>
                <h3 class="font-bold text-lg">Écoute hors ligne</h3>
                <p class="text-sm text-gray-300 mt-1 leading-relaxed">Téléchargez vos playlists et albums pour écouter sans connexion.</p>
            </div>
            <!-- Carte 2 -->
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 card-hover transition">
                <div class="w-12 h-12 rounded-full bg-[#33437e]/50 flex items-center justify-center text-[#33437e] text-xl mb-4">
                    <i class="fa-solid fa-music"></i>
                </div>
                <h3 class="font-bold text-lg">Son haute fidélité</h3>
                <p class="text-sm text-gray-300 mt-1 leading-relaxed">Profitez d’un son lossless et d’une qualité studio.</p>
            </div>
            <!-- Carte 3 -->
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 card-hover transition">
                <div class="w-12 h-12 rounded-full bg-[#33437e]/50 flex items-center justify-center text-[#33437e] text-xl mb-4">
                    <i class="fa-regular fa-circle-play"></i>
                </div>
                <h3 class="font-bold text-lg">Lecture illimitée</h3>
                <p class="text-sm text-gray-300 mt-1 leading-relaxed">Aucune interruption, passez d’un morceau à l’autre sans publicité.</p>
            </div>
            <!-- Carte 4 -->
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 card-hover transition">
                <div class="w-12 h-12 rounded-full bg-[#33437e]/50 flex items-center justify-center text-[#33437e] text-xl mb-4">
                    <i class="fa-regular fa-compass"></i>
                </div>
                <h3 class="font-bold text-lg">Découvertes exclusives</h3>
                <p class="text-sm text-gray-300 mt-1 leading-relaxed">Accédez à des playlists et podcasts réservés aux abonnés.</p>
            </div>
        </div>
    </section>

    <!-- ========== FORFAITS / PLANS ========== -->
    <section class="max-w-7xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-bold">🎧 Choisissez votre plan</h2>
            <p class="text-gray-400">Annulable à tout moment, 1 mois offert</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Plan Individuel -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 flex flex-col card-hover transition backdrop-blur-md">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-[#5d6b9c] [text-shadow:0_0_15px_rgba(51,67,126,0.6),0_0_30px_rgba(51,67,126,0.3)]">INDIVIDUEL</span>
                    <span class="bg-[#33437e]/20 text-[#5d6b9c] text-xs px-3 py-1 rounded-full">Populaire</span>
                </div>
                <h3 class="text-2xl font-bold mt-2">9,99 €<span class="text-sm font-normal text-gray-400">/mois</span></h3>
                <ul class="mt-4 space-y-2 text-sm text-gray-300 flex-1">
                    <li><i class="fa-regular fa-circle-check text-[#33437e] mr-2"></i> 1 compte premium</li>
                    <li><i class="fa-regular fa-circle-check text-[#33437e] mr-2"></i> Écoute hors ligne</li>
                    <li><i class="fa-regular fa-circle-check text-[#33437e] mr-2"></i> Son lossless</li>
                </ul>
                <a href="#" class="mt-6 bg-[#33437e] hover:bg-[#324588] text-white font-bold py-2.5 rounded-full text-center transition">S’abonner</a>
            </div>

            <!-- Plan Duo -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 flex flex-col card-hover transition relative backdrop-blur-md">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#33437e] text-white text-xs font-bold px-4 py-1 rounded-full">Économie</div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-white [text-shadow:0_0_15px_rgba(51,67,126,0.6),0_0_30px_rgba(51,67,126,0.3)]">DUO</span>
                    <span class="bg-white/10 text-gray-200 text-xs px-3 py-1 rounded-full">2 comptes</span>
                </div>
                <h3 class="text-2xl font-bold mt-2">13,99 €<span class="text-sm font-normal text-gray-400">/mois</span></h3>
                <ul class="mt-4 space-y-2 text-sm text-gray-300 flex-1">
                    <li><i class="fa-regular fa-circle-check text-[#33437e] mr-2"></i> 2 comptes premium</li>
                    <li><i class="fa-regular fa-circle-check text-[#33437e] mr-2"></i> Playlists partagées</li>
                    <li><i class="fa-regular fa-circle-check text-[#33437e] mr-2"></i> Écoute simultanée</li>
                </ul>
                <a href="#" class="mt-6 bg-[#33437e] hover:bg-[#324588] text-white font-bold py-2.5 rounded-full text-center transition">S’abonner</a>
            </div>

            <!-- Plan Famille -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 flex flex-col card-hover transition backdrop-blur-md">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-white [text-shadow:0_0_15px_rgba(51,67,126,0.6),0_0_30px_rgba(51,67,126,0.3)]">FAMILLE</span>
                    <span class="bg-white/10 text-gray-200 text-xs px-3 py-1 rounded-full">Jusqu’à 6</span>
                </div>
                <h3 class="text-2xl font-bold mt-2">17,99 €<span class="text-sm font-normal text-gray-400">/mois</span></h3>
                <ul class="mt-4 space-y-2 text-sm text-gray-300 flex-1">
                    <li><i class="fa-regular fa-circle-check text-[#33437e] mr-2"></i> 6 comptes premium</li>
                    <li><i class="fa-regular fa-circle-check text-[#33437e] mr-2"></i> Contrôle parental</li>
                    <li><i class="fa-regular fa-circle-check text-[#33437e] mr-2"></i> Playlists familiales</li>
                </ul>
                <a href="#" class="mt-6 bg-[#33437e] hover:bg-[#324588] text-white font-bold py-2.5 rounded-full text-center transition">S’abonner</a>
            </div>
        </div>
    </section>
</template>

<style scoped>
.gradient-bg {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
}

.card-hover:hover {
    transform: translateY(-6px);
    transition: all 0.25s ease;
    box-shadow: 0 20px 30px -10px rgba(51, 67, 126, 0.3);
}
</style>