<template>
    <div class="space-y-1">
        <label
            v-if="label"
            :id="id"
            class="block text-sm leading-5 font-medium text-gray-700"
        >
            {{ label }}
        </label>
        <div v-click-outside="hidePopover" class="relative">
            <span class="inline-block w-full rounded-md shadow-sm">
                <button
                    type="button"
                    aria-haspopup="listbox"
                    aria-expanded="true"
                    :aria-labelledby="id"
                    class="cursor-default relative w-full rounded-md border border-gray-300 bg-white pl-3 pr-10 py-2 text-left focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                    @click="showPopover = !showPopover"
                >
                    <slot name="selectedOption" v-bind="{ value }">
                        {{ labelResolver(value) }}
                    </slot>
                    <span
                        class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
                    >
                        <svg
                            class="h-5 w-5 text-gray-400"
                            viewBox="0 0 20 20"
                            fill="none"
                            stroke="currentColor"
                        >
                            <path
                                d="M7 7l3-3 3 3m0 6l-3 3-3-3"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </span>
                </button>
            </span>

            <transition
                leave-class="transform opacity-100"
                leave-active-class="transition ease-in duration-100"
                leave-to-class="transform opacity-0"
            >
                <div
                    v-show="showPopover"
                    class="absolute mt-1 w-full rounded-md bg-white shadow-lg"
                >
                    <ul
                        tabindex="-1"
                        role="listbox"
                        :aria-labelledby="id"
                        class="max-h-56 rounded-md py-1 text-base leading-6 shadow-xs overflow-auto focus:outline-none sm:text-sm sm:leading-5"
                    >
                        <template v-for="option in options">
                            <li
                                :id="id + '-0'"
                                :key="indexResolver(option)"
                                role="option"
                                class="cursor-default select-none relative py-2 pl-3 pr-9"
                                :class="
                                    isOptionHovered(option)
                                        ? 'text-white bg-indigo-600'
                                        : 'text-gray-900'
                                "
                                @mouseenter="
                                    currentHover = indexResolver(option)
                                "
                                @mouseleave="currentHover = null"
                                @click="selectOption(option)"
                            >
                                <slot name="option" v-bind="{ option }">
                                    {{ labelResolver(option) }}
                                </slot>

                                <span
                                    class="absolute inset-y-0 right-0 flex items-center pr-4"
                                >
                                    <svg
                                        v-if="isOptionSelected(option)"
                                        class="h-5 w-5"
                                        :class="
                                            isOptionHovered(option)
                                                ? 'text-white'
                                                : 'text-indigo-600'
                                        "
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </span>
                            </li>
                        </template>
                    </ul>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            required: true,
            type: String,
        },
        label: {
            default: undefined,
            validator: val => ['undefined', 'string'].includes(typeof val),
        },
        options: {
            required: true,
            type: Array,
        },
        indexResolver: {
            default: val => val,
            type: Function,
        },
        labelResolver: {
            default: val => val,
            type: Function,
        },
        // eslint-disable-next-line
        value: {},
        placeholder: {
            type: String,
            default: '',
        },

        /**
         * Used for sending back events
         */
        reduce: {
            default: val => val,
            type: Function,
        },
    },
    data() {
        return {
            currentHover: null,
            showPopover: false,
        };
    },
    methods: {
        /**
         * Check if the given option is currently selected.
         * @param  {Object|String}  option
         * @return {Boolean}        True when selected | False otherwise
         */
        isOptionSelected(option) {
            return this.optionComparator(this.value, option);
        },

        /**
         * Check if the given option is currently being hovered over.
         * @param  {Object|String}  option
         * @return {Boolean}        True when hovered | False otherwise
         */
        isOptionHovered(option) {
            return this.optionComparator(this.currentHover, option);
        },

        /**
         * Determine if two option objects are matching.
         *
         * @param a {Object}
         * @param b {Object}
         * @returns {boolean}
         */
        optionComparator(a, b) {
            return this.indexResolver(a) === this.indexResolver(b);
        },

        /**
         * Select a given option.
         *
         * @param {any} option
         */
        selectOption(option) {
            this.$emit('input', this.reduce(option));
            this.showPopover = false;
            this.currentHover = null;
        },

        /**
         * Hide the popover.
         */
        hidePopover() {
            this.showPopover = false;
        },
    },
};
</script>
