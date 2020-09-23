<template>
    <div class="space-y-5">
        <div class="flex justify-end md:justify-between items-center">
            <div class="justify-start items-center gap-x-4 hidden md:flex">
                <input
                    ref="playersCsv"
                    type="file"
                    class="hidden"
                    @change="uploadPlayers"
                />
                <ListBox
                    id="perPageSelect"
                    v-model="pagination.perPage"
                    class="w-40"
                    :options="pagination.perPageOptions"
                    :label-resolver="option => `${option} per page`"
                />
                <span class="shadow-sm rounded-md">
                    <button
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out"
                        @click="$refs.playersCsv.click()"
                    >
                        <svg
                            class="mr-2 h-5 w-5"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Upload
                    </button>
                </span>
                <span class="shadow-sm rounded-md">
                    <button
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-50 hover:text-red-600 focus:outline-none focus:shadow-outline focus:border-red-300 active:bg-red-300 transition duration-150 ease-in-out"
                        @click="deletePlayers"
                    >
                        <svg
                            class="mr-2 h-5 w-5"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Delete players
                    </button>
                </span>
            </div>
            <div class="w-full sm:w-auto flex justify-end items-center gap-x-4">
                <div
                    class="flex-grow sm:flex-grow-0 relative rounded-md shadow-sm"
                >
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                    >
                        <svg
                            class="text-gray-400 h-5 w-5"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <input
                        id="price"
                        v-model="query"
                        class="form-input block w-full pl-10 pr-12 text-sm leading-5"
                        placeholder="Search"
                        type="text"
                    />
                </div>
                <span class="shadow-sm rounded-md">
                    <button
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out"
                    >
                        <!-- TODO: on click refresh standings from API -->
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </span>
            </div>
        </div>
        <div
            class="shadow border border-gray-200 rounded-lg overflow-x-auto lg:whitespace-no-wrap"
        >
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <template v-for="column in allColumns">
                            <th
                                v-if="!hiddenColumns.includes(column.name)"
                                :key="`th-${column.type}-${column.name}`"
                                :class="
                                    sortable.includes(column.name)
                                        ? ' cursor-pointer'
                                        : ''
                                "
                                class="px-3 py-2 lg:px-4 lg:py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                @click="
                                    sortable.includes(column.name) &&
                                        sort(column.name)
                                "
                            >
                                <div class="flex items-center justify-between">
                                    <span
                                        class="inline-flex justify-between items-center space-x-0.5"
                                    >
                                        <span>{{ column.name }}</span>
                                        <svg
                                            v-if="column.type === 'formula'"
                                            v-tooltip="{
                                                content: findFormula(
                                                    column.name
                                                ).equation,
                                                classes: 'font-mono',
                                            }"
                                            class="h-4 w-4"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </span>
                                    <SortControl
                                        class="h-4 w-4 text-gray-400"
                                        :sortable="
                                            sortable.includes(column.name)
                                        "
                                        :sorted="sortBy.col === column.name"
                                        :ascending="sortBy.asc"
                                    />
                                </div>
                            </th>
                        </template>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template v-if="filteredData.length > 0">
                        <tr
                            v-for="(player, i) in paginationData.forPage"
                            :key="player.id"
                            :class="i % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
                        >
                            <template v-for="col in columns">
                                <td
                                    v-if="!hiddenColumns.includes(col)"
                                    :key="`${player.id}-${col}-column`"
                                    class="px-2 py-1 md:p-2 lg:p-3 xl:p-4 lg:whitespace-no-wrap text-sm"
                                >
                                    {{ player[col] }}
                                </td>
                            </template>

                            <template v-for="tag in tags">
                                <td
                                    v-if="!hiddenColumns.includes(tag.name)"
                                    :key="`${player.id}-${tag.name}-tag`"
                                    class="px-2 py-1 md:p-2 lg:p-3 xl:p-4 lg:whitespace-no-wrap text-sm"
                                >
                                    <Checkbox
                                        :checked="player.tag_values[tag.name]"
                                        size="7"
                                        @update:checked="
                                            toggleTag(player, tag.name)
                                        "
                                    />
                                </td>
                            </template>

                            <template v-for="formula in formulas">
                                <td
                                    v-if="!hiddenColumns.includes(formula.name)"
                                    :key="`${player.id}-${formula.name}-formula`"
                                    class="px-2 py-1 md:p-2 lg:p-3 xl:p-4 lg:whitespace-no-wrap text-sm"
                                >
                                    {{ player[formula.name] }}
                                </td>
                            </template>
                        </tr>
                    </template>
                    <template v-else>
                        <tr>
                            <td
                                class="text-center px-2 py-1 md:p-2 lg:p-3 xl:p-4 text-sm"
                                :colspan="allColumns.length"
                            >
                                No players found
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <!-- TODO: put this outside table to fix responsiveness -->

            <div
                class="bg-white text-center p-3 text-sm flex justify-between items-center border-t border-gray-200 text-center"
            >
                <div>
                    Showing
                    <span class="font-semibold">
                        {{ paginationData.from }}
                    </span>
                    to
                    <span class="font-semibold">
                        {{ paginationData.to }}
                    </span>
                    of
                    <span class="font-semibold">
                        {{ filteredData.length }}
                    </span>
                    results
                </div>
                <div class="flex justify-end items-center gap-x-4">
                    <span class="shadow-sm rounded-md">
                        <button
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-white bg-white text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                            @click="
                                pagination.currentPage !== 1 &&
                                    pagination.currentPage--
                            "
                        >
                            Previous
                        </button>
                    </span>
                    <span class="shadow-sm rounded-md">
                        <button
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-white bg-white text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                            @click="
                                pagination.currentPage !==
                                    paginationData.pages &&
                                    pagination.currentPage++
                            "
                        >
                            Next
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Layout from '@/Layouts/Layout';
import Checkbox from '@/Shared/Checkbox';
import ListBox from '@/Shared/ListBox';
import SortControl from '@/Shared/SortControl';
import { sentenceCase, paginate } from '@/Services/helpers';

