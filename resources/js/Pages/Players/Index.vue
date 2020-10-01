<template>
    <div class="space-y-5">
        <div class="flex justify-end md:justify-between items-center gap-x-4">
            <div class="justify-start items-center gap-x-4 hidden md:flex">
                <ListBox
                    id="perPageSelect"
                    v-model="pagination.perPage"
                    class="w-40"
                    :options="pagination.perPageOptions"
                    :label-resolver="option => `${option} per page`"
                />
                <ColumnsPicker
                    id="columnPicker"
                    :columns="allColumns"
                    :hidden-columns.sync="hiddenColumns"
                >
                    <template #column="{ column }">
                        <div
                            class="w-full flex justify-between items-center gap-x-2"
                        >
                            <span>
                                {{
                                    column.name in headings
                                        ? headings[column.name]
                                        : column.name
                                }}
                            </span>
                            <svg
                                class="h-5 w-5"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <!-- swap out the svg depending on the tag -->
                                <template v-if="column.type === 'column'">
                                    <path
                                        d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"
                                    />
                                    <path
                                        d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"
                                    />
                                    <path
                                        d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"
                                    />
                                </template>
                                <path
                                    v-if="column.type === 'tag'"
                                    fill-rule="evenodd"
                                    d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z"
                                    clip-rule="evenodd"
                                />
                                <path
                                    v-if="column.type === 'formula'"
                                    fill-rule="evenodd"
                                    d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm-3 3a1 1 0 100 2h.01a1 1 0 100-2H10zm-4 1a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1zm1-4a1 1 0 100 2h.01a1 1 0 100-2H7zm2 1a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm4-4a1 1 0 100 2h.01a1 1 0 100-2H13zM9 9a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zM7 8a1 1 0 000 2h.01a1 1 0 000-2H7z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                    </template>
                </ColumnsPicker>
            </div>
            <div class="w-full sm:w-auto flex justify-end items-center gap-x-4">
                <Search v-model="query" class="flex-grow sm:flex-grow-0" />
                <span class="shadow-sm rounded-md">
                    <button
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out"
                        @click="refreshStandings"
                    >
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
                                v-if="
                                    !hiddenColumns.includes(
                                        column.type + '-' + column.name
                                    )
                                "
                                :key="`th-${column.type}-${column.name}`"
                                :class="
                                    sortable.includes(column.name)
                                        ? ' cursor-pointer'
                                        : ''
                                "
                                class="px-3 py-2 lg:px-4 lg:py-3 bg-gray-100 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                @click="
                                    sortable.includes(column.name) &&
                                        sort(column.name)
                                "
                            >
                                <div class="flex items-center justify-between">
                                    <span
                                        class="inline-flex justify-between items-center space-x-0.5"
                                    >
                                        <span>
                                            {{
                                                column.name in headings
                                                    ? headings[column.name]
                                                    : column.name
                                            }}
                                        </span>
                                        <svg
                                            v-if="column.type === 'formula'"
                                            v-tooltip="{
                                                content: findFormula(
                                                    column.name
                                                ).equation,
                                                classes: 'font-mono',
                                                boundariesElement: pageContentElement,
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
                                    v-if="
                                        !hiddenColumns.includes('column-' + col)
                                    "
                                    :key="`${player.id}-${col}-column`"
                                    class="px-2 py-1 md:p-2 lg:p-3 xl:p-4 lg:whitespace-no-wrap text-sm"
                                >
                                    {{ player[col] }}
                                </td>
                            </template>

                            <template v-for="tag in tags">
                                <td
                                    v-if="
                                        !hiddenColumns.includes(
                                            'tag-' + tag.name
                                        )
                                    "
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
                                    v-if="
                                        !hiddenColumns.includes(
                                            'formula-' + formula.name
                                        )
                                    "
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
            <div
                v-if="filteredData.length > 0"
                class="border-t border-gray-200 p-3"
            >
                <Pagination
                    :from="paginationData.from"
                    :to="paginationData.to"
                    :row-count="filteredData.length"
                    :current-page.sync="pagination.currentPage"
                    :max-pages="paginationData.pages"
                />
            </div>
        </div>
    </div>
</template>
<script>
import Layout from '@/Layouts/Layout';
import Checkbox from '@/Shared/Checkbox';
import ListBox from '@/Shared/ListBox';
import SortControl from '@/Shared/SortControl';
import ColumnsPicker from '@/Shared/ColumnsPicker';
import Search from '@/Shared/Search';
import Pagination from '@/Shared/Pagination';
import { sentenceCase, paginate } from '@/Services/helpers';

const collator = new Intl.Collator(undefined, {
    sensitivity: 'base',
    numeric: true,
});

export default {
    layout: (h, page) => h(Layout, { props: { title: 'Players' } }, [page]),
    components: {
        Checkbox,
        SortControl,
        ListBox,
        ColumnsPicker,
        Pagination,
        Search,
    },
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
            headings: {
                full_name: 'name',
                decklist_name: 'decklist',
                decklist_count: 'deckcount',
                match_wins: 'wins',
                match_draws: 'draws',
                match_losses: 'losses',
            },
            sizes: {
                full_name: 'w-44',
                decklist_count: 'w-36',
                match_wins: 'w-28',
                match_draws: 'w-28',
                match_losses: 'w-28',
                points: 'w-28',
            },
            sortable: [...this.columns, ...this.formulas.map(f => f.name)],
            sortBy: {
                col: 'rank',
                asc: true,
            },
            pagination: {
                perPage: 10,
                perPageOptions: [10, 25, 50, 100],
                currentPage: 1,
            },
            hiddenColumns: [],
            query: '',
            pageContentElement: null,
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
                    p.full_name.toLowerCase().includes(this.query.toLowerCase())
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
    mounted() {
        // we need the right DOM target to allow tooltips to properly fill available space
        this.pageContentElement = document.querySelector('#pageContent');
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
         * Refresh the standings from the API, then refresh table.
         */
        async refreshStandings() {
            try {
                const response = await axios.post(
                    route('api.refresh.standings')
                );
                this.$inertia.reload({
                    preserveScroll: true,
                    onSuccess: () =>
                        this.$root.$emit('success', response?.data?.success),
                });
            } catch (error) {
                const err = error?.response?.data ?? error.message;
                this.$root.$emit('error', Array.isArray(err) ? err : [err]);
            }
        },
    },
};
</script>
