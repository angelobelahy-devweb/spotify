<script setup lang="ts">
import AlbumCard from '@/components/angelo/cards/AlbumCard.vue';
import { Link } from '@inertiajs/vue3'
import { Plus } from 'lucide-vue-next';
import { defineProps, ref, onMounted } from 'vue'

const props = defineProps({
    albums: Array,
    purchasedAlbumIds: {
        type: Array,
        default: () => []
    }
});

const cartItems = ref<number[]>([])

const isPurchased = (albumId: number) => {
    return props.purchasedAlbumIds.includes(albumId);
};

onMounted(() => {
    const savedCart = localStorage.getItem('music_cart')
    if (savedCart) {
        cartItems.value = JSON.parse(savedCart)
    }
})

const handleAddToCart = (album: any) => {
    if (!cartItems.value.includes(album.id)) {
        cartItems.value.push(album.id)
        localStorage.setItem('music_cart', JSON.stringify(cartItems.value))

        // Émettre un événement global pour que le layout mette à jour son compteur
        window.dispatchEvent(new CustomEvent('cart-updated', { detail: cartItems.value.length }))
    }
}

const handleDownload = (album: any) => {
    // Logique de téléchargement (Exemple: redirection vers le fichier ou un zip de l'album)
    alert(`Téléchargement de l'album : ${album.title}`);
};
</script>

<template>
    <div class="flex gap-5 items-center">
        <h1 class="text-[#e4e8f3d0] text-2xl my-2">Albums</h1>
        <Link href="/albums/create" class="flex gap-2 items-center bg-[#121212]/80 border-2 border-[#33437e] rounded-full w-[max-content] p-1 hover:translate-y-1 transition-all duration-300">
            <div class="bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all duration-300 p-1 rounded-full text-sm font-bold flex items-center justify-center gap-2 w-6 h-6 cursor-pointer disabled:opacity-50">
                <Plus />
            </div>
            <span class="text-xs text-white uppercase">Créer</span>
        </Link>
    </div>
    <div class="w-full flex flex-wrap items-center justify-start gap-2">
        <div v-for="album in albums" :key="album.id" class="relative group">
            <Link :href="`/albums/detail/${album.slug}`">
                <AlbumCard
                    :title="album.title"
                    :artist="album.artist.surname"
                    :image="`/storage/${album.image}`"
                    :price="album.price ?? 'gratuit'"
                    :isAdded="cartItems.includes(album.id)"
                    :isPurchased="isPurchased(album.id)"
                    @add-to-cart="handleAddToCart(album)"
                    @download="handleDownload(album)"
                />
            </Link>
        </div>
    </div>
</template>
