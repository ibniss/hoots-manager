<template>
    <div class="relative rounded-md shadow-sm">
        <div
            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
        >
            <svg
                class="text-gray-400 h-5 w-5"
                viewBox="0 0 20 20"
                fill="currentColor"
            >
                <path
                    fill-rule="evenodd"
                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                    clip-rule="evenodd"
                />
            </svg>
        </div>
        <input
            id="price"
            :value="value"
            class="form-input block w-full pl-10 pr-12 text-sm"
            placeholder="Search"
            type="text"
            @input="$emit('input', $event.target.value)"
        />
    </div>
</template>
<script>
export default {
    props: {
        value: {
            required: true,
            type: String,
        },
    },
};
</script>
