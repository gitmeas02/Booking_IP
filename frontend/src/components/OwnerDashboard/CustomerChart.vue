<template>
    <div class="flex flex-col border border-gray-500 rounded-xl p-3 gap-3">
        <div class="flex flex-col items-center">
            <h1 class="text-xl font-semibold">{{ title }}</h1>
            <span class="text-sm text-gray-500">{{ subtitle }}</span>
        </div>

        <div class="flex justify-between px-2">
            <div class="flex flex-col items-center">
                <div>Monthly</div>
                <div class="text-2xl font-bold">{{ monthly.value }}</div>
                <div :class="monthly.change >= 0 ? 'text-green-600' : 'text-red-600'">
                    <span v-if="monthly.change >= 0">▲</span>
                    <span v-else>▼</span>
                    {{ Math.abs(monthly.change) }}%
                </div>
            </div>
            <div class="flex flex-col items-center">
                <div>Weekly</div>
                <div class="text-2xl font-bold">{{ weekly.value }}</div>
                <div :class="weekly.change >= 0 ? 'text-green-600' : 'text-red-600'">
                    <span v-if="weekly.change >= 0">▲</span>
                    <span v-else>▼</span>
                    {{ Math.abs(weekly.change) }}%
                </div>
            </div>
            <div class="flex flex-col items-center">
                <div>Daily(AVG)</div>
                <div class="text-2xl font-bold">{{ daily.value }}</div>
                <div :class="daily.change >= 0 ? 'text-green-600' : 'text-red-600'">
                    <span v-if="daily.change >= 0">▲</span>
                    <span v-else>▼</span>
                    {{ Math.abs(daily.change) }}%
                </div>
            </div>
        </div>

        <div class="flex gap-12 pl-5 pr-11">
            <PieChart :chart-data="pieChartData" class="w-55 h-55" />

        </div>
    </div>
</template>

<script setup>
import PieChart from './Chart/PieChart.vue';

const props = defineProps({
    title: { type: String, default: 'New Customer' },
    subtitle: { type: String, default: 'In last 30 days' },
    monthly: {
        type: Object,
        default: () => ({ value: '0', change: 0 })
    },
    weekly: {
        type: Object,
        default: () => ({ value: '0', change: 0 })
    },
    daily: {
        type: Object,
        default: () => ({ value: '0', change: 0 })
    },
    pieChartData: {
        type: Object,
        default: () => ({
            labels: ['Daily(AVG)', 'Weekly', 'Monthly'],
            datasets: [{
                data: [0, 0, 0],
                backgroundColor: ['#000', '#60a5fa', '#fde68a']
            }]
        })
    }
});
</script>