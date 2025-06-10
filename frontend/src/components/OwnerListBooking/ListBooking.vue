<template>
  <table class="w-full bg-white rounded-lg shadow-md mt-6 text-xs font-semibold">
    <!-- Header Row -->
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

    <!-- Data Rows -->
    <tbody>
      <tr v-for="(booking, index) in bookings" :key="index">
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
        <td
          class="p-2 text-center"
          :class="booking.paymentStatus === 'Paid' ? 'text-green-800' : 'text-red-800'"
        >
          {{ booking.paymentStatus }}
        </td>
        <td class="p-2 text-center">{{ booking.bookingDate }}</td>
        <td class="p-2 text-center">{{ booking.checkIn }}</td>
        <td class="p-2 text-center">{{ booking.checkOut }}</td>
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

const bookings = ref([
  {
    name: 'John Doe',
    email: 'JohnDoe@gmail.com',
    bookingId: 'BK001',
    roomId: 'RM101',
    price: 40,
    paymentStatus: 'Paid',
    bookingDate: '14 June 2025',
    checkIn: '16 June 2025',
    checkOut: '20 June 2025',
    status: 'Pending'
  },
  {
    name: 'Jane Smith',
    email: 'JaneSmith@gmail.com',
    bookingId: 'BK002',
    roomId: 'RM102',
    price: 50,
    paymentStatus: 'Paid',
    bookingDate: '15 June 2025',
    checkIn: '18 June 2025',
    checkOut: '22 June 2025',
    status: 'Pending'
  },
  {
    name: 'Mike Johnson',
    email: 'MikeJ@gmail.com',
    bookingId: 'BK003',
    roomId: 'RM103',
    price: 60,
    paymentStatus: 'Unpaid',
    bookingDate: '17 June 2025',
    checkIn: '19 June 2025',
    checkOut: '23 June 2025',
    status: 'Pending'
  }
])
</script>
