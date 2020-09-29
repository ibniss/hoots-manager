<template>
    <div>
        <transition
            leave-class="transform opacity-100"
            leave-active-class="transition ease-in duration-100"
            leave-to-class="transform opacity-0"
        >
            <div
                v-if="success && show"
                class="group relative flex items-start space-x-4 shadow rounded-md bg-green-100 text-green-700 text-sm p-4 w-full cursor-pointer"
                @click="show = false"
            >
                <svg
                    class="mx-auto flex-shrink-0 -mt-0.5 h-6 w-6 text-green-400"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"
                    />
                </svg>
                <span class="flex-grow font-semibold">
                    {{ success }}
                </span>
                <div class="absolute top-0 right-0 pt-3.5 pr-4">
                    <button
                        type="button"
                        class="inline-flex items-center rounded-md text-green-400 group-hover:text-green-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
            <div
                v-if="errors.length > 0 && show"
                class="group relative flex items-start space-x-4 shadow rounded-md bg-red-100 text-red-700 text-sm p-4 w-full cursor-pointer"
                @click="show = false"
            >
                <svg
                    class="mx-auto flex-shrink-0 -mt-0.5 h-6 w-6 text-red-400"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"
                    />
                </svg>
                <div class="flex-grow mx-8">
                    <span v-if="errors.length === 1" class="font-semibold">
                        {{ errors[0] }}
                    </span>
                    <span v-else>
                        <p class="font-semibold">
                            There were
                            {{ errors.length }} errors with your submission
                        </p>
                        <ul class="mt-1 list-disc list-inside font-medium">
                            <li v-for="(error, key) in $page.errors" :key="key">
                                {{ error }}
                            </li>
                        </ul>
                    </span>
                </div>
                <div class="absolute top-0 right-0 pt-3.5 pr-4">
                    <button
                        type="button"
                        class="inline-flex items-center rounded-md text-red-400 group-hover:text-red-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </transition>
    </div>
</template>
<script>
export default {
    data() {
        return {
            show: true,
            errorSet: new Set(),
            success: null,
            timeoutId: null,
        };
    },
    computed: {
        /**
         * Get the error set as an array.
         *
         * @returns {Array}
         */
        errors() {
            return [...this.errorSet];
        },
    },
    watch: {
        $page: {
            handler(newPage) {
                this.clearData();

                // flash part
                if (newPage.flash.error) {
                    this.errorSet.add(newPage.flash.error);
                }
                this.success = newPage.flash.success;

                // errors part
                if (Object.values(newPage.errors).length > 0) {
                    Object.values(newPage.errors).forEach(err =>
                        this.errorSet.add(err)
                    );
                }

                this.show = true;
                this.cleanUp();
            },
            deep: true,
        },
    },
    mounted() {
        this.$root.$on('success', message => {
            this.clearData();
            this.success = message;
            this.show = true;
            this.cleanUp();
        });

        this.$root.$on('error', messages => {
            this.clearData();
            messages.forEach(err => this.errorSet.add(err));
            this.show = true;
            this.cleanUp();
        });
    },
    methods: {
        /**
         * Set a timeout to clean up the flash messages.
         */
        cleanUp() {
            clearTimeout(this.timeoutId);
            this.timeoutId = setTimeout(() => {
                this.clearData();
            }, 2000);
        },

        /**
         * Clear the flash messages.
         */
        clearData() {
            this.show = false;
            this.errorSet = new Set();
            this.success = null;
        },
    },
};
</script>
