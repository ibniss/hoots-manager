import { InertiaApp } from '@inertiajs/inertia-vue';
import Vue from 'vue';
import VTooltip from 'v-tooltip';

require('./bootstrap');

Vue.use(InertiaApp);
Vue.use(VTooltip);

Vue.mixin({ methods: { route: window.route } });

Vue.directive('click-outside', {
    bind(el, binding, vnode) {
        el.clickOutsideEvent = function (event) {
            // here I check that click was outside the el and his children
            if (!(el === event.target || el.contains(event.target))) {
                // and if it did, call method provided in attribute value
                vnode.context[binding.expression](event);
            }
        };
        document.body.addEventListener('click', el.clickOutsideEvent);
    },
    unbind(el) {
        document.body.removeEventListener('click', el.clickOutsideEvent);
    },
});

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
