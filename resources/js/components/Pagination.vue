<script setup>
import { Link } from '@inertiajs/vue3'
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'
import { computed } from 'vue'

const props = defineProps({
    pagination: {
        type: Object,
        required: true,
    },
})

const prevLink = computed(() =>
    props.pagination.links?.find(l => l.label.includes('Previous'))
)
const nextLink = computed(() =>
    props.pagination.links?.find(l => l.label.includes('Next'))
)
const pageLinks = computed(() =>
    props.pagination.links?.filter(
        l => !l.label.includes('Previous') && !l.label.includes('Next')
    ) ?? []
)
</script>
<template>
    <div
        v-if="pagination.last_page > 1"
        class="flex items-center justify-between mt-6 flex-wrap gap-4"
    >
        <div class="text-sm text-gray-400">
            Affichage de {{ pagination.from }} à {{ pagination.to }} sur {{ pagination.total }} résultats
        </div>

        <div class="flex items-center gap-2">
            <!-- Previous -->
            <Link
                v-if="prevLink"
                :href="prevLink.url || '#'"
                :disabled="!prevLink.url"
                preserve-scroll
                preserve-state
                class="p-2 rounded-lg bg-[#121212]/80 border border-[#33437e] text-white transition-all"
                :class="prevLink.url ? 'hover:bg-[#33437e]/50' : 'opacity-50 cursor-not-allowed pointer-events-none'"
            >
                <ChevronLeft class="w-4 h-4" />
            </Link>

            <!-- Numbered pages -->
            <Link
                v-for="(link, idx) in pageLinks"
                :key="idx"
                :href="link.url || '#'"
                preserve-scroll
                preserve-state
                class="px-3 py-1 rounded-lg transition-all min-w-[2.25rem] text-center"
                :class="[
                    link.active
                        ? 'bg-[#33437e] text-white'
                        : 'bg-[#121212]/80 border border-[#33437e] text-gray-300 hover:bg-[#33437e]/50',
                    !link.url && 'opacity-50 cursor-not-allowed pointer-events-none'
                ]"
                v-html="link.label"
            />

            <!-- Next -->
            <Link
                v-if="nextLink"
                :href="nextLink.url || '#'"
                :disabled="!nextLink.url"
                preserve-scroll
                preserve-state
                class="p-2 rounded-lg bg-[#121212]/80 border border-[#33437e] text-white transition-all"
                :class="nextLink.url ? 'hover:bg-[#33437e]/50' : 'opacity-50 cursor-not-allowed pointer-events-none'"
            >
                <ChevronRight class="w-4 h-4" />
            </Link>
        </div>
    </div>
</template>
