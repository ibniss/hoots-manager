<template>
    <div>
        <div class="bg-indigo-800">
            <div class="p-4 sm:px-6 lg:px-8">
                <nav
                    class="relative border-b border-indigo-700 flex items-center justify-between pb-4"
                >
                    <h2 class="text-indigo-100 font-medium text-lg">
                        HOOTS Manager
                    </h2>
                    <div class="flex justify-end space-x-4">
                        <template v-for="link in menu">
                            <inertia-link
                                :key="link.name"
                                :href="link.url"
                                class="px-3 py-2 rounded-md text-sm font-medium hover:text-white hover:bg-indigo-700 focus:outline-none transition duration-150 ease-in-out"
                                :class="
                                    isActive(link.activePart)
                                        ? 'text-indigo-100 bg-indigo-900'
                                        : 'text-indigo-300'
                                "
                            >
                                {{ link.name }}
                            </inertia-link>
                        </template>
                    </div>
                </nav>
            </div>
            <header class="py-12 px-4 sm:px-6 lg:px-8 pb-44">
                <h1 class="text-3xl font-bold leading-tight text-indigo-100">
                    {{ title }}
                </h1>
            </header>
        </div>
        <main class="py-8 px-4 sm:px-6 lg:px-8 overflow-y-auto -mt-40">
            <div id="pageContent" class="rounded-lg shadow-lg">
                <div class="rounded-lg shadow-xs overflow-hidden">
                    <div class="bg-white p-8">
                        <FlashMessages class="mb-4" />
                        <slot />
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
<script>
import FlashMessages from '@/Shared/FlashMessages';

export default {
    components: { FlashMessages },
    props: {
        title: {
            required: true,
            type: String,
        },
    },
    data() {
        return {
            menu: [
                {
                    name: 'Players',
                    url: route('players.index'),
                    activePart: 'players',
                },
                {
                    name: 'Pairings',
                    url: route('pairings.index'),
                    activePart: 'pairings',
                },
                {
                    name: 'Tags',
                    url: route('tags.index'),
                    activePart: 'tags',
                },
                {
                    name: 'Formulas',
                    url: route('formulas.index'),
                    activePart: 'formulas',
                },
                {
                    name: 'Settings',
                    url: route('settings.index'),
                    activePart: 'settings',
                },
            ],
        };
    },
    methods: {
        /**
         * Whether a given url is currently active.
         *
         * @param {String} url
         * @returns {Boolean}
         */
        isActive(url) {
            return location.pathname.substr(1).startsWith(url);
        },
    },
};
</script>
