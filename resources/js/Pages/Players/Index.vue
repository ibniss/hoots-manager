<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div class="mt-1 relative rounded-md shadow-sm">
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
            <div class="flex justify-end items-center space-x-4">
                <input
                    ref="playersCsv"
                    type="file"
                    class="hidden"
                    @change="uploadPlayers"
                />
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
        </div>
        <div class="shadow overflow-hidden border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th
                            v-for="col in columns"
                            :key="col"
                            :class="
                                (sizes[col] ? sizes[col] : '') +
                                (sortable.includes(col)
                                    ? ' cursor-pointer'
                                    : '')
                            "
                            class="px-5 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                            @click="sortable.includes(col) && sort(col)"
                        >
                            <div class="flex items-center justify-between">
                                <span>{{ col }}</span>
                                <template v-if="sortable.includes(col)">
                                    <FontAwesomeIcon
                                        v-if="sortBy.col === col"
                                        class="float-right"
                                        :icon="
                                            sortBy.asc ? 'sort-up' : 'sort-down'
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
                        <th
                            v-for="tag in tags"
                            :key="tag.name"
                            class="px-5 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ tag.name }}
                        </th>
                        <th
                            v-for="formula in formulas"
                            :key="formula.name"
                            class="px-5 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            @click="sort(formula.name)"
                        >
                            <div class="flex justify-between items-center">
                                <span
                                    class="inline-flex justify-between items-center space-x-0.5"
                                >
                                    <span>{{ formula.name }}</span>
                                    <FontAwesomeIcon
                                        v-tooltip="{
                                            content: formula.equation,
                                            classes: 'font-mono',
                                        }"
                                        icon="info-circle"
                                    />
                                </span>
                                <FontAwesomeIcon
                                    v-if="sortBy.col === formula.name"
                                    class="float-right"
                                    :icon="sortBy.asc ? 'sort-up' : 'sort-down'"
                                />
                                <FontAwesomeIcon
                                    v-else
                                    class="float-right"
                                    icon="sort"
                                />
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template v-if="filteredData.length > 0">
                        <tr v-for="player in filteredData" :key="player.id">
                            <td
                                v-for="col in columns"
                                :key="col"
                                class="px-5 py-4 whitespace-no-wrap"
                            >
                                {{ player[col] }}
                            </td>

                            <td
                                v-for="tag in tags"
                                :key="tag.name"
                                class="px-5 py-4 whitespace-no-wrap"
                            >
                                <Checkbox
                                    :checked="player.tag_values[tag.name]"
                                    @update:checked="
                                        toggleTag(player, tag.name)
                                    "
                                />
                            </td>

                            <td
                                v-for="formula in formulas"
                                :key="formula.name"
                                class="px-5 py-4 whitespace-no-wrap"
                            >
                                {{ player[formula.name] }}
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr>
                            <td
                                class="text-center px-5 py-4 whitespace-no-wrap"
                                :colspan="
                                    columns.length +
                                    tags.length +
                                    formulas.length
                                "
                            >
                                No players found
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
import { sentenceCase } from '@/Services/helpers';

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
            sortable: [
                'deckcount',
                'points',
                'wins',
                'losses',
                'draws',
                ...this.formulas.map(f => f.name),
            ],
            sortBy: {
                col: 'points',
                asc: false,
            },
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
                    const comparison = a[this.sortBy.col] - b[this.sortBy.col];
                    return this.sortBy.asc ? comparison : -1 * comparison;
                });
        },
    },
    methods: {
        sentenceCase,
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
