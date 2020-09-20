<template>
    <div class="flex flex-wrap gap-6">
        <div class="flex-1">
            <div class="bg-white border shadow overflow-hidden rounded-lg">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3
                        class="text-lg leading-6 font-medium flex justify-between items-center text-gray-900"
                    >
                        Existing Formulas
                        <FontAwesomeIcon
                            class="text-indigo-600"
                            icon="list"
                            size="lg"
                        />
                    </h3>
                </div>

                <div class="px-6 py-5">
                    <div
                        class="shadow overflow-hidden border border-gray-200 rounded-lg"
                    >
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Name
                                    </th>
                                    <th
                                        class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Equation
                                    </th>
                                    <th
                                        class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                    ></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template v-if="formulas.length > 0">
                                    <tr
                                        v-for="formula in formulas"
                                        :key="formula.id"
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-no-wrap"
                                        >
                                            {{ formula.name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-no-wrap"
                                        >
                                            <pre>{{ formula.equation }}</pre>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium"
                                        >
                                            <a
                                                href="#"
                                                class="text-red-600 hover:text-red-900"
                                                @click="deleteFormula(formula)"
                                                >Remove</a
                                            >
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td
                                            class="text-center px-6 py-4 whitespace-no-wrap"
                                            colspan="2"
                                        >
                                            No formulas created
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-initial w-80">
            <form
                class="bg-white border shadow overflow-hidden rounded-lg"
                @submit.prevent="createFormula"
            >
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3
                        class="text-lg leading-6 font-medium flex justify-between items-center text-gray-900"
                    >
                        Create New Formula
                        <FontAwesomeIcon
                            class="text-green-600"
                            icon="plus"
                            size="lg"
                        />
                    </h3>
                </div>

                <div class="px-5 py-4 space-y-4">
                    <div
                        class="rounded-md bg-indigo-50 text-indigo-700 p-6 space-y-2"
                    >
                        <h4 class="font-semibold">Available variables</h4>

                        <div class="flex flex-wrap -m-1">
                            <template v-for="variable in variables">
                                <span
                                    :key="variable"
                                    class="inline-flex items-center px-2.5 py-0.5 m-1 bg-indigo-200 rounded-full font-mono font-medium text-xs leading-4"
                                >
                                    {{ variable }}
                                </span>
                            </template>
                        </div>

                        <p class="text-indigo-500 italic text-sm">
                            You can only use up to 5 in one equation
                        </p>
                    </div>

                    <div class="space-y-1">
                        <label
                            class="block text-sm leading-5 font-medium text-gray-700"
                            for="name"
                        >
                            Name
                        </label>
                        <input
                            id="name"
                            v-model="newFormula.name"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            type="text"
                        />
                    </div>
                    <div class="space-y-1">
                        <label
                            class="block text-sm leading-5 font-medium text-gray-700"
                            for="equation"
                        >
                            Equation
                        </label>
                        <textarea
                            id="equation"
                            v-model="newFormula.equation"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        />
                    </div>
                    <button
                        type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-600 hover:bg-green-500 active:bg-green-700 transition duration-150 ease-in-out"
                    >
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import Layout from '@/Layouts/Layout';
import { sentenceCase } from '@/Services/helpers';

export default {
    layout: (h, page) => h(Layout, { props: { title: 'Formulas' } }, [page]),
    props: {
        formulas: {
            required: true,
            type: Array,
        },
        variables: {
            required: true,
            type: Array,
        },
    },
    data() {
        return {
            sizes: {
                Name: '1/4',
                Equation: '1/2',
                '': '1/4',
            },
            newFormula: {
                name: '',
                equation: '',
            },
        };
    },
    methods: {
        sentenceCase,

        /**
         * Create a new formula.
         */
        async createFormula() {
            await this.$inertia.post(route('formulas.store'), this.newFormula);
            this.newFormula.equation = '';
            this.newFormula.equation = '';
        },

        /**
         * Delete a given formula.
         *
         * @param {Object} formula
         */
        async deleteFormula(formula) {
            await this.$inertia.delete(route('formulas.delete', formula.id));
        },
    },
};
</script>
