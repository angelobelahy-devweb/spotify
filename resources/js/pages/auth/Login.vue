<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Connexion à votre compte',
        description: 'Saisissez votre adresse e-mail et votre mot de passe pour vous connecter',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>

        <Head title="Log in" />

        <div
            v-if="status"
            class="mb-4 text-center text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6 text-white"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">Adresse e-mail</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Mot de passe</Label>
                        <TextLink
                            v-if="canResetPassword"
                            :href="request()"
                            class="text-sm text-white/60"
                            :tabindex="5"
                        >
                            Mot de passe oublié ?
                        </TextLink>
                    </div>
                    <PasswordInput
                        id="password"
                        name="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="Mot de passe"
                    />
                    <InputError :message="errors.password" />
                </div>

                <Button
                    type="submit"
                    class="mt-4 w-full
                    bg-[#33437e]
                    hover:bg-[#364a92]
                    active:scale-95
                    transition-all
                    duration-300
                    px-6
                    py-2
                    rounded-full
                    text-sm
                    font-bold
                    flex
                    items-center
                    justify-center
                    gap-2
                    cursor-pointer
                    disabled:opacity-50 text-white"
                    :tabindex="4"
                    :disabled="processing"
                    data-test="login-button"
                >
                    <Spinner v-if="processing" />
                    Se connecter
                </Button>
            </div>

            <div
                class="text-center text-sm text-muted-foreground"
                v-if="canRegister"
            >
                Vous n'avez pas de compte ?
                <TextLink :href="register()" :tabindex="5" class="text-white">Inscrivez-vous</TextLink>
            </div>
        </Form>
</template>
