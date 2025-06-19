<template>

  <div class="relative bg-white flex flex-col gap-3 border rounded-xl px-8 py-4 w-[28rem] max-w-full">
    <h1 class="text-base font-bold">Booking Details</h1>
    <div class="flex border rounded-xl gap-2">
      <img :src="booking.image" alt="" class="w-24 h-24 rounded-xl">
      <div class="flex flex-col justify-center">
        <div>{{ booking.hotelName }}</div>
        <div>{{ booking.roomType }}</div>
        <div>⭐ {{ booking.rating }} ({{ booking.reviews }} Review)</div>
      </div>
    </div>
    <div class="font-bold">Room Type: <span class="text-[#703434]">{{ booking.roomType }}</span></div>
    <div class="flex justify-between">
      <div>
        <div>Check-in</div>
        <div class="flex text-2xl font-bold wrap-break-word">{{ booking.checkIn.date }}</div>
        <div>{{ booking.checkIn.time }}</div>
      </div>
      <div class="border-l border-gray-500 pr-2 ml-2"></div>
      <div>
        <div>Check-out</div>
        <div class="flex text-2xl font-bold wrap-break-word">{{ booking.checkOut.date }}</div>
        <div>{{ booking.checkOut.time }}</div>
      </div>
    </div>
    <div>Total length of stay:</div>
    <div class="text-xl font-bold">{{ booking.nights }} night</div>
    <div class="border-b border-gray-500"></div>
    <div class="text-xl font-bold">{{ booking.roomCount }} room for {{ booking.guests }} adults</div>
  </div>

</template>

<script setup>
import { ref } from 'vue'
defineProps({ booking: Object });
// const booking = ref({
//   hotelName: "KHUN HOTEL",
//   roomType: "King Bed",
//   rating: 4.9,
//   reviews: 32,
//   image: new URL('../../assets/Bed/bed.png', import.meta.url).href,
//   checkIn: {
//     date: "Sat, Mar 8, 2025",
//     time: "2:00 PM - 12:00 AM"
//   },
//   checkOut: {
//     date: "Sun, Mar 12, 2025",
//     time: "5:00 AM - 12:30 PM"
//   },
//   nights: 3,
//   roomCount: 1,
//   guests: 2
// })
</script>
