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
                <div class="relative inline-block text-left">
                    <div>
                        <span class="rounded-md shadow-sm">
                            <v-popover>
                                <button
                                    type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-50 text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150"
                                    aria-haspopup="true"
                                    aria-expanded="true"
                                >
                                    {{ pagination.perPage }} per page
                                    <FontAwesomeIcon
                                        class="-mr-1 ml-2 h-5 w-5"
                                        icon="chevron-down"
                                    />
                                </button>
                                <template #popover>
                                    <div class="rounded-md bg-white shadow-xs">
                                        <div
                                            class="py-1"
                                            role="menu"
                                            aria-orientation="vertical"
                                            aria-labelledby="options-menu"
                                        >
                                            <template
                                                v-for="option in pagination.perPageOptions"
                                            >
                                                <span
                                                    :key="option"
                                                    v-close-popover
                                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900 cursor-pointer"
                                                    role="menuitem"
                                                    @click="
                                                        pagination.perPage = option
                                                    "
                                                >
                                                    {{ option }} per page
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </v-popover>
                        </span>
                    </div>
                </div>
                <span class="shadow-sm rounded-md">
                    <button
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out"
                        @click="$refs.playersCsv.click()"
                    >
                        <FontAwesomeIcon class="mr-2" icon="upload" />
                        Upload
                    </button>
                </span>
                <span class="shadow-sm rounded-md">
                    <button
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-50 hover:text-red-600 focus:outline-none focus:shadow-outline focus:border-red-300 active:bg-red-300 transition duration-150 ease-in-out"
                        @click="deletePlayers"
                    >
                        <FontAwesomeIcon class="mr-2" icon="trash-alt" />
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
                        <FontAwesomeIcon class="text-gray-400" icon="search" />
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
                        <FontAwesomeIcon class="h-5" icon="sync-alt" />
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
                                        <FontAwesomeIcon
                                            v-if="column.type === 'formula'"
                                            v-tooltip="{
                                                content: findFormula(
                                                    column.name
                                                ).equation,
                                                classes: 'font-mono',
                                            }"
                                            icon="info-circle"
                                        />
                                    </span>
                                    <template
                                        v-if="sortable.includes(column.name)"
                                    >
                                        <FontAwesomeIcon
                                            v-if="sortBy.col === column.name"
                                            class="float-right"
                                            :icon="
                                                sortBy.asc
                                                    ? 'sort-up'
                                                    : 'sort-down'
                                            "
                                        />
                                        <FontAwesomeIcon
                                            v-else
                                            class="float-right"
                                            icon="sort"
                                        />
                                    </template>
                                </div>
                            </th>
                        </template>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template v-if="filteredData.length > 0">
                        <tr
                            v-for="(player, i) in filteredData"
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
                    <tr>
                        <td
                            class="text-center px-2 py-1 md:p-2 lg:p-3 xl:p-4 text-sm"
                            :colspan="allColumns.length"
                        >
                            <div class="flex justify-between items-center">
                                <div>
                                    Showing
                                    <span class="font-semibold">1</span> to
                                    <span class="font-semibold">20</span> of
                                    <span class="font-semibold">20</span>
                                    results
                                </div>
                                <div
                                    class="flex justify-end items-center gap-x-4"
                                >
                                    <span class="shadow-sm rounded-md">
                                        <button
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-white bg-white text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                                        >
                                            Previous
                                        </button>
                                    </span>
                                    <span class="shadow-sm rounded-md">
                                        <button
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-white bg-white text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                                        >
                                            Next
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
import Layout from '@/Layouts/Layout';
import Checkbox from '@/Shared/Checkbox';
import { sentenceCase } from '@/Services/helpers';

const collator = new Intl.Collator(undefined, {
    sensitivity: 'base',
    numeric: true,
});

export default {
    layout: (h, page) => h(Layout, { props: { title: 'Players' } }, [page]),
    components: { Checkbox },
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
                perPage: 25,
                perPageOptions: [10, 25, 50, 100],
            },
            hiddenColumns: [],
            query: '',
        };
    },
    computed: {
        /**
         * The table data with applied sorting.
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
