<template>
    <div class="flex flex-col items-center px-50 py-5">
        <div class="flex justify-between w-full">
            <div class="text-xl font-semibold">Occupancy Statistics</div>
            <div>
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
        <BarChart class="w-200 h-80"/>
    </div>
</template>

<script setup>
import BarChart from './Chart/BarChart.vue';


</script>