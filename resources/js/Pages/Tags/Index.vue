<template>
    <div class="flex space-x-6">
        <div class="w-full">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 space-y-4">
                <div
                    class="font-bold text-xl mb-2 flex justify-between items-center"
                >
                    Existing Tags
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
                                v-for="col in ['Name', 'Default']"
                                :key="col"
                                class="px-4 py-2 border border-solid border-gray-300"
                                :class="`w-${sizes[col]}`"
                            >
                                {{ col.charAt(0).toUpperCase() + col.slice(1) }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="tags.length > 0">
                            <tr v-for="tag in tags" :key="tag.id">
                                <td
                                    class="px-4 py-2 border border-solid border-gray-300"
                                >
                                    {{ tag.name }}
                                </td>
                                <td
                                    class="px-4 py-2 border border-solid border-gray-300"
                                >
                                    <div class="flex justify-center">
                                        <Checkbox
                                            :checked="tag.default"
                                            @update:checked="toggleTag(tag)"
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
                                    No tags created
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
                @submit.prevent="createTag"
            >
                <div
                    class="font-bold text-xl mb-2 flex justify-between items-center"
                >
                    Create New Tag
                    <FontAwesomeIcon
                        class="text-green-500"
                        icon="plus"
                        size="lg"
                    />
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
                        v-model="newTag.name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="text"
                        placeholder="Name"
                    />
                </div>
                <div class="flex items-center">
                    <label
                        class="flex items-center justify-between space-x-4 font-bold"
                    >
                        <Checkbox :checked.sync="newTag.default" />
                        <span
                            class="text-sm"
                            :class="
                                newTag.default
                                    ? 'text-green-500'
                                    : 'text-gray-500'
                            "
                            @click="newTag.default = !newTag.default"
                            >Default value</span
                        >
                    </label>
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
import Checkbox from '@/Shared/Checkbox';

export default {
    layout: Layout,
    components: { Checkbox },
    props: {
        tags: {
            required: true,
            type: Array,
        },
    },
    data() {
        return {
            sizes: {
                Name: '3/4',
                Default: '1/4',
            },
            newTag: {
                name: '',
                default: false,
            },
        };
    },
    methods: {
        /**
         * Toggle a given tag.
         *
         * @param {Object} tag
         */
        toggleTag(tag) {
            this.$inertia.put(route('tags.update', tag.id));
        },

        /**
         * Create a new tag.
         */
        createTag() {
            this.$inertia.post(route('tags.store'), this.newTag);
        },
    },
};
</script>
