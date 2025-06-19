<template>
  <table class="w-full bg-white rounded-lg shadow-md mt-6 text-xs font-semibold">
    <thead>
      <tr class="bg-[#572222] text-white">
        <th class="p-5 text-center flex items-center gap-2">
          <Circle class="w-6 h-6" />
          Customer Name
        </th>
        <th class="p-5 text-center">Booking ID</th>
        <th class="p-5 text-center">Room ID</th>
        <th class="p-5 text-center">Price</th>
        <th class="p-5 text-center">Payment Status</th>
        <th class="p-5 text-center">Booking Date</th>
        <th class="p-5 text-center">Check-in</th>
        <th class="p-5 text-center">Check-out</th>
        <th class="p-5 text-center">Status</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(booking, index) in bookings" :key="index" @click="$emit('select', booking)"
        class="cursor-pointer hover:bg-gray-100">
        <td class="p-2 flex items-center gap-2">
          <Circle class="w-10 h-10" />
          <div class="flex flex-col text-xs">
            <div>{{ booking.name }}</div>
            <div>{{ booking.email }}</div>
          </div>
        </td>
        <td class="p-2 text-center">{{ booking.bookingId }}</td>
        <td class="p-2 text-center">{{ booking.roomId }}</td>
        <td class="p-2 text-center">${{ booking.price }}</td>
        <td class="p-2 text-center" :class="booking.paymentStatus === 'Paid' ? 'text-green-800' : 'text-red-800'">
          {{ booking.paymentStatus }}
        </td>
        <td class="p-2 text-center">{{ booking.bookingDate }}</td>
        <td class="p-2 text-center">
          {{ booking.checkIn.date }}<br />
          <span class="text-xs text-gray-500">{{ booking.checkIn.time }}</span>
        </td>
        <td class="p-2 text-center">
          {{ booking.checkOut.date }}<br />
          <span class="text-xs text-gray-500">{{ booking.checkOut.time }}</span>
        </td>
        <td class="p-2 text-center">
          <button class="border border-blue-950 bg-blue-200 text-blue-950 rounded-3xl w-21 text-center p-1">
            {{ booking.status }}
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script setup>
import { ref } from 'vue'
import { Circle } from 'lucide-vue-next'

// Add all fields needed for InfoDetailCard and BookingDetailCard
const bookings = ref([
  {
    name: 'John Doe',
    email: 'JohnDoe@gmail.com',
    bookingId: 'BK001',
    bookingAt: '2025-Feb-15 12:00 am',
    roomId: 'RM101',
    price: 40,
    paymentStatus: 'Paid',
    bookingDate: '14 June 2025',
    checkIn: {
      date: 'Sat, Mar 8, 2025',
      time: '2:00 PM - 12:00 AM'
    },
    checkOut: {
      date: 'Sun, Mar 12, 2025',
      time: '5:00 AM - 12:30 PM'
    },
    nights: 3,
    roomCount: 1,
    guests: 2,
    status: 'Pending',
    hotelName: 'KHUN HOTEL',
    roomType: 'King Bed',
    rating: 4.9,
    reviews: 32,
    image: 'https://via.placeholder.com/96x96.png?text=Bed',
    priceDetails: {
      original: { nights: 1, amount: 43 },
      stay: { nights: 3, amount: 129 },
      bookingFeePercent: 10
    },
    total: 129.35,
    paid: 129.35
  },
  {
    name: 'Jane Smith',
    email: 'JaneSmith@gmail.com',
    bookingId: 'BK002',
    bookingAt: '2025-Feb-15 12:00 am',
    roomId: 'RM102',
    price: 50,
    paymentStatus: 'Paid',
    bookingDate: '15 June 2025',
    checkIn: {
      date: 'Mon, Mar 10, 2025',
      time: '3:00 PM - 11:00 PM'
    },
    checkOut: {
      date: 'Fri, Mar 14, 2025',
      time: '6:00 AM - 11:30 AM'
    },
    nights: 4,
    roomCount: 2,
    guests: 3,
    status: 'Confirmed',
    hotelName: 'SUN HOTEL',
    roomType: 'Queen Bed',
    rating: 4.7,
    reviews: 21,
    image: 'https://via.placeholder.com/96x96.png?text=Bed',
    priceDetails: {
      original: { nights: 1, amount: 50 },
      stay: { nights: 4, amount: 200 },
      bookingFeePercent: 10
    },
    total: 220,
    paid: 220
  },
  {
    name: 'Mike Johnson',
    email: 'MikeJ@gmail.com',
    bookingId: 'BK003',
    bookingAt: '2025-Feb-15 12:00 am',
    roomId: 'RM103',
    price: 60,
    paymentStatus: 'Unpaid',
    bookingDate: '17 June 2025',
    checkIn: {
      date: 'Wed, Mar 12, 2025',
      time: '1:00 PM - 10:00 PM'
    },
    checkOut: {
      date: 'Sat, Mar 15, 2025',
      time: '7:00 AM - 12:00 PM'
    },
    nights: 3,
    roomCount: 1,
    guests: 1,
    status: 'Pending',
    hotelName: 'MOON HOTEL',
    roomType: 'Single Bed',
    rating: 4.5,
    reviews: 15,
    image: 'https://via.placeholder.com/96x96.png?text=Bed',
    priceDetails: {
      original: { nights: 1, amount: 60 },
      stay: { nights: 3, amount: 180 },
      bookingFeePercent: 10
    },
    total: 198,
    paid: 0
  }
])
</script>