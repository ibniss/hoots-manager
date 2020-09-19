<template>
    <div class="flex space-x-6">
        <div class="w-full">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 space-y-4">
                <div
                    class="font-bold text-xl mb-2 flex justify-between items-center"
                >
                    Existing Formulas
                    <FontAwesomeIcon
                        class="text-blue-500"
                        icon="list"
                        size="lg"
                    />
                </div>
                <table
                    class="w-full max-w-full table-fixed border-collapse border border-solid border-gray-300"
                >
                    <thead>
                        <tr>
                            <th
                                v-for="col in ['Name', 'Equation', '']"
                                :key="col"
                                class="px-4 py-2 border border-solid border-gray-300"
                                :class="`w-${sizes[col]}`"
                            >
                                {{ col.charAt(0).toUpperCase() + col.slice(1) }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="formulas.length > 0">
                            <tr v-for="formula in formulas" :key="formula.id">
                                <td
                                    class="px-4 py-2 border border-solid border-gray-300"
                                >
                                    {{ formula.name }}
                                </td>
                                <td
                                    class="px-4 py-2 border border-solid border-gray-300"
                                >
                                    <pre>{{ formula.equation }}</pre>
                                </td>
                                <td
                                    class="px-4 py-2 border border-solid border-gray-300"
                                >
                                    <div
                                        class="flex justify-center text-red-700"
                                    >
                                        <FontAwesomeIcon
                                            icon="times"
                                            @click="deleteFormula(formula)"
                                        />
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td
                                    class="text-center px-4 py-2 border border-solid border-gray-300"
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

        <div class="w-full max-w-xs">
            <form
                class="bg-white shadow-md rounded px-8 pt-6 pb-8 space-y-4"
                @submit.prevent="createFormula"
            >
                <div
                    class="font-bold text-xl mb-2 flex justify-between items-center"
                >
                    Create New Formula
                    <FontAwesomeIcon
                        class="text-green-500"
                        icon="plus"
                        size="lg"
                    />
                </div>

                <div
                    class="rounded-md border border-gray-300 bg-blue-200 p-6 space-y-4"
                >
                    <b>Available variables:</b>

                    <ul class="list-disc">
                        <template v-for="variable in variables">
                            <li :key="variable">
                                {{ variable }}
                            </li>
                        </template>
                    </ul>
                </div>

                <div>
                    <label
                        class="block text-gray-700 text-sm font-bold mb-2"
                        for="name"
                    >
                        Name
                    </label>
                    <input
                        id="name"
                        v-model="newFormula.name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="text"
                        placeholder="Name"
                    />
                </div>
                <div>
                    <label
                        class="block text-gray-700 text-sm font-bold mb-2"
                        for="equation"
                    >
                        Equation
                    </label>
                    <textarea
                        id="equation"
                        v-model="newFormula.equation"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Equation"
                    />
                </div>
                <div class="flex items-center justify-end">
                    <button
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit"
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

export default {
    layout: Layout,
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
        /**
         * Create a new tag.
         */
        async createFormula() {
            await this.$inertia.post(route('formulas.store'), this.newFormula);
            this.newFormula.equation = '';
            this.newFormula.equation = '';
        },

        async deleteFormula(formula) {
            await this.$inertia.delete(route('formulas.delete', formula.id));
        },
    },
};
</script>
