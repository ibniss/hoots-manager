<template>
    <div class="space-y-8">
        <form
            class="divide-y divide-gray-200 tracking-wide"
            @submit.prevent="save"
        >
            <div class="py-4">
                <h3 class="font-semibold text-lg">Tournament</h3>
            </div>
            <div class="grid grid-cols-8 gap-x-4 py-4">
                <label
                    for="tournament_id"
                    class="col-span-3 py-2 text-sm font-medium text-gray-600"
                >
                    Tournament ID
                </label>

                <div class="col-span-3 space-y-2">
                    <input
                        id="tournament_id"
                        v-model="form.tournament_id"
                        name="tournament_id"
                        class="form-input w-full shadow rounded-md text-sm"
                        type="text"
                    />
                    <p class="text-sm text-gray-500">
                        Used to call MTGMelee API so make sure it is correct
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-8 gap-x-4 py-4">
                <label
                    for="pairings_template"
                    class="col-span-3 py-2 text-sm font-medium text-gray-600"
                >
                    Pairings message template
                </label>

                <div class="col-span-3 space-y-2">
                    <textarea
                        id="pairings_template"
                        v-model="form.pairings_template"
                        name="pairings_template"
                        class="form-textarea w-full shadow rounded-md text-sm"
                        type="text"
                        rows="5"
                    />
                    <p class="text-sm text-gray-500">
                        Available variables: [[round_num]] [[pairings]]
                    </p>
                </div>

                <div class="col-start-8 flex items-start justify-end">
                    <button
                        class="px-4 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-900 focus:outline-none"
                        type="button"
                        @click="
                            form.pairings_template = defaultPairingsTemplate
                        "
                    >
                        Reset
                    </button>
                </div>
            </div>
            <div class="py-4 flex justify-end items-center">
                <span class="shadow-sm rounded-md">
                    <button
                        type="submit"
                        class="inline-flex items-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 font-medium text-sm text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline transition ease-in-out duration-150"
                    >
                        Save
                    </button>
                </span>
            </div>
        </form>
        <div class="divide-y divide-gray-200 tracking-wide">
            <div class="py-4">
                <h3 class="font-semibold text-lg">Controls</h3>
            </div>
            <div class="flex justify-between items-center space-x-4">
                <div class="space-y-2 py-2 text-sm font-medium text-gray-600">
                    <h4>Refresh player data</h4>
                    <p class="text-xs font-normal text-gray-500">
                        Warning: this will wipe players and associated tags and
                        attempt to re-fetch players from MTGMelee
                    </p>
                </div>
                <span class="shadow-sm rounded-md">
                    <button
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-50 hover:text-red-600 focus:outline-none focus:shadow-outline focus:border-red-300 active:bg-red-300 transition duration-150 ease-in-out"
                        @click="resetPlayers"
                    >
                        <svg
                            class="h-5 w-5 mr-2"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Refresh
                    </button>
                </span>
            </div>
        </div>
    </div>
</template>
<script>
import Layout from '@/Layouts/Layout';

export default {
    layout: (h, page) => h(Layout, { props: { title: 'Settings' } }, [page]),
    props: {
        defaultPairingsTemplate: {
            required: true,
            type: String,
        },
        settings: {
            required: true,
            type: Object,
        },
    },
    data() {
        return {
            form: {
                tournament_id: this.settings.tournament_id,
                pairings_template: this.settings.pairings_template,
            },
        };
    },
    methods: {
        /**
         * Save the settings.
         */
        save() {
            this.$inertia.put(route('settings.update'), this.form);
        },

        /**
         * Wipe and re-fetch players data from API.
         */
        async resetPlayers() {
            try {
                const response = await axios.post(route('api.refresh.players'));
                this.$root.$emit('success', response?.data?.success);
            } catch (error) {
                const err = error?.response?.data ?? error.message;
                this.$root.$emit('error', Array.isArray(err) ? err : [err]);
            }
        },
    },
};
</script>
