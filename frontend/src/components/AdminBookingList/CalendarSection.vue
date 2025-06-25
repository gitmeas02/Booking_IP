<template>
  <div class="bg-white mx-6 mt-6 mb-4 rounded-lg border border-gray-300">
    <div class="px-6 py-4">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">Calendar</h2>
      <div class="text-center">
        <p class="text-gray-600 text-lg">
          Today {{ currentDate }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const currentDate = ref('')

const formatDate = () => {
  const today = new Date()
  const day = today.getDate()
  const month = today.toLocaleString('default', { month: 'long' })
  const year = today.getFullYear()
  
  currentDate.value = `${day}/${month}/${year}`
}

onMounted(() => {
  formatDate()
})
</script>