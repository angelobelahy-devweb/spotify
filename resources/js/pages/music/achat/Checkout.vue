<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, computed, watch } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import { Trash2, CreditCard, ArrowLeft, ShoppingCart } from 'lucide-vue-next';
import logo from '@/assets/images/logo.png';
import backgroundImage from '@/assets/images/font_casque.png';

interface Artist {
    name?: string;
    surname?: string;
}

interface Album {
    id: number;
    title: string;
    image: string;
    price: string | number;
    artist?: Artist;
}

const props = defineProps<{
    albums?: Album[];
}>();

const page = usePage();
const cartCount = ref(0);
const cartIds = ref<number[]>([]);
const cartAlbums = ref<Album[]>([]);
const isProcessing = ref(false);

const loadCartFromStorage = () => {
    const savedCart = localStorage.getItem('music_cart');
    if (savedCart) {
        cartIds.value = JSON.parse(savedCart);
        cartCount.value = cartIds.value.length;
    } else {
        cartIds.value = [];
        cartCount.value = 0;
    }
    fetchCartDetails();
};

const fetchCartDetails = () => {
    if (props.albums && props.albums.length > 0) {
        cartAlbums.value = props.albums.filter((album) => cartIds.value.includes(album.id));
    } else {
        cartAlbums.value = [];
    }
};

const handleGlobalCartSync = (e: Event) => {
    const count = (e as CustomEvent).detail;
    if (count === 0) {
        cartIds.value = [];
        cartAlbums.value = [];
        cartCount.value = 0;
    } else {
        loadCartFromStorage();
    }
};

// Initial load
loadCartFromStorage();

watch(() => props.albums, () => {
    fetchCartDetails();
}, { immediate: true, deep: true });

// Shared state-clearing function
const clearCartState = () => {
    localStorage.removeItem('music_cart');
    cartIds.value = [];
    cartAlbums.value = [];
    cartCount.value = 0;
    window.dispatchEvent(new CustomEvent('cart-updated', { detail: 0 }));
};

// Automatically clear if a success flash message is sent to whatever page we land on
watch(
    () => page.props.flash,
    (newFlash) => {
        if (newFlash?.success) {
            clearCartState();
        }
    },
    { deep: true, immediate: true }
);

// 🔥 SYSTEM FIX: Listens for the exact window unload event when redirecting to Stripe
const clearCartOnLeave = () => {
    if (isProcessing.value) {
        clearCartState();
    }
};

onMounted(() => {
    window.addEventListener('cart-updated', handleGlobalCartSync);
    window.addEventListener('beforeunload', clearCartOnLeave);
});

onBeforeUnmount(() => {
    window.removeEventListener('cart-updated', handleGlobalCartSync);
    window.removeEventListener('beforeunload', clearCartOnLeave);
});

const totalPrice = computed(() => {
    return cartAlbums.value.reduce((sum, album) => {
        const price = typeof album.price === 'string' ? parseFloat(album.price) : album.price;
        return sum + (isNaN(price) ? 0 : price);
    }, 0);
});

const removeFromCart = (id: number) => {
    cartIds.value = cartIds.value.filter(itemId => itemId !== id);
    cartAlbums.value = cartAlbums.value.filter(album => album.id !== id);
    cartCount.value = cartIds.value.length;
    localStorage.setItem('music_cart', JSON.stringify(cartIds.value));

    window.dispatchEvent(new CustomEvent('cart-updated', { detail: cartIds.value.length }));
};

const checkout = () => {
    if (cartIds.value.length === 0) return;
    isProcessing.value = true;

    router.post('/purchase', {
        album_ids: cartIds.value
    }, {
        // If the backend returns validation errors, cancel processing status
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
                            <button @click="removeFromCart(album.id)" class="text-gray-400 hover:text-red-500 p-1 transition-colors" :disabled="isProcessing">
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
                        class="w-full bg-[#33437e] hover:bg-[#4358a5] disabled:opacity-70 disabled:cursor-not-allowed text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2 transition-all active:scale-[0.98]"
                    >
                        <svg v-if="isProcessing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <CreditCard v-else class="w-5 h-5" />

                        <span>{{ isProcessing ? 'Redirection vers Stripe...' : 'Valider la commande' }}</span>
                    </button>
                </div>
            </div>
        </main>
    </div>
</template>
