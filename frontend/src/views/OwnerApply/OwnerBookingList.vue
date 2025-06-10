<template>

    <div id="container" class="flex justify-center mr-2 h-full">

        <div id="sidebar" class="flex items-center justify-center">
            <Sidebar />
        </div>

        <div id="bookinglist" class="flex flex-col">
            <div class="rounded-bl-xl rounded-br-xl shadow-lg">
                <WidgetBox />
                <Overview />
            </div>
            
            <ListBooking />

            <div class="flex gap-3 px-15 pt-6">
                <InfoDetailCard />
                <BookingDetailCard />
            </div>
        </div>

    </div>

</template>

<script setup>
import Sidebar from '@/components/OwnerListBooking/Sidebar.vue';
import WidgetBox from '@/components/OwnerListBooking/WidgetBox.vue';
import Overview from '@/components/OwnerListBooking/Overview.vue';
import ListBooking from '@/components/OwnerListBooking/ListBooking.vue';
import InfoDetailCard from '@/components/OwnerListBooking/InfoDetailCard.vue';
import BookingDetailCard from '@/components/OwnerListBooking/BookingDetailCard.vue';

</script>

<style scoped>

</style>