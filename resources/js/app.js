// eslint-disable-next-line no-undef

require('./bootstrap');
import 'flowbite';

import {createApp, h, ref} from 'vue';
import {createInertiaApp} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';
import Swal from './Functions/useSweetAlert';
import {translations} from './Mixins/translations';
import { createPinia } from 'pinia';
const pinia = createPinia();

// eslint-disable-next-line no-undef
window.Vapor = require('laravel-vapor');

const appName = process.env.MIX_APP_NAME || 'Cocking Betting System';
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    // eslint-disable-next-line no-undef
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({el, app, props, plugin}) {
        pinia.use(createStorePlugin(props));
        return createApp({render: () => h(app, props)})
            .use(plugin)
            .use(pinia)
            .use(Swal)
            .mixin({methods: {
                /* eslint-disable-next-line no-undef */
                route,
                asset: window.Vapor.asset
            }})
            .mixin(translations)
            .mount(el);
    },
});

InertiaProgress.init({color: '#E9D991', showSpinner: true});
function createStorePlugin (props) {
    return ({ store }) =>{
        if (store.$id === 'globalStore')
        {
            store.errors = ref(props.initialPage.props.errors);
        }
    };
}
