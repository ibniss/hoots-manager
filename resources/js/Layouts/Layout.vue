<template>
  <div>
    <header class="relative border-b border-gray-200 bg-blue-600 flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
      <div class="text-blue-100">
        Hooglandia Feature Picker
      </div>
      <div class="flex justify-end space-x-4">
        <inertia-link
          class="text-blue-200"
          :href="route('users.index')"
        >
          Users
        </inertia-link>
        <inertia-link
          class="text-blue-200"
          :href="route('tags.index')"
        >
          Tags
        </inertia-link>
      </div>
    </header>
    <main>
      <div
        class="flex-1 px-4 py-8 md:p-12 overflow-y-auto"
        scroll-region
      >
        <slot />
      </div>
    </main>
  </div>
</template>

<script>
export default {};
</script>
