<template>
    <div class="flex justify-between items-start py-4 border-b border-gray-300">
        <div class="w-40 font-medium">{{ label }}</div>
        <div class="flex-1 text-gray-800">
            <div>{{ value }}</div>
            <small v-if="note" class="text-gray-500 text-xs">{{ note }}</small>
        </div>
        <div class="text-blue-600 cursor-pointer">Edit</div>
    </div>
</template>

<script setup>
defineProps({
    label: String,
    value: String,
    note: String
})
</script>