<template>
    <div class="flex flex-col items-start self-start h-fit px-12 gap-6">
        <h1 class="text-2xl py-4">List Booking</h1>

        <div class="flex gap-5">
            <!-- Total Customers -->
            <div class="flex flex-col items-center justify-center bg-[#703434] px-5 py-2 rounded shadow-md w-45">
                <div class="flex items-center justify-between text-white gap-2">
                    <Users class="text-white w-4" />
                    Total Customers
                </div>
                <div class="text-white text-xl font-bold">
                    {{ stats.totalCustomers }}
                    <span class="text-green-400 text-sm ml-1">↑ {{ stats.totalCustomersChange }}%</span>
                </div>
            </div>

            <!-- Check In -->
            <div class="flex flex-col items-center justify-center px-5 py-2 rounded shadow-md w-45">
                <div class="flex items-center justify-between gap-2">
                    <Users class="w-4" />
                    Check In
                </div>
                <div class="text-xl font-bold">
                    {{ stats.checkIn }}
                    <span class="text-red-500 text-sm ml-1">↓ {{ stats.checkInChange }}%</span>
                </div>
            </div>

            <!-- Check Out -->
            <div class="flex flex-col items-center justify-center px-5 py-2 rounded shadow-md w-45">
                <div class="flex items-center justify-between gap-2">
                    <Users class="w-4" />
                    Check Out
                </div>
                <div class="text-xl font-bold">
                    {{ stats.checkOut }}
                    <span class="text-red-500 text-sm ml-1">↓ {{ stats.checkOutChange }}%</span>
                </div>
            </div>

            <!-- Search / Sort / Filter -->
            <div class="flex items-center justify-center px-5 rounded shadow-md gap-2">
                <div class="flex items-center justify-between border h-11 px-2 text-base rounded-lg">
                    <input type="text" class="rounded px-2 py-1 w-30 focus:outline-none focus:border-transparent"
                        placeholder="Search" />
                    <Search class="text-gray-500" />
                </div>

                <button class="flex items-center justify-between border h-11 px-2 w-25 text-base rounded-lg">
                    <ArrowDownUp class="text-gray-500 w-5" />
                    SORT
                </button>
                <button class="flex items-center justify-between border h-11 px-2 w-25 text-base rounded-lg">
                    <ListFilter class="text-gray-500 w-5" />
                    FILTER
                </button>
                <button class="">
                    <CalendarDays class="text-gray-500 w-10 h-10" />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Users, ArrowDownUp, ListFilter, CalendarDays, Search } from 'lucide-vue-next'

const stats = ref({
    totalCustomers: 183958,
    totalCustomersChange: 24,
    checkIn: 1000,
    checkInChange: 10,
    checkOut: 100,
    checkOutChange: 10
})
</script>
