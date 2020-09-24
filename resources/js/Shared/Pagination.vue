<template>
    <div class="text-sm flex justify-between items-center">
        <div>
            Showing
            <span class="font-semibold">
                {{ from }}
            </span>
            to
            <span class="font-semibold">
                {{ to }}
            </span>
            of
            <span class="font-semibold">
                {{ rowCount }}
            </span>
            results
        </div>
        <div class="flex justify-end items-center gap-x-4">
            <span class="shadow-sm rounded-md">
                <button
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md bg-white text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                    @click="previous"
                >
                    Previous
                </button>
            </span>
            <span class="shadow-sm rounded-md">
                <button
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md bg-white text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                    @click="next"
                >
                    Next
                </button>
            </span>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        from: {
            required: true,
            type: Number,
        },
        to: {
            required: true,
            type: Number,
        },
        maxPages: {
            required: true,
            type: Number,
        },
        rowCount: {
            required: true,
            type: Number,
        },
        currentPage: {
            required: true,
            type: Number,
        },
    },
    methods: {
        /**
         * Switch to previous page.
         */
        previous() {
            if (this.currentPage !== 1) {
                this.$emit('update:currentPage', this.currentPage - 1);
            }
        },

        /**
         * Switch to next page.
         */
        next() {
            if (this.currentPage !== this.maxPages) {
                this.$emit('update:currentPage', this.currentPage + 1);
            }
        },
    },
};
</script>
