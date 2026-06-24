<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { Plus, Award, Lock, Search } from 'lucide-vue-next'
import { ref, computed } from 'vue'

const props = defineProps<{ artists: Array<{ id: number; surname: string; user: { slug: string; pdp: string; name?: string } }> }>()

const page = usePage()
const searchQuery = ref('')
const normalizedPlan = computed(() => String(page.props.auth?.user?.plan || '').toLowerCase())
const userPlanLabel = computed(() => page.props.auth?.user?.plan ? String(page.props.auth.user.plan).toUpperCase() : '')
const canCreateArtist = computed(() => ['premium', 'vip'].includes(normalizedPlan.value))

const filteredArtists = computed(() => {
    const search = searchQuery.value.trim().toLowerCase()

    if (!search) {
        return props.artists
    }

    return props.artists.filter((artist: any) => {
        const title = String(artist.surname || artist.user?.name || '').toLowerCase()

        return title.includes(search)
    })
})
</script>

<template>
    <div class="space-y-6 p-6">
        <div class="grid gap-4 md:grid-cols-[1fr_auto] items-start bg-[#10121a]/90 border border-[#33437e] rounded-3xl p-6 shadow-[0_25px_80px_rgba(0,0,0,0.4)]">
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-[#8a9ed6]/70">Espace Artistes</p>
                        <h1 class="text-3xl font-bold text-[#e4e8f3]">Les artistes du moment</h1>
                        <p class="mt-2 text-sm text-gray-400 max-w-2xl">
                            Découvrez les talents et accédez rapidement à l'option « Devenir artiste » si vous avez un abonnement Premium ou VIP.
                        </p>
                    </div>

                    <div class="rounded-3xl bg-[#11131d]/80 border border-[#2a3b70] p-4 shadow-[0_18px_40px_rgba(51,67,126,0.18)]">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs uppercase tracking-[0.18em] text-[#9ea8ff]">Forfait</span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-[#1b2340] px-3 py-2 text-xs font-semibold text-[#d3d9ff]">
                                <Award class="w-4 h-4 text-yellow-300" />
                                {{ userPlanLabel || 'Aucun' }}
                            </span>
                        </div>
                        <p class="mt-3 text-sm text-gray-400">
                            {{ canCreateArtist ? 'Vous pouvez créer votre profil artiste.' : 'Passez au Premium pour téléverser votre musique et devenir artiste.' }}
                        </p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <Link
                                v-if="canCreateArtist"
                                href="/artistes/create"
                                class="inline-flex items-center gap-2 rounded-full bg-[#33437e] px-4 py-2 text-xs font-bold uppercase text-white shadow-lg shadow-[#33437e]/20 hover:bg-[#3f5dce] transition"
                            >
                                <Plus class="w-4 h-4" />
                                Créer un profil artiste
                            </Link>
                            <Link
                                href="/subscription"
                                class="inline-flex items-center gap-2 rounded-full border border-[#556cff]/50 bg-[#0f1322] px-4 py-2 text-xs font-semibold uppercase text-[#dbe5ff] hover:bg-[#141c35] transition"
                                title="Voir les formules d'abonnement"
                            >
                                <Lock class="w-4 h-4 text-[#82a1ff]" />
                                {{ canCreateArtist ? 'Gérer mon abonnement' : 'S’abonner' }}
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-2 rounded-2xl border border-[#2f439a] bg-[#111523]/90 px-4 py-3 shadow-inner shadow-[#001344]/20">
                        <Search class="w-4 h-4 text-[#8ea3ff]" />
                        <input
                            v-model="searchQuery"
                            type="search"
                            placeholder="Rechercher un artiste"
                            class="w-full bg-transparent text-sm text-white placeholder:text-gray-500 outline-none"
                        />
                    </div>

                    <p class="text-sm text-gray-400">
                        {{ filteredArtists.length }} artiste(s) affiché(s)
                    </p>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3 xl:grid-cols-4">
            <template v-if="filteredArtists.length">
                <div v-for="artist in filteredArtists" :key="artist.id">
                    <ArtisteCard
                        :slug="artist.user.slug"
                        :artist="artist.surname"
                        :image="`/storage/${artist.user.pdp}`"
                    />
                </div>
            </template>

            <div v-else class="col-span-full rounded-3xl border border-[#33437e] bg-[#0f1221]/80 p-8 text-center text-gray-400 shadow-[0_20px_50px_rgba(0,0,0,0.25)]">
                <p class="text-lg font-semibold text-white mb-2">Aucun artiste ne correspond à votre recherche.</p>
                <p class="text-sm">Essayez un autre prénom, nom ou mot-clé.</p>
            </div>
        </div>
    </div>
</template>
