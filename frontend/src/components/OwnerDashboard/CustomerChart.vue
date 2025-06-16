<template>
    <div class="flex flex-col border border-gray-500 rounded-xl p-3 gap-3">
        <div class="flex flex-col items-center">
            <h1 class="text-xl font-semibold">New Customer</h1>
            <span class="text-sm text-gray-500">In last 30 days</span>
        </div>

        <div class="flex justify-between px-2">
            <div class="flex flex-col">
                <div>Montly</div>
                <div>9.28K</div>
                <div></div>
            </div>
            <div class="flex flex-col">
                <div>Weekly</div>
                <div>2.89K</div>
                <div></div>
            </div>
            <div class="flex flex-col">
                <div>Daily(AVG)</div>
                <div>0.90K</div>
                <div></div>
            </div>
        </div>

        <div class="flex gap-12 pl-5 pr-11">
            <PieChart class="w-55 h-55"/>
        </div>
    </div>
</template>

<script setup>
import PieChart from './Chart/PieChart.vue';
</script>