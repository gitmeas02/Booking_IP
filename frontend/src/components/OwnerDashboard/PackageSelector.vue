<template>
    <div class="flex gap-5">
        <div class="flex flex-col gap-3 border border-gray-500 rounded-xl px-6 py-3">
            <div class="flex justify-between">
                <div id="agbalumo" class="text-xl justify-between">Total Booking</div>
                <div class="flex">
                    <div id="agbalumo" class="flex items-center px-2 py-1 bg-[#37474F] text-white rounded-2xl text-xs">
                        Total Booking</div>
                    <MessageCircleQuestion class="flex items-center" />
                </div>
            </div>
            <div class="flex items-center px-12 text-xl font-bold">
                11,230
                <span class="flex items-center text-green-600 text-base font-semibold">
                    <MoveUp class="w-3 h-4"/>
                    24%
                </span>
            </div>
            <div class="flex gap-3">
                <div class="flex flex-col text-base gap-3">
                    <div class="">
                        <div class="text-gray-500">THIS MONTH</div>
                        <div class="font-bold">12887</div>
                    </div>
                    <div>
                        <div class="text-gray-500">THIS WEEK</div>
                        <div class="font-bold">12887</div>
                    </div>
                </div>
                <div>
                    <BarChart class="w-50 h-35" />
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-3 border border-gray-500 rounded-xl px-6 py-3">
            <div class="flex justify-between">
                <div id="agbalumo" class="text-xl justify-between">Total Booking</div>
                <div class="flex">
                    <div id="agbalumo" class="flex items-center px-2 py-1 bg-[#37474F] text-white rounded-2xl text-xs">
                        Total Booking</div>
                    <MessageCircleQuestion class="flex items-center" />
                </div>
            </div>
            <div class="flex items-center px-12 text-xl font-bold">
                11,230
                <span class="flex items-center text-green-600 text-base font-semibold">
                    <MoveUp class="w-3 h-4"/>
                    24%
                </span>
            </div>
            <div class="flex gap-3">
                <div class="flex flex-col text-base gap-3">
                    <div class="">
                        <div class="text-gray-500">THIS MONTH</div>
                        <div class="font-bold">12887</div>
                    </div>
                    <div>
                        <div class="text-gray-500">THIS WEEK</div>
                        <div class="font-bold">12887</div>
                    </div>
                </div>
                <div>
                    <BarChart class="w-50 h-35" />
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-3 border border-gray-500 rounded-xl px-6 py-3">
            <div class="flex justify-between">
                <div id="agbalumo" class="text-xl justify-between">Total Booking</div>
                <div class="flex">
                    <div id="agbalumo" class="flex items-center px-2 py-1 bg-[#37474F] text-white rounded-2xl text-xs">
                        Total Booking</div>
                    <MessageCircleQuestion class="flex items-center" />
                </div>
            </div>
            <div class="flex items-center px-12 text-xl font-bold">
                11,230
                <span class="flex items-center text-green-600 text-base font-semibold">
                    <MoveUp class="w-3 h-4"/>
                    24%
                </span>
            </div>
            <div class="flex gap-3">
                <div class="flex flex-col text-base gap-3">
                    <div class="">
                        <div class="text-gray-500">THIS MONTH</div>
                        <div class="font-bold">12887</div>
                    </div>
                    <div>
                        <div class="text-gray-500">THIS WEEK</div>
                        <div class="font-bold">12887</div>
                    </div>
                </div>
                <div>
                    <BarChart class="w-50 h-35" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import BarChart from './Chart/BarChart.vue';
import { MessageCircleQuestion, MoveUp } from 'lucide-vue-next';
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Agbalumo&display=swap');

#agbalumo {
    font-family: "Agbalumo", system-ui;
    font-weight: 400;
    font-style: normal;
}
</style>