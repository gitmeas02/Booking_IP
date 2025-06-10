<template>

    <div class="flex flex-col justify-between p-4 border-r border-black w-82 h-[100%]">

        
        <div>

            <div id="agbalumo" class="flex items-center justify-center  text-2xl font-bold mb-4">
            Admin Dashboard
            </div>

            <div id="agbalumo" class="">
                Analysis Dashboard
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center text-xl">
                    <Book class="text-gray-500" />
                    Booking
                </div>
                <ChevronRight class="text-gray-800" />
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center text-xl">
                    <House class="text-gray-500" />
                    Room
                </div>
                <ChevronRight class="text-gray-800" />
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center text-xl">
                    <MessageSquare class="text-gray-500" />
                    Message
                </div>
                <ChevronRight class="text-gray-800" />
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center text-xl">
                    <CalendarDays class="text-gray-500" />
                    Calendar
                </div>
                <ChevronRight class="text-gray-800" />
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center text-xl">
                    <CircleDollarSign class="text-gray-500" />
                    Payment
                </div>
                <ChevronRight class="text-gray-800" />
            </div>
            
        </div>
        
        <div>
            <div class="flex items-center justify-between">
                <div class="flex items-center text-xl">
                    <Headset class="text-gray-500" />
                    Support
                </div>
                <ChevronRight class="text-gray-800" />
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center text-xl">
                    <Settings class="text-gray-500" />
                    Settings
                </div>
                <ChevronRight class="text-gray-800" />
            </div>
        </div>
        
        

    </div>

</template>

<script setup>

import { Book, House, MessageSquare, ChevronRight, CalendarDays, CircleDollarSign, Headset, Settings } from 'lucide-vue-next';

</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Agbalumo&display=swap');

#agbalumo {
    font-family: "Agbalumo", system-ui;
    font-weight: 400;
    font-style: normal;
}
</style>