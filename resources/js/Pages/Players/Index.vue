<template>
    <div class="space-y-6">
        <table
            class="w-full max-w-full table-fixed border-collapse border border-solid border-gray-300"
        >
            <thead>
                <tr>
                    <th
                        v-for="col in columns"
                        :key="col"
                        :class="sizes[col] ? `w-${sizes[col]}` : ''"
                        class="px-4 py-2 border border-solid border-gray-300"
                    >
                        <div
                            class="flex justify-between space-x-1"
                            :class="
                                sortable.includes(col) ? 'cursor-pointer' : ''
                            "
                            @click="sortable.includes(col) && sort(col)"
                        >
                            <span>{{
                                col.charAt(0).toUpperCase() + col.slice(1)
                            }}</span>
                            <template v-if="sortable.includes(col)">
                                <FontAwesomeIcon
                                    v-if="sortBy.col === col"
                                    :icon="sortBy.asc ? 'sort-up' : 'sort-down'"
                                />
                                <FontAwesomeIcon v-else icon="sort" />
                            </template>
                        </div>
                    </th>
                    <th
                        v-for="tag in tags"
                        :key="tag.name"
                        class="px-4 py-2 border border-solid border-gray-300"
                    >
                        {{
                            tag.name.charAt(0).toUpperCase() + tag.name.slice(1)
                        }}
                    </th>
                    <th
                        v-for="formula in formulas"
                        :key="formula.name"
                        class="px-4 py-2 border border-solid border-gray-300"
                    >
                        <div
                            class="flex space-x-1 cursor-pointer"
                            @click="sort(formula.name)"
                        >
                            <span>
                                {{
                                    formula.name.charAt(0).toUpperCase() +
                                    formula.name.slice(1)
                                }}
                            </span>
                            <FontAwesomeIcon
                                v-tooltip="formula.equation"
                                icon="info-circle"
                            />
                            <FontAwesomeIcon
                                v-if="sortBy.col === formula.name"
                                class="self-end"
                                :icon="sortBy.asc ? 'sort-up' : 'sort-down'"
                            />
                            <FontAwesomeIcon
                                v-else
                                class="self-end"
                                icon="sort"
                            />
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <template v-if="players.length > 0">
                    <tr v-for="player in filteredData" :key="player.id">
                        <td
                            v-for="col in columns"
                            :key="col"
                            class="px-4 py-2 border border-solid border-gray-300"
                        >
                            {{ player[col] }}
                        </td>

                        <td
                            v-for="tag in tags"
                            :key="tag.name"
                            class="px-4 py-2 border border-solid border-gray-300"
                        >
                            <div class="flex justify-center items-center">
                                <Checkbox
                                    :checked="player.tag_values[tag.name]"
                                    @update:checked="
                                        toggleTag(player, tag.name)
                                    "
                                />
                            </div>
                        </td>

                        <td
                            v-for="formula in formulas"
                            :key="formula.name"
                            class="px-4 py-2 border border-solid border-gray-300"
                        >
                            {{ player[formula.name] }}
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <tr>
                        <td
                            class="text-center px-4 py-2 border border-solid border-gray-300"
                            :colspan="columns.length"
                        >
                            No players entered!
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <div class="flex justify-end items-center space-x-4">
            <input
                ref="playersCsv"
                type="file"
                class="hidden"
                @change="uploadPlayers"
            />
            <button
                class="rounded-md px-4 py-3 bg-blue-700 text-blue-100 hover:bg-blue-800"
                @click="$refs.playersCsv.click()"
            >
                Upload Players CSV
            </button>
            <button
                class="rounded-md px-4 py-3 bg-red-700 text-white hover:bg-red-800"
                @click="deletePlayers"
            >
                Delete all players
            </button>
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
                wins: '20',
                draws: '20',
                losses: '20',
                points: '20',
            },
            sortable: [
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
        };
    },
    computed: {
        filteredData() {
            return this.players.slice(0).sort((a, b) => {
                const comparison = a[this.sortBy.col] - b[this.sortBy.col];
                return this.sortBy.asc ? comparison : -1 * comparison;
            });
        },
    },
    methods: {
        /**
         * Sort by given col.
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
            this.$inertia.put(route('players.tags.update', [player, tagName]));
        },

        deletePlayers() {
            this.$inertia.delete(route('players.delete'));
        },
    },
};
</script>
