<template>
    <div
        class="rounded-full shadow-xs h-9 w-9 flex justify-center items-center cursor-pointer"
        :class="checked ? 'bg-green-500' : 'bg-gray-300'"
        @click="$emit('update:checked', !checked)"
    >
        <FontAwesomeIcon
            v-if="checked"
            class="text-green-100"
            :icon="'check'"
        />
    </div>
</template>

<script>
export default {
    props: {
        checked: {
            required: true,
            type: Boolean,
        },
    },
};
</script>
