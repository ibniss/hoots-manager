<template>
    <div
        class="rounded-md h-8 w-8 flex justify-center items-center cursor-pointer"
        :class="checked ? 'bg-green-500' : 'bg-gray-500'"
        @click="$emit('update:checked', !checked)"
    >
        <FontAwesomeIcon class="text-white" :icon="checked ? 'check' : 'ban'" />
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
