<template>
    <div v-click-outside="hidePopover" class="relative">
        <span class="inline-block w-full rounded-md shadow-sm">
            <button
                type="button"
                aria-haspopup="listbox"
                aria-expanded="true"
                :aria-labelledby="id"
                class="relative w-full rounded-md border border-gray-300 bg-white pl-10 pr-3 py-2 text-left font-medium text-gray-600 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 sm:text-sm"
                @click="showPopover = !showPopover"
            >
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none"
                >
                    <svg class="h5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            fill-rule="evenodd"
                            d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </span>
                Columns
            </button>
        </span>

        <transition
            leave-class="transform opacity-100"
            leave-active-class="transition ease-in duration-100"
            leave-to-class="transform opacity-0"
        >
            <div
                v-show="showPopover"
                class="absolute mt-1 w-max-content rounded-md bg-white shadow-lg"
            >
                <ul
                    tabindex="-1"
                    role="listbox"
                    :aria-labelledby="id"
                    class="rounded-md py-3 space-y-1.5 text-xs shadow-xs focus:outline-none"
                >
                    <template v-for="column in columns">
                        <li
                            :id="id + '-' + column.name + '-' + column.type"
                            :key="column.name + '-' + column.type"
                            role="option"
                            class="cursor-default select-none relative px-3"
                        >
                            <button
                                class="w-full inline-flex justify-center items-center px-4 py-2 rounded-md text-gray-500 font-medium uppercase tracking-wider hover:bg-gray-200 hover:text-gray-600 focus:outline-none active:bg-gray-200 active:text-gray-600 transition ease-in-out duration-150"
                                :class="
                                    isHidden(column)
                                        ? ''
                                        : 'border border-gray-300 bg-gray-100 focus:border-blue-300 focus:shadow-outline'
                                "
                                @click="toggleColumn(column)"
                            >
                                <slot name="column" v-bind="{ column }">
                                    {{ column.name }}
                                </slot>
                            </button>
                        </li>
                    </template>
                </ul>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            required: true,
            type: String,
        },
        columns: {
            required: true,
            type: Array,
        },
        hiddenColumns: {
            required: true,
            type: Array,
        },
    },
    data() {
        return {
            showPopover: false,
        };
    },
    methods: {
        /**
         * Hide the popover.
         */
        hidePopover() {
            this.showPopover = false;
        },

        /**
         * Toggle a given column's visibility.
         *
         * @param {Object} column
         */
        toggleColumn(column) {
            const columnTag = this.getColumnTag(column);
            const hiddenIndex = this.hiddenColumns.findIndex(
                x => x === columnTag
            );

            // if column not currently hidden
            if (hiddenIndex === -1) {
                this.$emit('update:hiddenColumns', [
                    ...this.hiddenColumns,
                    columnTag,
                ]);
            } else {
                const copy = this.hiddenColumns.slice(0);
                copy.splice(hiddenIndex, 1);
                this.$emit('update:hiddenColumns', copy);
            }
        },

        /**
         * Whether a given column is currently hidden.
         *
         * @param {Object} column
         * @returns {Boolean}
         */
        isHidden(column) {
            const columnTag = this.getColumnTag(column);
            return this.hiddenColumns.includes(columnTag);
        },

        /**
         * Get the tag used to identify the column.
         *
         * @param {Object} column
         * @returns {String}
         */
        getColumnTag(column) {
            return `${column.type}-${column.name}`;
        },
    },
};
</script>
