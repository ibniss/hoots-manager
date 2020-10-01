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
                <div class="shadow-sm rounded-md">
                    <button
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out"
                        @click="showCopyPairingsModal = true"
                    >
                        Pairings message
                    </button>
                    <Modal
                        max-size="sm:max-w-2xl"
                        :show.sync="showCopyPairingsModal"
                    >
                        <template #body>
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10"
                                >
                                    <svg
                                        class="h-6 w-6 text-indigo-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"
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
                                        Copy pairings message
                                    </h3>

                                    <pre class="font-sans">{{
                                        pairingsMessage
                                    }}</pre>
                                </div>
                            </div>
                        </template>
                        <template #footer="{ hide }">
                            <span
                                class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto"
                            >
                                <button
                                    type="button"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm"
                                >
                                    Copy
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
                                    Close
                                </button>
                            </span>
                        </template>
                    </Modal>
                </div>
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
                        <template v-for="column in allColumns">
                            <th
                                :key="`th-${column.type}-${column.name}`"
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
                <tbody>
                    <template v-if="filteredData.length > 0">
                        <tr
                            v-for="(pairing, i) in paginationData.forPage"
                            :key="pairing.id"
                            :class="i % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
                        >
                            <template v-for="col in allColumns">
                                <td
                                    :key="`${pairing.id}-${col.name}`"
                                    class="px-2 py-1 md:p-2 lg:p-3 xl:p-4 lg:whitespace-no-wrap text-sm"
                                    :class="
                                        headings[col.name] === '' ? 'w-12' : ''
                                    "
                                >
                                    <template v-if="col.name === 'select'">
                                        <Checkbox
                                            :checked="
                                                selectedPairings.includes(
                                                    pairing.id
                                                )
                                            "
                                            size="7"
                                            @update:checked="
                                                togglePairingSelection(
                                                    pairing.id
                                                )
                                            "
                                        />
                                    </template>
                                    <template v-else-if="col.name === 'copy'">
                                        <button
                                            class="inline-flex items-center justify-center group focus:outline-none"
                                            @click="copyPairing(pairing)"
                                        >
                                            <svg
                                                class="h-7 w-7 text-gray-400 group-hover:text-gray-300 transition duration-150 ease-in-out"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"
                                                />
                                                <path
                                                    d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"
                                                />
                                            </svg>
                                        </button>
                                    </template>
                                    <template v-else>
                                        {{ objectGet(pairing, col.name) }}
                                    </template>
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
import Checkbox from '@/Shared/Checkbox';
import Modal from '@/Shared/Modal';
import Layout from '@/Layouts/Layout';
import { paginate, objectGet } from '@/Services/helpers';

const collator = new Intl.Collator(undefined, {
    sensitivity: 'base',
    numeric: true,
});

export default {
    layout: (h, page) => h(Layout, { props: { title: 'Pairings' } }, [page]),
    components: { Search, ListBox, SortControl, Pagination, Checkbox, Modal },
    props: {
        pairings: {
            required: true,
            type: Array,
        },
        pairingsMessageTemplate: {
            required: true,
            type: String,
        },
        columns: {
            required: true,
            type: Array,
        },
        formulas: {
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
            selectedPairings: [],
            showCopyPairingsModal: false,
            headings: {
                select: '',
                copy: '',
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
                ...this.formulas.map(f => f.name),
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
            pageContentElement: null,
        };
    },
    computed: {
        /**
         * Get the pairings message.
         *
         * @returns {String}
         */
        pairingsMessage() {
            const pairings = this.selectedPairings.map(id =>
                this.pairings.find(x => x.id === id)
            );

            let pairingsPart = '';
            let index = 1;

            for (const pairing of pairings) {
                const player1Discord = pairing.player1.discord_username?.split(
                    '#'
                )?.[0];
                const player2Discord = pairing.player2.discord_username?.split(
                    '#'
                )?.[0];

                // if couldn't find discord username
                if (!player1Discord || !player2Discord) {
                    const missingPlayer = !player1Discord
                        ? pairing.player1
                        : pairing.player2;
                    return `Discord username missing for player '${missingPlayer.player_name}'`;
                }

                pairingsPart += `${index}: @${player1Discord} @${player2Discord}\n`;
                index++;
            }

            return this.pairingsMessageTemplate
                .replace(
                    '[[round_num]]',
                    this.rounds.find(r => r.id === this.currentRoundId)?.name ??
                        'Round not found'
                )
                .replace('[[pairings]]', pairingsPart)
                .trim();
        },

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
         * Filtered table data.
         *
         * @returns {Array}
         */
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

        /**
         * Get all table columns as object of col: 'type'.
         *
         * @returns {Array<{ name: string, type: string }>}
         */
        allColumns() {
            return [
                ...this.columns.map(col => ({ name: col, type: 'column' })),
                ...this.formulas.map(formula => ({
                    name: formula.name,
                    type: 'formula',
                })),
            ];
        },
    },
    mounted() {
        // we need the right DOM target to allow tooltips to properly fill available space
        this.pageContentElement = document.querySelector('#pageContent');
    },
    methods: {
        objectGet,
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

        /**
         * Toggle the selection for a given pairing.
         *
         * @param {Number} pairingId
         */
        togglePairingSelection(pairingId) {
            const index = this.selectedPairings.findIndex(x => x === pairingId);

            if (index === -1) {
                this.selectedPairings.push(pairingId);
            } else {
                this.selectedPairings.splice(index, 1);
            }
        },

        /**
         * Copy a given pairing into clipboard.
         *
         * @param {Object} pairing
         */
        async copyPairing(pairing) {
            const player1Discord = pairing.player1.discord_username?.split(
                '#'
            )?.[0];
            const player2Discord = pairing.player2.discord_username?.split(
                '#'
            )?.[0];

            // if couldn't find discord username
            if (!player1Discord || !player2Discord) {
                const missingPlayer = !player1Discord
                    ? pairing.player1
                    : pairing.player2;
                this.$root.$emit('error', [
                    `Discord username missing for player '${missingPlayer.player_name}'`,
                ]);
                return;
            }

            const copyString = `@${player1Discord} @${player2Discord}`;
            try {
                await navigator.clipboard.writeText(copyString);
                this.$root.$emit(
                    'success',
                    `Pairing '${copyString}' successfully copied to clipboard`
                );
            } catch {
                this.$root.$emit('error', [
                    `Copying '${copyString}' failed due to permission issues`,
                ]);
            }
        },
    },
};
</script>
