import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, h } from 'vue'; // DÉCOMMENTÉ
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import PageLayout from './layouts/PageLayout.vue';

// 1. IMPORTS DE PRIMEVUE À BIEN AJOUTER / DÉCOMMENTER :
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice'; // Ajout indispensable
import Aura from '@primeuix/themes/aura';

import 'primeicons/primeicons.css';
import '../css/app.css'; // Décommentez si nécessaire pour vos styles globaux


const appName = import.meta.env.VITE_APP_NAME || 'My Life';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('admin/'):
                return AdminLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return PageLayout;
        }
    },
    // 2. CONFIGURATION DE L'INSTANCIATION VUE :
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(PrimeVue, {
                theme: {
                    preset: Aura // Utilise le thème Aura configuré par défaut
                }
            })
            .use(ToastService) // <--- ACTIVE LE SERVICE TOAST DE PRIMEVUE
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// Vos initialisations existantes
initializeTheme();

// Si vous utilisez le Toast de PrimeVue, vous pouvez commenter cette ligne 
// ou la garder si vous utilisez deux systèmes en parallèle.
// initializeFlashToast(); 
