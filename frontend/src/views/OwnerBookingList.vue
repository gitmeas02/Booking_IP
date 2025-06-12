<template>
  <div id="container" class="flex justify-center mr-50 h-screen">
    <div id="sidebar" class="flex items-center justify-center h-screen">
      <Sidebar />
    </div>
    <div id="bookinglist" class="flex flex-col w-screen">
      <div class="rounded-bl-xl rounded-br-xl shadow-lg">
        <WidgetBox />
        <Overview />
      </div>
      <ListBooking @select="handleSelectBooking" />

      <!-- Modal Overlay for both cards -->
      <div v-if="showBookingDetail && selectedBooking"
        class="fixed inset-0 bg-opacity-40 flex items-center justify-center z-50">
        <div class="relative bg-white flex flex-col md:flex-row gap-6 border rounded-xl px-8 py-6 w-[90vw] max-w-5xl">
          <!-- Close Button -->
          <button class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-2xl" @click="closeBookingDetail"
            aria-label="Close">&#10005;</button>
          <InfoDetailCard :booking="selectedBooking" />
          <BookingDetailCard :booking="selectedBooking" @close="closeBookingDetail" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Sidebar from '@/components/OwnerListBooking/Sidebar.vue';
import WidgetBox from '@/components/OwnerListBooking/WidgetBox.vue';
import Overview from '@/components/OwnerListBooking/Overview.vue';
import ListBooking from '@/components/OwnerListBooking/ListBooking.vue';
import InfoDetailCard from '@/components/OwnerListBooking/InfoDetailCard.vue';
import BookingDetailCard from '@/components/OwnerListBooking/BookingDetailCard.vue';

const selectedBooking = ref(null);
const showBookingDetail = ref(false);

function handleSelectBooking(booking) {
  selectedBooking.value = booking;
  showBookingDetail.value = true;
}
function closeBookingDetail() {
  showBookingDetail.value = false;
  selectedBooking.value = null;
}
</script>

<style scoped></style>