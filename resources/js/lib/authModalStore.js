import { reactive } from 'vue';

export const authModalStore = reactive({
    open: false,
    message: 'Veuillez vous connecter pour continuer.',
});

export function openAuthModal(message = 'Veuillez vous connecter pour continuer.') {
    authModalStore.message = message;
    authModalStore.open = true;
}

export function closeAuthModal() {
    authModalStore.open = false;
}
