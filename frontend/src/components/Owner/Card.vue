<template>
    <div
        class="card flex flex-col justify-between items-center max-w-xs p-6 rounded-lg shadow-md bg-white text-center h-64">
        <Icon :icon="icon" width="32" height="32" />
        <h2 class="title text-xl font-semibold mb-3">{{ title }}</h2>
        <p class="description text-sm text-gray-600 mb-5">{{ description }}</p>
        <button class="cta-button bg-gray-800 text-white py-2 px-4 rounded-lg font-medium mt-auto hover:bg-gray-700">
            List your properties
        </button>
    </div>
</template>

<script setup>
import { Icon } from '@iconify/vue';

defineProps({
    icon: {
        type: String,
        required: true
    },
    title: {
        type: String,
        required: true
    },
    description: {
        type: String,
        required: true
    }
});
</script>

<style scoped>
/* Tailwind handles all the styling, no need for custom CSS */
</style>