<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { email } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Mot de passe oublié',
        description: 'Saisissez votre adresse e-mail pour recevoir un lien de réinitialisation du mot de passe',
    },
});

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head title="Forgot password" />

    <div
        v-if="status"
        class="mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <div class="space-y-6">
        <Form v-bind="email.form()" v-slot="{ errors, processing }">
            <div class="grid gap-2">
                <Label for="email">Adresse E-mail</Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    autocomplete="off"
                    autofocus
                    placeholder="email@example.com"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="my-6 flex items-center justify-start">
                <Button
                    class="w-full
                    bg-[#33437e]
                    hover:bg-[#364a92]
                    active:scale-95
                    transition-all
                    duration-300
                    px-6
                    py-2
                    text-sm
                    font-bold
                    flex
                    items-center
                    justify-center
                    gap-2
                    cursor-pointer
                    disabled:opacity-50 text-white"
                    :disabled="processing"
                    data-test="email-password-reset-link-button"
                >
                    <Spinner v-if="processing" />
                    Envoyer le lien de réinitialisation
                </Button>
            </div>
        </Form>

        <div class="space-x-1 text-center text-sm text-muted-foreground">
            <span>Ou, revenir à la page de</span>
            <TextLink :href="login()" class="text-white">connexion</TextLink>
        </div>
    </div>
</template>
