<script setup lang="ts">
import { reactive } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/vue3'
import { ArrowLeft, Image, User } from 'lucide-vue-next'
import { useToast } from 'primevue/usetoast'
import Toast from 'primevue/toast';

// Map correct properties using 'surname' instead of 'name'
const form = useForm({
    name: "",
});

const toast = useToast();

const submit = () => {
    // Send as a POST request, Inertia + Laravel treats it as a PUT due to the _method attribute
    form.post('/admin/genres', {
        onSuccess: () => {
            const successMessage = usePage().props.flash?.success;
            toast.add({
                severity: 'success',
                summary: 'Succès',
                detail: successMessage || 'Le genre a été créé avec succès !',
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
                <Link href="/admin/genres" class="text-gray-400 hover:text-white flex items-center gap-1 text-sm">
                    <ArrowLeft class="w-4 h-4" /> Retour
                </Link>
            </div>
            <!-- NOM -->
            <div>
                <label class="text-sm text-gray-400">
                    Genre musical
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
                        v-model="form.name"
                        type="text"
                        placeholder="Ex : Pop, Rock, Jazz..."
                        class="  w-full  border  border-[#2e2e2e]  rounded-md  pl-12  pr-2  py-2  outline-none  focus:border-[#33437e] transition-all
                        "
                    />
                </div>

                <p
                    v-if="form.errors.name"
                    class="text-red-500 text-sm mt-2"
                >
                    {{ form.errors.name }}
                </p>
            </div>
            <!-- BUTTON -->
            <button
                type="submit"
                :disabled="form.processing"
                class="bg-[#33437e] hover:bg-[#364a92] active:scale-95 transition-all duration-300 mt-4 px-6 py-2 rounded-full text-sm font-bold flex items-center justify-center gap-2 w-max cursor-pointer disabled:opacity-50
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
