<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import { Trash2, CreditCard, ArrowLeft, ShoppingCart } from 'lucide-vue-next';
import logo from '@/assets/images/logo.png'; // Ajuste le chemin vers ton logo si nécessaire
import backgroundImage from '@/assets/images/font_casque.png'; // Ajuste le chemin vers ton image de fond

const props = defineProps({
    albums: {
        type: Array,
        default: () => []
    }
});

const cartCount = ref(0);
const cartIds = ref<number[]>([]);
const cartAlbums = ref<any[]>([]);
const isProcessing = ref(false);

onMounted(() => {
    const savedCart = localStorage.getItem('music_cart');
    if (savedCart) {
        cartIds.value = JSON.parse(savedCart);
        cartCount.value = cartIds.value.length;
        fetchCartDetails();
    }
});

const fetchCartDetails = () => {
    if (props.albums.length > 0) {
        cartAlbums.value = props.albums.filter((album: any) => cartIds.value.includes(album.id));
    }
};

const totalPrice = computed(() => {
    return cartAlbums.value.reduce((sum, album) => {
        const price = parseFloat(album.price);
        return sum + (isNaN(price) ? 0 : price);
    }, 0);
});

const removeFromCart = (id: number) => {
    cartIds.value = cartIds.value.filter(itemId => itemId !== id);
    cartAlbums.value = cartAlbums.value.filter(album => album.id !== id);
    cartCount.value = cartIds.value.length;
    localStorage.setItem('music_cart', JSON.stringify(cartIds.value));

    // Notifier le reste de l'application au cas où
    window.dispatchEvent(new CustomEvent('cart-updated', { detail: cartIds.value.length }));
};

const checkout = () => {
    if (cartIds.value.length === 0) return;
    isProcessing.value = true;

    router.post('/purchase', {
        album_ids: cartIds.value
    }, {
        onSuccess: () => {
            localStorage.removeItem('music_cart');
            cartIds.value = [];
            cartAlbums.value = [];
            cartCount.value = 0;
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: 0 }));
            isProcessing.value = false;
            alert('Achat effectué avec succès !');
        },
        onError: () => {
            isProcessing.value = false;
        }
    });
};
</script>

<template>
    <div
        class="min-h-screen w-full bg-[#0d1329] text-white flex flex-col font-sans overflow-x-hidden"
        :style="{
            backgroundImage: `linear-gradient(rgba(0, 0, 0, 0.85), rgba(51, 67, 126, 0.35)), url(${backgroundImage})`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            backgroundAttachment: 'fixed'
        }"
    >

        <main class="flex-1 max-w-5xl w-full mx-auto p-4 md:p-8 mt-1">

            <Link href="/albums" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white mb-3 transition-colors group">
                <ArrowLeft class="w-4 h-4 transition-transform group-hover:-translate-x-1" />
                Retour aux albums
            </Link>

            <h1 class="text-3xl font-extrabold mb-8 text-white tracking-wide">Mon Panier d'Achat</h1>

            <div v-if="cartAlbums.length === 0" class="bg-[#33437e]/10 border border-[#33437e]/30 backdrop-blur-xl rounded-2xl p-12 text-center">
                <p class="text-gray-400 text-lg mb-6">Votre panier est actuellement vide.</p>
                <Link href="/albums" class="inline-block bg-[#33437e] hover:bg-[#4358a5] text-sm font-bold px-8 py-3 rounded-full transition-transform hover:scale-105">
                    Découvrir des albums
                </Link>
            </div>

            <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <div class="lg:col-span-2 space-y-4">
                    <div
                        v-for="album in cartAlbums"
                        :key="album.id"
                        class="flex items-center justify-between p-4 bg-[#33437e]/10 border border-[#33437e]/40 rounded-xl backdrop-blur-xl hover:border-[#33437e] transition-all"
                    >
                        <div class="flex items-center gap-4">
                            <img :src="`/storage/${album.image}`" class="w-16 h-16 object-cover rounded-lg border border-[#33437e]/30 flex-shrink-0" />
                            <div>
                                <h3 class="font-bold text-white text-base md:text-lg">{{ album.title }}</h3>
                                <p class="text-xs md:text-sm text-gray-400">Album . {{ album.artist?.surname || album.artist?.name || 'Artiste' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <span class="font-bold text-white text-sm md:text-base">
                                {{ !album.price || album.price == 0 || album.price === 'gratuit' ? 'Gratuit' : `${album.price} €` }}
                            </span>
                            <button @click="removeFromCart(album.id)" class="text-gray-400 hover:text-red-500 p-1 transition-colors">
                                <Trash2 class="w-5 h-5" />
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-[#0d1329]/90 border border-[#33437e] rounded-2xl p-6 backdrop-blur-2xl lg:sticky lg:top-6 shadow-2xl">
                    <h2 class="text-lg font-bold mb-4 border-b border-[#33437e]/40 pb-2 text-white">Résumé de la commande</h2>

                    <div class="flex justify-between text-sm text-gray-300 mb-2">
                        <span>Articles ({{ cartAlbums.length }})</span>
                        <span>{{ totalPrice.toFixed(2) }} €</span>
                    </div>

                    <div class="flex justify-between text-sm text-gray-300 mb-6">
                        <span>TVA (0%)</span>
                        <span>0.00 €</span>
                    </div>

                    <div class="flex justify-between font-bold text-base mb-6 pt-2 border-t border-[#33437e]/40">
                        <span>Total Général</span>
                        <span class="text-emerald-400 text-lg font-extrabold">{{ totalPrice.toFixed(2) }} €</span>
                    </div>

                    <button
                        @click="checkout"
                        :disabled="isProcessing"
                        class="w-full bg-[#33437e] hover:bg-[#4358a5] disabled:opacity-50 text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2 transition-all active:scale-[0.98]"
                    >
                        <CreditCard class="w-5 h-5" />
                        {{ isProcessing ? 'Traitement...' : 'Valider la commande' }}
                    </button>
                </div>

            </div>
        </main>
    </div>
</template>
