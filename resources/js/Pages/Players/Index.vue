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
                        {{ col.charAt(0).toUpperCase() + col.slice(1) }}
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
                </tr>
            </thead>
            <tbody>
                <template v-if="players.length > 0">
                    <tr v-for="player in players" :key="player.id">
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
                                />
                            </div>
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

        <div class="flex justify-end items-center">
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
        };
    },
    methods: {
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
    },
};
</script>
