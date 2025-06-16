<template>
    <div class="flex flex-col items-center px-50 py-5">
        <div class="flex justify-between w-full">
            <div class="text-xl font-semibold">Occupancy Statistics</div>
            <div>
                <select v-model="selectedPeriod"
                    class="border border-gray-300 rounded-md px-2 py-1.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-20">
                    <option value="1">1 Day</option>
                    <option value="7">7 Days</option>
                    <option value="30">30 Days</option>
                    <option value="60">60 Days</option>
                    <option value="90">90 Days</option>
                </select>
            </div>
        </div>
        <BarChart :chart-data="chartData" class="w-200 h-80" />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import BarChart from './Chart/BarChart.vue';

const selectedPeriod = ref('7');

const dummyData = {
    '1': {
        labels: ['Today'],
        datasets: [{
            label: 'Occupancy %',
            data: [78],
            backgroundColor: ['#60a5fa'],
        }],
    },
    '7': {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Occupancy %',
            data: [75, 80, 78, 85, 90, 88, 82],
            backgroundColor: ['#60a5fa', '#60a5fa', '#60a5fa', '#60a5fa', '#60a5fa', '#60a5fa', '#60a5fa'],
        }],
    },
    '30': {
        labels: Array.from({ length: 30 }, (_, i) => `Day ${i + 1}`),
        datasets: [{
            label: 'Occupancy %',
            data: Array.from({ length: 30 }, () => Math.floor(Math.random() * 30) + 70),
            backgroundColor: Array(30).fill('#60a5fa'),
        }],
    },
    '60': {
        labels: Array.from({ length: 12 }, (_, i) => `W${i + 1}`),
        datasets: [{
            label: 'Occupancy %',
            data: Array.from({ length: 12 }, () => Math.floor(Math.random() * 30) + 65),
            backgroundColor: Array(12).fill('#60a5fa'),
        }],
    },
    '90': {
        labels: Array.from({ length: 3 }, (_, i) => `Month ${i + 1}`),
        datasets: [{
            label: 'Occupancy %',
            data: [72, 85, 80],
            backgroundColor: ['#60a5fa', '#60a5fa', '#60a5fa'],
        }],
    },
};

const chartData = computed(() => dummyData[selectedPeriod.value]);
</script>