<script setup>
import AlbumHeader from '@/components/angelo/album/AlbumHeader.vue'
import AlbumSongs from '@/components/angelo/album/AlbumSongs.vue'
import { defineProps, computed, ref, watch } from 'vue'
import { ArrowLeft, MessageCircle, SendIcon } from 'lucide-vue-next';
import { Link, router } from '@inertiajs/vue3'
import PrimaryButton from '@/components/angelo/button/PrimaryButton.vue';

import { usePage } from '@inertiajs/vue3'

const page = usePage()
const currentUser = page.props.auth.user

const formatDate = (date) => {
    return new Date(date).toLocaleString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    })
}
// Props from Inertia (we expect `track` to be provided)
const props = defineProps([
    'track'
]);

const track = computed(() => props.track || {});

// Comments reactive list
const comments = ref(track.value.comments ? [...track.value.comments] : []);

watch(track, (newTrack) => {
    comments.value = newTrack.comments ? [...newTrack.comments] : [];
});

const newComment = ref('');

// Format duration helper
const formatDuration = (seconds) => {
    if (!seconds) return '0:00';
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
};

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

// Submit a comment via fetch to the new route
// const submitComment = async () => {
//     if (!newComment.value.trim()) return;
//     const slug = track.value.slug;
//     try {
//         const tokenMeta = document.querySelector('meta[name="csrf-token"]');
//         const csrf = tokenMeta ? tokenMeta.getAttribute('content') : null;
//         console.log('slug =', slug);
//         console.log('url =', `/tracks/${slug}/comments`);
//         const res = await fetch(`/tracks/${slug}/comments`, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': csrf,
//                 'Accept': 'application/json'
//             },
//             credentials: 'same-origin',
//             body: JSON.stringify({ content: newComment.value })
//         });

//         if (!res.ok) {
//             console.log('Status :', res.status);

//             const text = await res.text();

//             console.log(text);

//             return;
//         }

//         const data = await res.json();
//         if (data.comment) {
//             comments.value.unshift({
//                 id: data.comment.id,
//                 content: data.comment.content,
//                 created_at: data.comment.created_at,
//                 user: data.comment.user,
//             });
//             newComment.value = '';
//         }
//     } catch (e) {
//         console.error(e);
//     }
// };

const submitComment = () => {
    if (!newComment.value.trim()) return;

    router.post(
        `/tracks/${track.value.slug}/comments`,
        {
            content: newComment.value
        },
        {
            preserveScroll: true,

            onSuccess: () => {
                newComment.value = '';
            },

            onError: (errors) => {
                console.log(errors);
            }
        }
    );
};
</script>

<template>
    <div class="backdrop-blur-md h-[max-content] bg-[#33437e]/20
        border border-[#33437e]
        backdrop-blur-xl rounded-xl p-4 md:p-8 text-white">

        <Link href="/" class="text-gray-400 hover:text-white flex items-center gap-1 text-sm cursor-pointer mb-5">
            <ArrowLeft class="w-4 h-4" /> Retour
        </Link>

        <!-- HEADER -->
        <div class="grid gird-cols-1 md:grid-cols-2">
            <div class=" flex flex-col md:flex-row flex-wrap gap-6 items-center bg-gradient-to-r  from-[#33437e]/30 to-transparent p-6 rounded-xl ">
                <!-- IMAGE -->
                <img :src="`/storage/${track.album?.image}`" class=" w-[150px]  h-[150px]  object-cover shadow-2xl rounded-lg">
                <!-- INFO -->
                <div class="flex-1">
                    <h1 class=" text-white text-4xl md:text-xl font-black mt-3 ">{{ track.title }}</h1>
                    <div class=" flex flex-col flex-wrap items-start gap-2 mt-4 text-gray-300    ">
                        <span class="font-bold text-white">{{ track.album?.artist?.surname }}</span>
                        <span>{{ comments.length }} Commentaire{{ comments.length > 1 ? 's' : '' }}</span>
                    </div>
                </div>
            </div>
            <!-- Commentaire -->
            <div class="mb-4">
                <div class="relative mt-2">
                    <MessageCircle class="absolute left-4 top-5 -translate-y-1/2 w-5 h-5 text-gray-500" />
                    <textarea v-model="newComment" placeholder="Ex : Entrez votre commentaire" class="w-full bg-transparent text-white border border-gray-500 focus:border-[#33437e] rounded-md pl-12 pr-2 py-2 outline-none transition-all"></textarea>
                    <PrimaryButton @click.prevent="submitComment"
                        size="sm">
                        <SendIcon class="fill-white text-white w-4 h-4" /> Envoyez
                    </PrimaryButton>
                </div>
                <span class="block text-sm font-medium m-2">Tous les commentaires : </span>
                <!--Tout les commentaires-->
                <div class="w-full max-h-[400px] overflow-y-auto flex flex-col gap-3 p-2">

                    <div
                        v-for="c in comments"
                        :key="c.id"
                        class="flex w-full"
                        :class="c.user.id === currentUser.id ? 'justify-end' : 'justify-start'"
                    >

                        <div
                            class="flex items-end gap-2 max-w-[90%] sm:max-w-[75%]"
                            :class="c.user.id === currentUser.id ? 'flex-row-reverse' : 'flex-row'"
                        >

                            <!-- Photo -->
                            <div class="flex-shrink-0">
                                <img
                                    v-if="c.user?.pdp"
                                    :src="`/storage/${c.user.pdp}`"
                                    :alt="c.user.name"
                                    class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover"
                                />

                                <span
                                    v-else
                                    class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold"
                                >
                                    {{ (c.user?.name || 'U').charAt(0).toUpperCase() }}
                                </span>
                            </div>

                            <!-- Bulle -->
                            <div
                                class="px-4 py-2 rounded-2xl shadow-md break-words"
                                :class="
                                    c.user.id === currentUser.id
                                        ? 'bg-black text-white rounded-br-sm'
                                        : 'bg-[#33436e] text-white rounded-bl-sm'
                                "
                            >
                                <div class="text-xs font-semibold mb-1">
                                    {{ c.user.name || track.album?.artist?.surname || 'Utilisateur' }}
                                </div>

                                <p class="text-sm whitespace-pre-wrap">
                                    {{ c.content }}
                                </p>

                                <div class="text-[11px] opacity-70 mt-2 text-right">
                                    {{ formatDate(c.created_at) }}
                                </div>
                            </div>

                        </div>

                    </div>

                    <div
                        v-if="comments.length === 0"
                        class="text-center text-gray-400 py-4"
                    >
                        Aucun commentaire pour le moment.
                    </div>

                </div>
            </div>
        </div>

        <!-- SONGS DYNAMIQUES -->
    </div>
</template>
