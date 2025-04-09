<script setup>
import { ref, computed, onMounted } from 'vue'
import Calendar from './Calendar.vue'

const today = new Date()
const currentMonth = ref(today.getMonth())
const currentYear = ref(today.getFullYear())
const startDate = ref(null)
const endDate = ref(null)

const nextMonth = computed(() => (currentMonth.value + 1) % 12)
const nextYear = computed(() =>
  currentMonth.value === 11 ? currentYear.value + 1 : currentYear.value
)

function prevMonth() {
  if (currentMonth.value === 0) {
    currentMonth.value = 11
    currentYear.value--
  } else {
    currentMonth.value--
  }
}

function goToNextMonth() {
  if (currentMonth.value === 11) {
    currentMonth.value = 0
    currentYear.value++
  } else {
    currentMonth.value++
  }
}

function resetToToday() {
  currentMonth.value = today.getMonth()
  currentYear.value = today.getFullYear()
}

function onDateSelected(date) {
  if (!startDate.value || (startDate.value && endDate.value)) {
    startDate.value = date
    endDate.value = null
  } else if (date > startDate.value) {
    endDate.value = date
  } else {
    startDate.value = date
    endDate.value = null
  }
}

function formatMonthYear(year, month) {
  const date = new Date(year, month)
  return date.toLocaleString('default', { month: 'long', year: 'numeric' })
}
</script>

<template>
  <div class="p-4 bg-white rounded shadow w-full max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-4">
      <button @click="prevMonth" class="text-blue-500 hover:underline">&larr; Prev</button>
      <div class="flex items-center gap-4">
        <button 
          @click="resetToToday" 
          class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm"
        >
          Today
        </button>
        <div class="font-semibold text-lg">
          {{ formatMonthYear(currentYear, currentMonth) }} & - & {{ formatMonthYear(nextYear, nextMonth) }}
        </div>
      </div>
      <button @click="goToNextMonth" class="text-blue-500 hover:underline">Next &rarr;</button>
    </div>

    <div class="flex gap-4">
      <Calendar
        :month="currentMonth"
        :year="currentYear"
        :start-date="startDate"
        :end-date="endDate"
        @date-selected="onDateSelected"
      />
      <Calendar
        :month="nextMonth"
        :year="nextYear"
        :start-date="startDate"
        :end-date="endDate"
        @date-selected="onDateSelected"
      />
    </div>
  </div>
</template>