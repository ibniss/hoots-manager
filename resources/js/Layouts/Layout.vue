<template>
    <div>
        <div class="flex flex-col">
            <div class="h-screen flex flex-col">
                <!-- Header -->
                <div class="flex-shrink-0">
                    <div class="bg-blue-700 p-4 text-blue-200">
                        Hooglandia Feature Picker
                    </div>
                </div>
                <div class="flex flex-grow overflow-hidden">
                    <div
                        class="flex-1 px-4 py-8 md:p-12 overflow-y-auto"
                        scroll-region
                    >
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {};
</script>
