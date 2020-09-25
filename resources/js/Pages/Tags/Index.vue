<template>
    <div class="space-y-5">
        <div class="flex justify-end items-center">
            <span class="shadow-sm rounded-md">
                <button
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out"
                    @click="showNewTagModal = true"
                >
                    Create
                </button>
                <form @submit.prevent="createTag">
                    <Modal :show.sync="showNewTagModal">
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
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
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
                                        Create new tag
                                    </h3>

                                    <div class="space-y-4">
                                        <input
                                            id="name"
                                            v-model="newTag.name"
                                            class="form-input text-sm"
                                            type="text"
                                            placeholder="Name"
                                        />

                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div class="flex items-center">
                                                <input
                                                    id="default"
                                                    v-model="newTag.default"
                                                    type="checkbox"
                                                    class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                                                />
                                                <label
                                                    for="default"
                                                    class="ml-2 block text-sm text-gray-900"
                                                >
                                                    Default value
                                                </label>
                                            </div>
                                        </div>
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
            </span>
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
                            class="w-1/12 px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Default
                        </th>
                        <th
                            class="w-1/12 px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        ></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template v-if="tags.length > 0">
                        <tr v-for="tag in tags" :key="tag.id">
                            <td class="px-6 py-4">
                                {{ tag.name }}
                            </td>
                            <td class="px-6 py-4">
                                <Checkbox
                                    :checked="tag.default"
                                    @update:checked="toggleTag(tag)"
                                />
                            </td>
                            <td
                                class="px-6 py-4 text-right text-sm font-medium"
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
                            <td class="text-center px-6 py-4" colspan="3">
                                No tags created
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
import Checkbox from '@/Shared/Checkbox';
import Modal from '@/Shared/Modal';

export default {
    layout: (h, page) => h(Layout, { props: { title: 'Tags' } }, [page]),
    components: { Checkbox, Modal },
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
            showNewTagModal: false,
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
        createTag() {
            this.$inertia.post(route('tags.store'), this.newTag, {
                only: ['tags'],
                preserveScroll: true,
                onSuccess: () => {
                    this.newTag.name = '';
                },
            });
        },
    },
};
</script>
