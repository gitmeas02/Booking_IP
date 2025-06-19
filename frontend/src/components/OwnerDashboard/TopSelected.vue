<template>
    <div class="border border-gray-500 rounded-xl px-5 py-3">
        <div class="flex justify-between items-start mb-6">
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-gray-900 mb-1">Top Selected package</h2>
                <p class="text-sm text-gray-500">{{ cardSubtitle }}</p>
            </div>
            <div class="">
                <select v-model="selectedPeriod"
                    class="border border-gray-300 rounded-md px-2 py-1.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-20">
                    <option value="1">1 Days</option>
                    <option value="7">7 Days</option>
                    <option value="30">30 Days</option>
                    <option value="60">60 Days</option>
                    <option value="90">90 Days</option>
                </select>
            </div>
        </div>

        <div class="space-y-5">
            <div v-for="metric in metrics" :key="metric.name" class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">{{ metric.name }}</span>
                    <span class="text-sm font-semibold text-gray-900">{{ metric.value }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded h-2 overflow-hidden">
                    <div class="h-full bg-gray-400 rounded transition-all duration-300 ease-out"
                        :style="{ width: metric.value + '%' }"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const selectedPeriod = ref('30')

const metrics = ref([
    { name: 'Booking Status', value: 60 },
    { name: 'Top Selected Room', value: 60 },
    { name: 'Revenue & Earnings', value: 60 },
    { name: 'Room Availability & Performance', value: 60 },
    { name: 'Reservation Trends', value: 60 }
])

const cardSubtitle = computed(() => {
    return `In last ${selectedPeriod.value} days top selected package.`
})
</script>

<style scoped>
/* Custom styles can be added here if needed */
</style>