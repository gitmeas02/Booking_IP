<template>
  <div class="mx-6 mb-6">
    <div class="bg-white rounded-lg border border-gray-300 px-6 py-4">
      <div class="flex items-center justify-center space-x-2">
        <!-- Previous Arrow -->
        <button
          @click="goToPreviousPage"
          :disabled="currentPage === 1"
          class="p-2 text-gray-500 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>

        <!-- Page Numbers -->
        <button
          v-for="page in pageNumbers"
          :key="page"
          @click="goToPage(page)"
          :class="{
            'bg-blue-500 text-white': page === currentPage,
            'text-gray-700 hover:text-blue-500': page !== currentPage
          }"
          class="w-8 h-8 flex items-center justify-center rounded text-sm font-medium"
        >
          {{ page }}
        </button>

        <!-- Next Arrow -->
        <button
          @click="goToNextPage"
          :disabled="currentPage === totalPages"
          class="p-2 text-gray-500 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  currentPage: {
    type: Number,
    required: true
  },
  totalPages: {
    type: Number,
    required: true
  }
})

const emit = defineEmits(['page-change'])

const pageNumbers = computed(() => {
  const pages = []
  for (let i = 1; i <= props.totalPages; i++) {
    pages.push(i)
  }
  return pages
})

const goToPage = (page) => {
  if (page !== props.currentPage) {
    emit('page-change', page)
  }
}

const goToPreviousPage = () => {
  if (props.currentPage > 1) {
    emit('page-change', props.currentPage - 1)
  }
}

const goToNextPage = () => {
  if (props.currentPage < props.totalPages) {
    emit('page-change', props.currentPage + 1)
  }
}
</script>