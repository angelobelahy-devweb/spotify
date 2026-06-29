<script setup lang="ts">
import { computed, onMounted, reactive } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/vue3'
import { ArrowLeft, Image, User } from 'lucide-vue-next'
import { Spinner } from '@/components/ui/spinner'
import { useToast } from 'primevue/usetoast'
import Toast from 'primevue/toast'

const page = usePage()
const successMessage = computed(() => String(page.props.flash?.success || ''))

const toast = useToast()
const form = useForm({
    surname: '',
    description: '',
    image: null as File | null,
})

const preview = reactive({
    image: '',
})

const handleImage = (e: Event) => {
    const target = e.target as HTMLInputElement

    if (target.files && target.files[0]) {
        form.image = target.files[0]
        preview.image = URL.createObjectURL(target.files[0])
    }
}

const submit = () => {
    form.post('/artistes/store', {
        forceFormData: true,
        onSuccess: () => {
            const successMessage = usePage().props.flash?.success

            toast.add({
                severity: 'success',
                summary: 'Succès',
                detail: successMessage || 'L\'artiste a été créé avec succès !',
                life: 3000,
            })

            form.reset()
            preview.image = ''
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Erreur',
                detail: 'Veuillez corriger les erreurs du formulaire.',
                life: 3000,
            })
        },
    })
}

onMounted(() => {
    const successMessage = usePage().props.flash?.success

    if (successMessage) {
        toast.add({
            severity: 'success',
            summary: 'Succès',
            detail: successMessage,
            life: 4000,
        })
    }
})
</script>

<template>
    <div class="text-white flex justify-center px-4 py-10">
        <Toast />

        <div class="w-full max-w-150 space-y-5">
            <div v-if="successMessage" class="rounded-3xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-emerald-100 shadow-inner shadow-emerald-500/10">
                <p class="text-sm font-semibold">Succès du paiement</p>
                <p class="mt-2 text-sm leading-6 text-gray-200">{{ successMessage }}</p>
            </div>

            <form
                @submit.prevent="submit"
                class="rounded-4xl border border-[#33437e] bg-black/50 p-8 shadow-[0_30px_90px_rgba(0,0,0,0.35)] backdrop-blur-xl"
            >
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-[#8fa4ff]/70">Création artiste</p>
                    <h1 class="text-2xl font-bold text-white">Devenir un artiste</h1>
                    <p class="mt-2 text-sm text-gray-400">Complétez votre profil artiste pour partager votre musique et augmenter votre visibilité.</p>
                </div>

                <Link href="/artistes" class="inline-flex items-center gap-2 rounded-full border border-[#4e5dd1]/40 bg-[#111826] px-4 py-2 text-sm text-gray-300 transition hover:border-[#7c8cf5] hover:text-white">
                    <ArrowLeft class="w-4 h-4" /> Retour
                </Link>
            </div>

            <div class="grid gap-6">
                <div class="flex flex-col items-center gap-4 rounded-3xl border border-dashed border-[#2e3d78] bg-[#10152f]/70 p-5 text-center">
                    <label class="relative flex h-40 w-40 cursor-pointer items-center justify-center overflow-hidden rounded-3xl border border-[#33437e] bg-[#141c34] text-[#92a2ff] transition hover:border-[#556cff]">
                        <img v-if="preview.image" :src="preview.image" class="h-full w-full object-cover" />
                        <div v-else class="flex flex-col items-center justify-center gap-2 px-4 text-sm text-gray-400">
                            <Image class="h-10 w-10" />
                            Cliquer pour ajouter une photo
                        </div>
                        <input type="file" class="hidden" accept="image/*" @change="handleImage" />
                    </label>
                    <p class="text-xs text-gray-500">PNG, JPG ou GIF — 2 Mo max.</p>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="text-sm text-gray-400">Nom de l’artiste</label>
                        <div class="relative mt-2">
                            <User class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500" />
                            <input
                                v-model="form.surname"
                                type="text"
                                placeholder="Ex : Rim Ka"
                                class="w-full rounded-2xl border border-[#2e2e2e] bg-[#0d1221] py-3 pl-12 pr-4 text-white outline-none transition focus:border-[#556cff]"
                            />
                        </div>
                        <p v-if="form.errors.surname" class="mt-2 text-sm text-red-500">{{ form.errors.surname }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-400">Description</label>
                        <textarea
                            v-model="form.description"
                            rows="4"
                            placeholder="Décrivez votre univers artistique..."
                            class="mt-2 w-full rounded-2xl border border-[#2e2e2e] bg-[#0d1221] p-3 text-white outline-none transition focus:border-[#556cff] resize-none"
                        ></textarea>
                        <p v-if="form.errors.description" class="mt-2 text-sm text-red-500">{{ form.errors.description }}</p>
                    </div>
                </div>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="mt-8 inline-flex w-full items-center justify-center gap-3 rounded-full bg-[#33437e] px-6 py-3 text-sm font-bold uppercase text-white shadow-lg shadow-[#33437e]/20 transition hover:bg-[#3e5ecf] active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-50"
            >
                <Spinner v-if="form.processing" class="h-4 w-4 animate-spin text-white" />
                <span>{{ form.processing ? 'Création en cours...' : 'Créer artiste' }}</span>
            </button>
        </form>
    </div>
</div>
</template>
