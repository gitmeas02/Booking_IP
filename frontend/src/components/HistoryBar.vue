<script setup>
import {History} from 'lucide-vue-next';
import { Calendar } from 'lucide-vue-next';
</script>
<template>
    <header class="bg-white border-b border-gray-200 py-4  pl-6 lg:pl-32 lg:pr-32">
      <div class="container mx-auto flex justify-between items-center px-4">
        <div class="flex items-center space-x-3">
          <History class="h-6 w-6 text-hotel-primary" />
          <span class="font-bold text-xl text-hotel-primary">Hotel History Keeper</span>
        </div>
        
        <div class="flex space-x-4">
          <button class="flex items-center gap-2 border border-gray-300 rounded-md px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">
            <Calendar class="h-4 w-4" />
            <span>My Bookings</span>
          </button>
          <button class="border border-gray-300 rounded-md px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">
            <span>New Booking</span>
          </button>
        </div>
      </div>
    </header>
</template>