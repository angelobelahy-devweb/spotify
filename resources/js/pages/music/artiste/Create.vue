<script setup lang="ts">
import { reactive } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import { ArrowLeft, Image, User } from 'lucide-vue-next'

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

        preview.image = URL.createObjectURL(
            target.files[0]
        )
    }
}

const submit = () => {
    form.post('/artistes/store', {
        forceFormData: true,
    })
}
</script>

<template>
    <div class=" text-white flex justify-center">

        <!-- FORM -->
        <form
            @submit.prevent="submit"
            class="w-[500px] bg-[#181818]/80  p-8 rounded-lg  shadow-2xl  space-y-2 h-[max-content]"
        >
            <!-- IMAGE -->
            <div class="flex flex-col items-center gap-4">

                <label
                    class="
                        relative
                        w-30
                        h-30
                        rounded-full
                        overflow-hidden
                        bg-[#232323]
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
                        
                    />
                </label>

                <p class="text-gray-400 text-sm">
                    Ajouter une photo d’artiste
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
                {{
                    form.processing
                        ? 'Création...'
                        : 'Créer l’artiste'
                }}
            </button>
        </form>
    </div>
</template>