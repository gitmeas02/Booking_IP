<template>
    <div
      v-if="show"
      class="absolute z-50 bg-white shadow-lg border p-4 rounded w-64"
      :style="{ top: position.y + 'px', left: position.x + 'px' }"
    >
      <h3 class="text-lg font-semibold mb-2">Room Control</h3>
      <p class="text-sm text-gray-500 mb-1">Date: {{ dateStr }}</p>
  
      <div class="mb-3">
        <label class="text-sm">Price ($):</label>
        <input
          v-model.number="localPrice"
          type="number"
          class="w-full border p-1 rounded"
        />
      </div>
  
      <div class="flex items-center gap-2 mb-3">
        <input
          type="checkbox"
          v-model="localIsBlocked"
          id="block-toggle"
        />
        <label for="block-toggle" class="text-sm">Block Room</label>
      </div>
  
      <div class="flex justify-end gap-2">
        <button @click="close" class="text-sm px-3 py-1 bg-gray-200 rounded">Cancel</button>
        <button @click="save" class="text-sm px-3 py-1 bg-amber-300 rounded font-semibold">Save</button>
      </div>
    </div>
  </template>
  
  <script setup>
  const props = defineProps({
    show: Boolean,
    position: Object,
    dateStr: String,
    date: String,
    isBlocked: Boolean,
    price: Number,
    hotelId: Number,
    roomId: Number
  });
  </script>
  