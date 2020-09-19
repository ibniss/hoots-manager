require('./bootstrap');

import { InertiaApp } from '@inertiajs/inertia-vue';
import Vue from 'vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
    faBan,
    faCheck,
    faList,
    faPlus,
} from '@fortawesome/free-solid-svg-icons';

// icons
library.add(faCheck);
library.add(faBan);
library.add(faPlus);
library.add(faList);

Vue.use(InertiaApp);
Vue.component('FontAwesomeIcon', FontAwesomeIcon);

Vue.mixin({ methods: { route: window.route } });

const app = document.getElementById('app');

new Vue({
    render: h =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: name =>
                    import(`./Pages/${name}`).then(module => module.default),
            },
        }),
}).$mount(app);
