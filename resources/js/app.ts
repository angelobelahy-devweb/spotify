// import '../css/app.css';
// import { createApp, h } from 'vue';

// import PrimeVue from 'primevue/config';
// import Aura from '@primeuix/themes/aura';

// import 'primeicons/primeicons.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';
import PageLayout from './layouts/PageLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return PageLayout;
        }
    },
    // setup({ el, App, props, plugin }) {
    //     createApp({
    //         render: () => h(App, props),
    //     })
    //         .use(plugin)

    //         .use(PrimeVue, {
    //             theme: {
    //                 preset: Aura,
    //             },
    //         })

    //         .mount(el);
    // },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();
