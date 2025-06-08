<template>
  <div
    class="card flex flex-col justify-between items-center max-w-xs p-6 rounded-lg shadow-md bg-white text-center h-64 transition duration-200"
    :class="{
      'ring-2 ring-blue-500 border-blue-500': isSelected,
      'hover:ring-1 hover:ring-blue-300': !isSelected
    }"
  >
    <Icon :icon="icon" width="32" height="32" />
    <h2 class="title text-xl font-semibold mb-3">{{ property_type }}</h2>
    <p class="description text-sm text-gray-600 mb-5">{{ description }}</p>
    <button
      class="cta-button bg-gray-800 text-white py-2 px-4 rounded-lg font-medium mt-auto hover:bg-gray-700"
      @click="emit('click')"
    >
      List your properties
    </button>
  </div>
</template>

<script setup>
import { Icon } from '@iconify/vue';

defineProps({
  icon: String,
  property_type: String,
  description: String,
  isSelected: Boolean,
});

const emit = defineEmits(['click']);
</script>

<style scoped>
/* Tailwind handles all the styling, no need for custom CSS */
</style>