const collator = new Intl.Collator(undefined, {
    sensitivity: 'base',
    numeric: true,
});

export default {
    layout: (h, page) => h(Layout, { props: { title: 'Players' } }, [page]),
    components: { Checkbox, SortControl, ListBox },
    props: {
        columns: {
            required: true,
            type: Array,
        },
        tags: {
            required: true,
            type: Array,
        },
        formulas: {
            required: true,
            type: Array,
        },
        players: {
            required: true,
            type: Array,
        },
    },
    data() {
        return {
            sizes: {
                name: 'w-44',
                deckcount: 'w-36',
                wins: 'w-28',
                draws: 'w-28',
                losses: 'w-28',
                points: 'w-28',
            },
            sortable: [...this.columns, ...this.formulas.map(f => f.name)],
            sortBy: {
                col: 'points',
                asc: false,
            },
            pagination: {
                perPage: 10,
                perPageOptions: [10, 25, 50, 100],
                currentPage: 1,
            },
            hiddenColumns: [],
            query: '',
        };
    },
    computed: {
        /**
         * Get the pagination data.
         *
         * @returns {ReturnType<paginate>}
         */
        paginationData() {
            return paginate(
                this.filteredData,
                this.pagination.currentPage,
                this.pagination.perPage
            );
        },

        /**
         * The table data with applied filtering & sorting.
         *
         * @returns {Array}
         */
        filteredData() {
            return this.players
                .filter(p =>
                    p.name.toLowerCase().includes(this.query.toLowerCase())
                )
                .sort((a, b) => {
                    const comparison = collator.compare(
                        a[this.sortBy.col],
                        b[this.sortBy.col]
                    );
                    return this.sortBy.asc ? comparison : -1 * comparison;
                });
        },

        /**
         * Get all table columns as object of col: 'type'.
         *
         * @returns {Array<{ name: string, type: string }>}
         */
        allColumns() {
            return [
                ...this.columns.map(col => ({ name: col, type: 'column' })),
                ...this.tags.map(tag => ({ name: tag.name, type: 'tag' })),
                ...this.formulas.map(f => ({ name: f.name, type: 'formula' })),
            ];
        },
    },
    methods: {
        sentenceCase,

        /**
         * Find a formula by its name.
         *
         * @param {String} col
         * @returns {Object|undefined}
         */
        findFormula(col) {
            return this.formulas.find(f => f.name === col);
        },

        /**
         * Sort the data by given col.
         *
         * @param {String} col
         */
        sort(col) {
            if (this.sortBy.col === col) {
                this.sortBy.asc = !this.sortBy.asc;
            } else {
                this.sortBy.col = col;
                this.sortBy.asc = true;
            }
        },

        /**
         * Send a request with the CSV data uploaded.
         *
         * @param {Event} event
         */
        uploadPlayers(event) {
            const files = event.target.files || event.dataTransfer.files;

            if (!files.length) return;

            const data = new FormData();
            data.append('data', files[0]);
            this.$inertia.post(route('players.store'), data);
        },

        /**
         * Toggle a tag value for a player.
         *
         * @param {Object} player
         * @param {String} tagName
         */
        toggleTag(player, tagName) {
            this.$inertia.put(
                route('players.tags.update', [player, tagName]),
                null,
                {
                    preserveScroll: true,
                    only: ['players'],
                }
            );
        },

        /**
         * Wipe all player data.
         */
        deletePlayers() {
            this.$inertia.delete(route('players.delete'));
        },
    },
};
</script>
