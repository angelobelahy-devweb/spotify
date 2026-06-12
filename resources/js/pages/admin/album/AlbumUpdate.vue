<script setup lang="ts">
import { reactive } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/vue3'
import { ArrowLeft, Image, User } from 'lucide-vue-next'
import { useToast } from 'primevue/usetoast'
import Toast from 'primevue/toast';

// Receive single 'artist' prop from controller
const props = defineProps([
    'artist'
]);

// Map correct properties using 'surname' instead of 'name'
const form = useForm({
    _method: 'PUT', // Method spoofing for file uploads
    surname: props.artist?.surname || "",
    description: props.artist?.description || "",
    image: null // Start as null since it's an optional file upload now
});

const toast = useToast();

// Setup initial preview if user already has an image
const preview = reactive({
    image: props.artist?.user?.pdp ? `/storage/${props.artist.user.pdp}` : '',
})

const handleImage = (e: Event) => {
    const target = e.target as HTMLInputElement

    if (target.files && target.files[0]) {
        form.image = target.files[0]
        preview.image = URL.createObjectURL(target.files[0])
    }
}

const submit = () => {
    // Send as a POST request, Inertia + Laravel treats it as a PUT due to the _method attribute
    form.post(`/admin/artists/${props.artist.id}`, {
        forceFormData: true,
        onSuccess: () => {
            const successMessage = usePage().props.flash?.success;
            toast.add({
                severity: 'success',
                summary: 'Succès',
                detail: successMessage || 'L\'artiste a été modifié avec succès !',
                life: 3000
            });
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Erreur',
                detail: 'Veuillez corriger les erreurs du formulaire.',
                life: 3000
            });
        }
    });
}
</script>

<template>
    <div class="text-white flex justify-center">


        <!-- FORM -->
        <form
            @submit.prevent="submit"
            class="border border-[#33437e] backdrop-blur-md bg-black/40 w-full   p-8 rounded-lg  shadow-2xl h-[max-content]"
        >
            <div class="w-full flex items-center justify-between mb-6">
                <Link href="/admin/artists" class="text-gray-400 hover:text-white flex items-center gap-1 text-sm">
                    <ArrowLeft class="w-4 h-4" /> Retour
                </Link>
            </div>
            <!-- IMAGE -->
            <div class="flex flex-col items-center gap-4">

                <label
                    class="
                        relative
                        w-30
                        h-30
                        rounded-xl
                        overflow-hidden
                        cursor-pointer
                        group
                        border-2
                        border-dashed
                        border-[#3b3b3b]
                        hover:border-[#556cff]
                        transition-all
                    "
                >

                    <img
                        v-if="preview.image"
                        :src="preview.image"
                        class="
                            w-full
                            h-full
                            object-cover
                        "
                    />

                    <div
                        v-else
                        class="
                            w-full
                            h-full
                            flex
                            items-center
                            justify-center
                        "
                    >
                        <Image
                            class="
                                w-12
                                h-12
                                text-gray-500
                            "
                        />
                    </div>

                    <input
                        type="file"
                        class="hidden"
                        accept="image/*"
                        @change="handleImage"
                        @input="form.image = $event.target.files[0]"

                    />
                    <p v-if="form.errors.image" class="text-red-500 text-sm mt-2 text-center">
                        {{ form.errors.image }}
                    </p>
                </label>

                <p class="text-gray-400 text-sm">
                    Ajouter une photo
                </p>
            </div>
            <!-- NOM -->
            <div>
                <label class="text-sm text-gray-400">
                    Nom de l’artiste
                </label>

                <div class="relative mt-2">
                    <User
                        class="
                            absolute
                            left-4
                            top-1/2
                            -translate-y-1/2
                            w-5
                            h-5
                            text-gray-500
                        "
                    />
                    <input
                        v-model="form.surname"
                        type="text"
                        placeholder="Ex : Rim Ka"
                        class="  w-full  border  border-[#2e2e2e]  rounded-md  pl-12  pr-2  py-2  outline-none  focus:border-[#33437e] transition-all
                        "
                    />
                </div>

                <p
                    v-if="form.errors.surname"
                    class="text-red-500 text-sm mt-2"
                >
                    {{ form.errors.surname }}
                </p>
            </div>
            <!-- DESCRIPTION -->
            <div>
                <label class="text-sm text-gray-400">
                    Description
                </label>

                <textarea
                    v-model="form.description"
                    rows="3"
                    placeholder="Description de l’artiste..."
                    class="  mt-2  w-full border  border-[#2e2e2e] rounded-md  p-2  outline-none  focus:border-[#33437e]  transition-all  resize-none
                    "
                ></textarea>

                <p
                    v-if="form.errors.description"
                    class="text-red-500 text-sm mt-2"
                >
                    {{ form.errors.description }}
                </p>
            </div>
            <!-- BUTTON -->
            <button
                type="submit"
                :disabled="form.processing"
                class="bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all duration-300 px-6 py-2 rounded-full text-sm font-bold flex items-center justify-center gap-2 w-max cursor-pointer disabled:opacity-50
                "
            >
                <span v-if="form.processing" class="flex gap-2">
                    <spinner /> Création
                </span>
                <span v-else>Ajouter</span>
            </button>
        </form>
    </div>
</template>
