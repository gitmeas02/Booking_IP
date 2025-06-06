<template>
  <!-- <div class="p-4"> -->
  <div
    class="relative w-60 h-[17.5rem] rounded-lg bg-amber-600 overflow-hidden transition-transform duration-300 ease-in-out hover:-translate-y-1 hover:scale-110 shadow-lg cursor-pointer"
    role="button"
    tabindex="0"
    @click="handleClick"
    @keydown.enter="handleClick"
    @keydown.space="handleClick"
  >
    <img
      :src="image"
      :alt="label"
      class="w-full h-full object-cover rounded-lg"
    />

    <!-- Discount Badge -->
    <div
      v-if="discount"
      class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-sm font-bold"
    >
      -{{ discount }}%
    </div>

    <!-- Content Overlay -->
    <div
      class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4"
    >
      <p class="text-lg text-white font-bold mb-1">{{ label }}</p>
      <p v-if="description" class="text-sm text-gray-200 mb-2 line-clamp-2">
        {{ description }}
      </p>

      <div class="flex justify-between flex-col items-center">
        <div
          v-if="code"
          class="bg-amber-500 text-white px-2 py-1 rounded text-xs font-medium"
        >
          Code: {{ code }}
        </div>
        <div v-if="validUntil" class="text-xs text-gray-300">
          Valid until: {{ formatDate(validUntil) }}
        </div>
      </div>
    </div>
  </div>
  <!-- </div> -->
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  image: {
    type: String,
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
  discount: {
    type: Number,
    default: null,
  },
  code: {
    type: String,
    default: null,
  },
  description: {
    type: String,
    default: null,
  },
  validUntil: {
    type: String,
    default: null,
  },
});

const emit = defineEmits(["click"]);

const handleClick = () => {
  console.log(`Card clicked: ${props.label}`);
  emit("click", {
    label: props.label,
    discount: props.discount,
    code: props.code,
    description: props.description,
    validUntil: props.validUntil,
  });
};

const formatDate = (dateString) => {
  if (!dateString) return "";
  const date = new Date(dateString);
  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.cursor-pointer {
  cursor: pointer;
}
</style>
