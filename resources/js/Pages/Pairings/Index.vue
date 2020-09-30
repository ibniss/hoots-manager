<template>
    <div class="space-y-5">
        <div class="flex flex-wrap items-center gap-2">
            <template v-for="round in rounds">
                <inertia-link
                    :key="round.id"
                    class="inline-flex items-center px-4 py-2 border border-transparent hover:text-indigo-600 hover:bg-indigo-100 focus:outline-none focus:shadow-outline focus:border-indigo-300 active:bg-indigo-300 transition duration-150 ease-in-out rounded-lg font-medium cursor-pointer"
                    type="button"
                    :class="
                        currentRoundId === round.id
                            ? 'text-indigo-600 bg-indigo-100 border-indigo-300'
                            : 'text-indigo-700 bg-indigo-200'
                    "
                    :href="route('pairings.index', { round: round.id })"
                    :only="['pairings', 'currentRoundId']"
                    preserve-scroll
                    replace
                >
                    {{ round.name }}
                </inertia-link>
            </template>
        </div>

        <div class="flex justify-end md:justify-between items-center gap-x-4">
            <div class="justify-start items-center gap-x-4 hidden md:flex">
                <ListBox
                    id="perPageSelect"
                    v-model="pagination.perPage"
                    class="w-40"
                    :options="pagination.perPageOptions"
                    :label-resolver="option => `${option} per page`"
                />
            </div>
            <div class="w-full sm:w-auto flex justify-end items-center gap-x-4">
                <Search v-model="query" class="flex-grow sm:flex-grow-0" />
                <span class="shadow-sm rounded-md">
                    <button
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out"
                        @click="refreshPairings"
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
                        <template v-for="column in columns">
                            <th
                                :key="column"
                                class="px-3 py-2 lg:px-4 lg:py-3 bg-gray-100 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                @click="
                                    sortable.includes(column) && sort(column)
                                "
                            >
                                <div class="flex items-center justify-between">
                                    <span>
                                        {{
                                            column in headings
                                                ? headings[column]
                                                : column
                                        }}
                                    </span>
                                    <SortControl
                                        class="h-4 w-4 text-gray-400"
                                        :sortable="sortable.includes(column)"
                                        :sorted="sortBy.col === column"
                                        :ascending="sortBy.asc"
                                    />
                                </div>
                            </th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="filteredData.length > 0">
                        <tr
                            v-for="(pairing, i) in paginationData.forPage"
                            :key="pairing.id"
                            :class="i % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
                        >
                            <template v-for="col in columns">
                                <td
                                    :key="`${pairing.id}-${col}`"
                                    class="px-2 py-1 md:p-2 lg:p-3 xl:p-4 lg:whitespace-no-wrap text-sm"
                                >
                                    {{ objectGet(pairing, col) }}
                                </td>
                            </template>
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
import Search from '@/Shared/Search';
import SortControl from '@/Shared/SortControl';
import Pagination from '@/Shared/Pagination';
import ListBox from '@/Shared/ListBox';
import Layout from '@/Layouts/Layout';
import { paginate, objectGet } from '@/Services/helpers';

const collator = new Intl.Collator(undefined, {
    sensitivity: 'base',
    numeric: true,
});

export default {
    layout: (h, page) => h(Layout, { props: { title: 'Pairings' } }, [page]),
    components: { Search, ListBox, SortControl, Pagination },
    props: {
        pairings: {
            required: true,
            type: Array,
        },
        columns: {
            required: true,
            type: Array,
        },
        rounds: {
            required: true,
            type: Array,
        },
        currentRoundId: {
            type: Number,
            default: null,
        },
    },
    data() {
        return {
            headings: {
                'player1.player_name': 'Player 1',
                'player1.decklist_name': 'Decklist',
                'player2.player_name': 'Player 2',
                'player2.decklist_name': 'Decklist',
            },
            query: '',
            sortable: [
                'player1.player_name',
                'player1.decklist_name',
                'player2.player_name',
                'player2.decklist_name',
            ],
            sortBy: {
                col: 'player1.player_name',
                asc: true,
            },
            pagination: {
                perPage: 10,
                perPageOptions: [10, 25, 50, 100],
                currentPage: 1,
            },
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

        filteredData() {
            return this.pairings
                .filter(
                    p =>
                        p.player1.player_name
                            .toLowerCase()
                            .includes(this.query.toLowerCase()) ||
                        p.player2.player_name
                            .toLowerCase()
                            .includes(this.query.toLowerCase())
                )
                .sort((a, b) => {
                    const comparison = collator.compare(
                        objectGet(a, this.sortBy.col),
                        objectGet(b, this.sortBy.col)
                    );
                    return this.sortBy.asc ? comparison : -1 * comparison;
                });
        },
    },
    methods: {
        objectGet,
        /**
         * Manually refresh pairings for the current round from the API, then refresh table.
         */
        async refreshPairings() {
            try {
                const response = await axios.post(
                    route('api.refresh.pairings'),
                    { round_id: this.currentRoundId }
                );
                this.$inertia.reload({
                    preserveScroll: true,
                    only: ['pairings'],
                    onSuccess: () =>
                        this.$root.$emit('success', response?.data?.success),
                });
            } catch (error) {
                const err = error?.response?.data ?? error.message;
                this.$root.$emit('error', Array.isArray(err) ? err : [err]);
            }
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
    },
};
</script>
