<template>
    <div>
        <header
            class="relative border-b border-gray-200 bg-blue-600 flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8"
        >
            <div class="text-blue-100">Hooglandia Feature Picker</div>
            <div class="flex justify-end space-x-4">
                <inertia-link
                    :href="route('players.index')"
                    :class="
                        isActive('players') ? 'text-white' : 'text-blue-200'
                    "
                >
                    Players
                </inertia-link>
                <inertia-link
                    :href="route('tags.index')"
                    :class="isActive('tags') ? 'text-white' : 'text-blue-200'"
                >
                    Tags
                </inertia-link>
            </div>
        </header>
        <main>
            <div class="flex-1 px-4 py-8 md:p-12 overflow-y-auto" scroll-region>
                <slot />
            </div>
        </main>
    </div>
</template>

<script>
export default {
    methods: {
        isActive(url) {
            return location.pathname.substr(1).startsWith(url);
        },
    },
};
</script>
