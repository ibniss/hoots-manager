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
                        <inertia-link
                            :href="route('players.index')"
                            class="px-3 py-2 rounded-md text-sm font-medium hover:text-white hover:bg-indigo-700 focus:outline-none transition duration-150 ease-in-out"
                            :class="
                                isActive('players')
                                    ? 'text-indigo-100 bg-indigo-900'
                                    : 'text-indigo-300'
                            "
                        >
                            Players
                        </inertia-link>
                        <inertia-link
                            :href="route('tags.index')"
                            class="px-3 py-2 rounded-md text-sm font-medium hover:text-white hover:bg-indigo-700 focus:outline-none transition duration-150 ease-in-out"
                            :class="
                                isActive('tags')
                                    ? 'text-indigo-100 bg-indigo-900'
                                    : 'text-indigo-300'
                            "
                        >
                            Tags
                        </inertia-link>
                        <inertia-link
                            :href="route('formulas.index')"
                            class="px-3 py-2 rounded-md text-sm font-medium hover:text-white hover:bg-indigo-700 focus:outline-none transition duration-150 ease-in-out"
                            :class="
                                isActive('formulas')
                                    ? 'text-indigo-100 bg-indigo-900'
                                    : 'text-indigo-300'
                            "
                        >
                            Formulas
                        </inertia-link>
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
