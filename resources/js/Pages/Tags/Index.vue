<template>
    <div class="flex flex-wrap gap-6">
        <div class="flex-1">
            <div class="bg-white border shadow overflow-hidden rounded-lg">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3
                        class="text-lg leading-6 font-medium flex justify-between items-center text-gray-900"
                    >
                        Existing Tags
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
                                        class="w-1/6 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Default
                                    </th>
                                    <th
                                        class="w-1/6 px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                    ></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template v-if="tags.length > 0">
                                    <tr v-for="tag in tags" :key="tag.id">
                                        <td
                                            class="px-6 py-4 whitespace-no-wrap"
                                        >
                                            {{ tag.name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-no-wrap"
                                        >
                                            <Checkbox
                                                :checked="tag.default"
                                                @update:checked="toggleTag(tag)"
                                            />
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium"
                                        >
                                            <a
                                                href="#"
                                                class="text-red-600 hover:text-red-900"
                                                @click="removeTag(tag)"
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
                                            No tags created
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
                @submit.prevent="createTag"
            >
                <div class="px-6 py-5 border-b border-gray-200">
                    <div
                        class="text-lg leading-6 font-medium flex justify-between items-center text-gray-900"
                    >
                        Create New Tag
                        <FontAwesomeIcon
                            class="text-green-600"
                            icon="plus"
                            size="lg"
                        />
                    </div>
                </div>

                <div class="px-5 py-4 space-y-4">
                    <div class="space-y-1">
                        <label
                            class="block text-sm leading-5 font-medium text-gray-700"
                            for="name"
                        >
                            Name
                        </label>
                        <input
                            id="name"
                            v-model="newTag.name"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            type="text"
                        />
                    </div>
                    <div class="flex items-center">
                        <label
                            class="flex items-center justify-between space-x-4 font-bold cursor-pointer"
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
import Checkbox from '@/Shared/Checkbox';

export default {
    layout: (h, page) => h(Layout, { props: { title: 'Tags' } }, [page]),
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
                Name: '2/4',
                Default: '1/4',
                '': '1/4',
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
            this.$inertia.put(route('tags.update', tag.id), {
                only: ['tags'],
                preserveScroll: true,
            });
        },

        removeTag(tag) {
            this.$inertia.delete(route('tags.delete', tag.id), {
                only: ['tags'],
                preserveScroll: true,
            });
        },

        /**
         * Create a new tag.
         */
        async createTag() {
            await this.$inertia.post(route('tags.store'), this.newTag, {
                only: ['tags'],
                preserveScroll: true,
            });
            this.newTag.name = '';
        },
    },
};
</script>
