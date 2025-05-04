<template>
    <HistoryBar/>
    <div class="flex items-center mb-6 justify-start pt-10 pl-6 lg:pl-32">
        <h2 class="text-xl font-semibold text-gray-700 mr-4">List Booking</h2>
        <div class="grid w-auto grid-cols-2 bg-gray-100 justify-center items-center text-center p-1 rounded-sm">	
        <button  :class="[
        'text-center pl-2 pr-2 pt-1 pb-1 w-auto rounded-sm',
        activeTab === 'current'
        ? 'bg-white text-blue-900 font-bold'
        : 'text-gray-600'
        ]"@click="activeTab = 'current'" >Current → Future</button>
        
        <button  :class="[
        'text-center pl-2 pr-2 pt-1 pb-1 w-auto rounded-sm',
        activeTab === 'history'
        ? 'bg-white text-blue-900 font-bold'
        : 'text-gray-600']"
        @click="activeTab = 'history'">History</button>
        </div>
    </div>
    <Current_Future_Past :active-tab="activeTab"/>
</template>
<script setup>
import { ref } from "vue";

import HistoryBar from "@/components/History/HistoryBar.vue";
import Current_Future_Past from "@/components/History/Current_Future_Past_Component.vue";
const activeTab = ref('current');

</script>