<template>
    <form class="divide-y divide-gray-200 tracking-wide" @submit.prevent="save">
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
                    @click="form.pairings_template = defaultPairingsTemplate"
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
    },
};
</script>
