<template>
    <div class="flex gap-10">
        <div v-for="(card, idx) in cards" :key="idx"
            class="flex flex-col gap-3 border border-gray-500 rounded-xl px-6 py-3">
            <div class="flex justify-between">
                <div id="agbalumo" class="text-xl">{{ card.title }}</div>
                <div class="flex">
                    <div id="agbalumo" class="flex items-center px-2 py-1 bg-[#37474F] text-white rounded-2xl text-xs">
                        {{ card.badge }}
                    </div>
                    <MessageCircleQuestion class="flex items-center" />
                </div>
            </div>
            <div class="flex items-center px-12 text-xl font-bold">
                {{ card.value }}
                <span class="flex items-center text-green-600 text-base font-semibold">
                    <MoveUp class="w-3 h-4" />
                    {{ card.percent }}
                </span>
            </div>
            <div class="flex gap-3">
                <div class="flex flex-col text-base gap-3">
                    <div v-if="card.month !== undefined">
                        <div class="text-gray-500">THIS MONTH</div>
                        <div class="font-bold">{{ card.month }}</div>
                    </div>
                    <div v-if="card.week !== undefined">
                        <div class="text-gray-500">THIS WEEK</div>
                        <div class="font-bold">{{ card.week }}</div>
                    </div>
                </div>
                <div>
                    <BarChart :chart-data="card.chartData" class="w-50 h-35" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import BarChart from './Chart/BarChart.vue';
import { MessageCircleQuestion, MoveUp } from 'lucide-vue-next';

defineProps({
    cards: {
        type: Array,
        required: true,
    },
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Agbalumo&display=swap');

#agbalumo {
    font-family: "Agbalumo", system-ui;
    font-weight: 400;
    font-style: normal;
}
</style>