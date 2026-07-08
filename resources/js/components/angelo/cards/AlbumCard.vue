<script setup>
defineProps({
    title: String,
    artist: String,
    image: String,
    price: [String, Number],
    isAdded: Boolean,
    isPurchased: Boolean
});

// Ajout de l'événement 'download' pour avertir le parent au clic
defineEmits(['addToCart', 'download'])
</script>

<template>
    <div
        class="
        bg-[#33437e]/20
        border border-[#33437e]
        backdrop-blur-xl
        transition
        p-2
        rounded-xs
        group
        w-[200px]
        shadow-[0_2_20px_red]
        hover:translate-y-1
        "
    >
        <div class="relative">
            <img
                :src="image"
                class="
                w-full
                h-[150px]
                object-cover
                "
            />
        </div>

        <h2 class="text-white font-bold mt-4 truncate">
            {{ title }}
        </h2>

        <p class="text-gray-400 text-sm">
            Album . {{ artist }}
        </p>

        <div class="flex items-center justify-between mt-3 pt-2 border-t border-[#33437e]/40">
            <span class="text-xs font-semibold text-white">
                {{ !price || price == 0 || price === 'gratuit' ? 'Gratuit' : `${price} €` }}
            </span>

            <button
                v-if="isPurchased"
                @click.prevent="$emit('download')"
                class="text-[10px] uppercase px-2 py-1 rounded-sm font-bold bg-emerald-600 text-white hover:bg-emerald-500 active:scale-95 transition-all"
            >
                Télécharger
            </button>

            <button
                v-else
                @click.prevent="$emit('addToCart')"
                class="text-[10px] uppercase px-2 py-1 rounded-sm font-bold transition-all"
                :class="isAdded
                    ? 'bg-blue-600 text-white cursor-default'
                    : 'bg-[#33437e] text-white hover:bg-[#4358a5] active:scale-95'"
                :disabled="isAdded"
            >
                {{ isAdded ? 'Ajouté' : 'Acheter' }}
            </button>
        </div>
    </div>
</template>
