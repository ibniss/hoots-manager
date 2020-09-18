<template>
    <div class="space-y-6">
        <table
            class="w-full max-w-full table-auto border-collapse border border-solid border-gray-300"
        >
            <thead>
                <tr>
                    <th
                        class="px-4 py-2 border border-solid border-gray-300"
                        v-for="col in columns"
                        :key="col"
                    >
                        {{ col.charAt(0).toUpperCase() + col.slice(1) }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <template v-if="users.length > 0">
                    <tr v-for="user in users" :key="user.id">
                        <td
                            class="px-4 py-2 border border-solid border-gray-300"
                            v-for="col in columns"
                            :key="col"
                        >
                            {{ user[col] }}
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <tr>
                        <td
                            class="text-center px-4 py-2 border border-solid border-gray-300"
                            :colspan="columns.length"
                        >
                            No users entered!
                            <inertia-link href="#"
                                >Import users first</inertia-link
                            >
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <div class="flex justify-end items-center">
            <input type="file" class="hidden" ref="usersCsv" @change="uploadUsers" />
            <button
                class="rounded-md px-4 py-3 bg-blue-700 text-blue-100 hover:bg-blue-800"
                @click="$refs.usersCsv.click()"
            >
                Upload Users CSV
            </button>
        </div>
    </div>
</template>
<script>
import Layout from "@/Layouts/Layout";

export default {
    layout: Layout,
    props: {
        columns: {
            required: true,
            type: Array
        },
        users: {
            required: true,
            type: Array
        }
    },
    methods: {
      /**
       * Send a request with the CSV data uploaded.
       *
       * @param {Event} event
       */
      uploadUsers(event) {
        const files = event.target.files || event.dataTransfer.files;

        if (!files.length) return;

        const data = new FormData();
        data.append('data', files[0]);
        this.$inertia.post(route('users.store'), data);
      }
    }
};
</script>
