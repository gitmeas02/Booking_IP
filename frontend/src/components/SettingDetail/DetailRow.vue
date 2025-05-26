<template>
  <div class="flex justify-between items-start py-4 border-b border-gray-300">
    <div class="w-40 font-medium">{{ label }}</div>
    <div class="flex-1 text-gray-800">
      <div v-if="!editing">
        {{ value }}
      </div>
      <div v-else>
        <input
          class="border rounded px-2 py-1 w-full"
          :type="label === 'Date of birth' ? 'date' : 'text'"
          :value="editValue"
          @input="$emit('update', $event.target.value)"
          @keyup.enter="$emit('save')"
          @blur="$emit('save')"
          autofocus
        />
        <small v-if="note" class="text-gray-500 text-xs">{{ note }}</small>
      </div>
    </div>
    <div class="flex gap-2 items-center min-w-[120px] justify-end">
      <template v-if="editing">
        <button
          class="ml-2 text-blue-600 border border-blue-600 rounded px-3 py-1"
          @click="$emit('save')"
        >
          Save
        </button>
        <button
          class="text-red-600 border border-red-400 rounded px-3 py-1"
          @click="$emit('cancel')"
        >
          Cancel
        </button>
      </template>
      <template v-else>
        <span
          class="text-blue-600 cursor-pointer select-none"
          @click="$emit('edit')"
        >
          Edit
        </span>
      </template>
    </div>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  value: String,
  note: String,
  editing: Boolean,
  editValue: String,
});

defineEmits(['edit', 'update', 'save', 'cancel']);
</script>
