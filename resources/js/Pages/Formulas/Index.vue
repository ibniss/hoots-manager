<template>
    <div class="space-y-5">
        <div
            class="flex flex-wrap justify-end lg:justify-between items-center gap-4"
        >
            <div
                class="w-full lg:w-auto shadow rounded-md bg-indigo-50 text-indigo-700 p-6"
            >
                <p class="font-medium text-sm">
                    Equations are evaluated in order - you can use equation
                    results as variables in succeeding equations
                </p>
            </div>
            <div class="shadow-sm rounded-md">
                <button
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out"
                    @click="showNewFormulaModal = true"
                >
                    Create
                </button>
                <form @submit.prevent="createFormula">
                    <Modal :show.sync="showNewFormulaModal">
                        <template #body>
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10"
                                >
                                    <svg
                                        class="h-6 w-6 text-green-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                        />
                                    </svg>
                                </div>
                                <div
                                    class="flex-grow mt-3 sm:mt-0 sm:ml-4 flex flex-col gap-y-4"
                                >
                                    <h3
                                        id="modal-headline"
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Create new formula
                                    </h3>

                                    <div
                                        class="rounded-md bg-indigo-50 text-indigo-700 p-6 space-y-2"
                                    >
                                        <h4 class="font-semibold">
                                            Available variables
                                        </h4>

                                        <div class="flex flex-wrap -m-1">
                                            <template
                                                v-for="variable in variables"
                                            >
                                                <span
                                                    :key="variable"
                                                    class="inline-flex items-center px-2.5 py-0.5 m-1 bg-indigo-200 rounded-full font-mono font-medium text-xs"
                                                >
                                                    {{ variable }}
                                                </span>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <input
                                            id="name"
                                            v-model="newFormula.name"
                                            class="form-input text-sm"
                                            type="text"
                                            placeholder="Name"
                                        />

                                        <textarea
                                            id="equation"
                                            v-model="newFormula.equation"
                                            class="form-textarea text-sm w-full"
                                            placeholder="Equation"
                                        />
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template #footer="{ hide }">
                            <span
                                class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto"
                            >
                                <button
                                    type="submit"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm"
                                    @click="hide"
                                >
                                    Save
                                </button>
                            </span>
                            <span
                                class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto"
                            >
                                <button
                                    type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm"
                                    @click="hide"
                                >
                                    Cancel
                                </button>
                            </span>
                        </template>
                    </Modal>
                </form>
            </div>
        </div>

        <div
            class="shadow overflow-x-auto border border-gray-200 rounded-lg lg:whitespace-no-wrap"
        >
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Name
                        </th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Equation
                        </th>
                        <th
                            class="w-1/12 px-6 py-3 bg-gray-50 text-left text-xs"
                        ></th>
                        <th
                            class="w-1/12 px-6 py-3 bg-gray-50 text-left text-xs"
                        ></th>
                        <th
                            class="w-1/12 px-6 py-3 bg-gray-50 text-left text-xs"
                        ></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template v-if="formulas.length > 0">
                        <tr v-for="formula in formulas" :key="formula.id">
                            <td class="px-6 py-4">
                                {{ formula.name }}
                            </td>
                            <td class="px-6 py-4">
                                <pre>{{ formula.equation }}</pre>
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <button
                                    class="font-medium text-indigo-600 hover:text-indigo-900 focus:outline-none"
                                    @click="moveFormula(formula, 'up')"
                                >
                                    Up
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <button
                                    class="font-medium text-indigo-600 hover:text-indigo-900 focus:outline-none"
                                    @click="moveFormula(formula, 'down')"
                                >
                                    Down
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <button
                                    class="font-medium text-red-600 hover:text-red-900 focus:outline-none"
                                    @click="deleteFormula(formula)"
                                >
                                    Remove
                                </button>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr>
                            <td class="text-center px-6 py-4" colspan="3">
                                No formulas created
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
import Layout from '@/Layouts/Layout';
import Modal from '@/Shared/Modal';
import { sentenceCase } from '@/Services/helpers';

export default {
    layout: (h, page) => h(Layout, { props: { title: 'Formulas' } }, [page]),
    components: { Modal },
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
            showNewFormulaModal: false,
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
        createFormula() {
            this.$inertia.post(route('formulas.store'), this.newFormula, {
                preserveScroll: true,
                onSuccess: () => {
                    this.newFormula.name = '';
                    this.newFormula.equation = '';
                },
            });
        },

        /**
         * Move a given formula in a given direction.
         *
         * @param {Object} formula
         * @param {String} direction
         */
        moveFormula(formula, direction) {
            this.$inertia.put(
                route('formulas.move', formula),
                { direction },
                {
                    preserveScroll: true,
                }
            );
        },

        /**
         * Delete a given formula.
         *
         * @param {Object} formula
         */
        deleteFormula(formula) {
            this.$inertia.delete(route('formulas.delete', formula.id), {
                preserveScroll: true,
            });
        },
    },
};
</script>
