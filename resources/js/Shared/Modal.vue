<template>
    <div v-show="show" class="fixed z-10 inset-0 overflow-y-auto">
        <div
            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
        >
            <transition
                enter-class="opacity-0"
                enter-to-class="opacity-100"
                enter-active-class="ease-out duration-300"
                leave-class="opacity-100"
                leave-to-class="opacity-0"
                leave-active-class="ease-in duration-200"
            >
                <div v-show="show" class="fixed inset-0 transition-opacity">
                    <div
                        class="absolute inset-0 bg-gray-500 opacity-75"
                        @click.self="hide"
                    ></div>
                </div>
            </transition>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span
                class="hidden sm:inline-block sm:align-middle sm:h-screen"
            ></span
            >&#8203;

            <transition
                enter-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                enter-active-class="ease-out duration-300"
                leave-class="opacity-100 translate-y-0 sm:scale-100"
                leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale:95"
                leave-active-class="ease-in duration-200"
            >
                <div
                    v-show="show"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full"
                    :class="maxSize"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="modal-headline"
                >
                    <div class="absolute top-4 right-4">
                        <button
                            type="button"
                            class="inline-flex items-center rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                            @click="hide"
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
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <slot name="body" />
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                    >
                        <slot name="footer" v-bind="{ hide }" />
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        show: {
            required: true,
            type: Boolean,
        },
        maxSize: {
            type: String,
            default: 'sm:max-w-lg',
        },
    },
    created() {
        const escapeHandler = e => {
            if (e.key === 'Escape' && this.show) {
                this.hide();
            }
        };

        document.addEventListener('keydown', escapeHandler);
        this.$once('hook:destroyed', () => {
            document.removeEventListener('keydown', escapeHandler);
        });
    },
    methods: {
        /**
         * Hide the modal.
         */
        hide() {
            this.$emit('update:show', false);
        },
    },
};
</script>
