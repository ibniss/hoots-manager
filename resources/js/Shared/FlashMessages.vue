<template>
    <div>
        <transition
            leave-class="transform opacity-100"
            leave-active-class="transition ease-in duration-100"
            leave-to-class="transform opacity-0"
        >
            <div
                v-if="$page.flash.success && show"
                class="relative shadow rounded-md bg-green-100 text-green-700 text-sm p-4 w-full cursor-pointer"
                @click="show = false"
            >
                <div
                    class="absolute left-0 top-0 pt-6 ml-3 flex items-center justify-center h-6 w-6"
                >
                    <svg
                        class="h-6 w-6 text-green-400"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <span class="ml-8 font-semibold">
                    {{ $page.flash.success }}
                </span>
                <div class="absolute top-0 right-0 pt-4 pr-4">
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
                v-if="
                    ($page.flash.error ||
                        Object.keys($page.errors).length > 0) &&
                    show
                "
                class="group relative shadow rounded-md bg-red-100 text-red-700 text-sm p-4 w-full cursor-pointer"
                @click="show = false"
            >
                <svg
                    class="absolute top-3 left-3 h-6 w-6 text-red-400"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"
                    />
                </svg>
                <div v-if="$page.flash.error" class="mx-8 font-semibold">
                    {{ $page.flash.error }}
                </div>
                <div v-else class="mx-8">
                    <span
                        v-if="Object.keys($page.errors).length === 1"
                        class="font-semibold"
                    >
                        {{ Object.values($page.errors)[0] }}
                    </span>
                    <span v-else>
                        <p class="font-semibold">
                            There were
                            {{ Object.keys($page.errors).length }} errors with
                            your submission
                        </p>
                        <ul class="mt-1 list-disc list-inside font-medium">
                            <li v-for="(error, key) in $page.errors" :key="key">
                                {{ error }}
                            </li>
                        </ul>
                    </span>
                </div>
                <div class="absolute top-0 right-0 pt-4 pr-4">
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
        };
    },
    watch: {
        '$page.flash': {
            handler() {
                this.show = true;
                setTimeout(() => (this.show = false), 2000);
            },
            deep: true,
        },
    },
};
</script>
