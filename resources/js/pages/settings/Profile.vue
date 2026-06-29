<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue'; // Added ref for tab switching
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import DeleteUser from '@/components/DeleteUser.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';
import PageLayout from '@/layouts/PageLayout.vue';
import Security from './Security.vue';
import Appearance from './Appearance.vue';

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
};

defineProps<Props>();

defineOptions({
    layout: PageLayout
});

const page = usePage();
const user = computed(() => page.props.auth.user);

// Keep track of which settings tab is currently selected
const activeTab = ref<'profile' | 'security' | 'appearance'>('profile');
</script>

<template>
    <Head title="Profile settings" />

    <h1 class="sr-only">Profile settings</h1>

    <div class="max-w-4xl mx-auto p-6 text-white pb-20 border border-[#33437e] backdrop-blur-sm bg-black/40 rounded-lg">
        
        <!-- MODERN MUSIC-APP SUB-TAB NAVIGATION -->
        <div class="flex gap-6 border-b border-white/10 mb-8 text-sm font-medium">
            <button 
                @click="activeTab = 'profile'"
                :class="activeTab === 'profile' ? 'text-white border-b-2 border-blue-500 pb-3' : 'text-neutral-400 hover:text-white pb-3 transition'"
            >
                Profil
            </button>
            <button 
                @click="activeTab = 'security'"
                :class="activeTab === 'security' ? 'text-white border-b-2 border-blue-500 pb-3' : 'text-neutral-400 hover:text-white pb-3 transition'"
            >
                Sécurité
            </button>
            <button 
                @click="activeTab = 'appearance'"
                :class="activeTab === 'appearance' ? 'text-white border-b-2 border-blue-500 pb-3' : 'text-neutral-400 hover:text-white pb-3 transition'"
            >
                Apparence
            </button>
        </div>

        <!-- TAB CONTENT HOUSING -->
        <div class="transition-all duration-200">
            
            <!-- SECTION 1: PROFILE INFO & DELETE ACCOUNT -->
            <div v-if="activeTab === 'profile'" class="space-y-8">
                <div class="flex flex-col space-y-6 p-6 border border-[#33437e] backdrop-blur-sm bg-black/40 rounded-lg">
                    <Heading
                        variant="small"
                        title="Profile information"
                        description="Update your name and email address"
                    />

                    <Form
                        v-bind="ProfileController.update.form()"
                        class="space-y-6"
                        v-slot="{ errors, processing }"
                    >
                        <div class="grid gap-2">
                            <Label for="name">Name</Label>
                            <Input
                                id="name"
                                class="mt-1 block w-full"
                                name="name"
                                :default-value="user.name"
                                required
                                autocomplete="name"
                                placeholder="Full name"
                            />
                            <InputError class="mt-2" :message="errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="email">Email address</Label>
                            <Input
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                name="email"
                                :default-value="user.email"
                                required
                                autocomplete="username"
                                placeholder="Email address"
                            />
                            <InputError class="mt-2" :message="errors.email" />
                        </div>

                        <div v-if="mustVerifyEmail && !user.email_verified_at">
                            <p class="-mt-4 text-sm text-muted-foreground">
                                Your email address is unverified.
                                <Link
                                    :href="send()"
                                    as="button"
                                    class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                                >
                                    Click here to resend the verification email.
                                </Link>
                            </p>

                            <div
                                v-if="status === 'verification-link-sent'"
                                class="mt-2 text-sm font-medium text-green-600"
                            >
                                A new verification link has been sent to your email address.
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <Button :disabled="processing" data-test="update-profile-button">Save</Button>
                        </div>
                    </Form>
                </div>

                <!-- Delete account fits logically right under profile information -->
                <DeleteUser />
            </div>

            <!-- SECTION 2: PASSWORD / SECURITY CARD -->
            <div v-if="activeTab === 'security'" class="flex flex-col space-y-6 p-6 border border-[#33437e] backdrop-blur-sm bg-black/40 rounded-lg">
                <Heading 
                    variant="small" 
                    title="Security" 
                    description="Ensure your account is using a long, random password to stay secure." 
                />
                <Security /> 
            </div>

            <!-- SECTION 3: APPEARANCE CARD -->
            <div v-if="activeTab === 'appearance'" class="flex flex-col space-y-6 p-6 border border-[#33437e] backdrop-blur-sm bg-black/40 rounded-lg">
                <Heading 
                    variant="small" 
                    title="Appearance" 
                    description="Customize how your application looks." 
                />
                <Appearance />
            </div>

        </div>
    </div>
</template>