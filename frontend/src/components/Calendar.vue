<template>
  <div>
    <div class="text-center font-bold mb-2">{{ monthName }} {{ year }}</div>
    <div class="grid grid-cols-7 text-sm text-center">
      <div v-for="day in daysShort" :key="day" class="font-medium text-gray-500">{{ day }}</div>
    </div>
    <div class="grid grid-cols-7 text-center rounded-l-2xl">
      <div v-for="blank in firstDay" :key="'b'+blank"></div>
      <button
        v-for="day in daysInMonth"
        :key="day"
        class="pr-4 pl-4 pt-2 pb-2 relative"
        :class="getDayClass(day)"
        @click="selectDate(day)"
      >
        {{ day }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  month: Number, // 0-based (Jan = 0)
  year: Number,
  startDate: Date,
  endDate: Date,
})

const emit = defineEmits(['date-selected'])

const daysShort = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']

const firstDay = computed(() => new Date(props.year, props.month, 1).getDay())
const daysInMonth = computed(() => new Date(props.year, props.month + 1, 0).getDate())
const monthName = computed(() => new Date(props.year, props.month).toLocaleString('default', { month: 'long' }))

function selectDate(day) {
  emit('date-selected', new Date(props.year, props.month, day))
}

function isSameDate(d1, d2) {
  return d1?.getFullYear() === d2?.getFullYear() &&
         d1?.getMonth() === d2?.getMonth() &&
         d1?.getDate() === d2?.getDate()
}

function isBetween(date, start, end) {
  return start && end && date > start && date < end
}

function getDayClass(day) {
  const thisDate = new Date(props.year, props.month, day)
  const today = new Date()
  
  // Highlight today's date
  if (thisDate.getDate() === today.getDate() && 
      thisDate.getMonth() === today.getMonth() && 
      thisDate.getFullYear() === today.getFullYear()) {
    return 'border border-blue-500 rounded-full'
  }
  
  if (isSameDate(thisDate, props.startDate) || isSameDate(thisDate, props.endDate)) {
    return 'bg-blue-600 text-white font-bold'
  }
  if (isBetween(thisDate, props.startDate, props.endDate)) {
    return 'bg-blue-200 text-blue-900'
  }
  return 'hover:bg-gray-200'
}
</script>