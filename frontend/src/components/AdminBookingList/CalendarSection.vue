<template>
  
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