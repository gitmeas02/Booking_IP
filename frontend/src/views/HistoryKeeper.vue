<template>
    <HistoryBar/>
    <div class="flex items-center mb-6 justify-start pt-10 pl-6 lg:pl-32">
        <h2 class="text-xl font-semibold text-gray-700 mr-4">List Booking</h2>
        <div class="grid w-auto grid-cols-2 bg-gray-100 justify-center items-center text-center p-1 rounded-sm">	
            <button class="text-center pl-2 pr-2 pt-1 pb-1 bg-white w-auto rounded-sm text-blue-900 font-bold">Current → Future</button>
            <button class="text-center pl-2 pr-2 pt-1 pb-1">History</button>
        </div>
    </div>
    <Current_Future_Past/>
</template>
<script setup>
import HistoryBar from "@/components/HistoryBar.vue";
import Current_Future_Past from "@/components/Current_Future_Past.vue";
</script>