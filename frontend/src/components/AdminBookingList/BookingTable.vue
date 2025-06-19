<template>
  <div class="mx-6 mb-6">
    <!-- Header Section -->
    <div class="bg-white rounded-t-lg border border-gray-300 border-b-0 px-6 py-4">
      <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800">Owner Hotel Listing</h3>
        <div class="text-center">
          <h4 class="text-lg font-semibold text-gray-800">KAMPOT</h4>
        </div>
        <div class="flex items-center space-x-4">
          <!-- Sort Button -->
          <button class="flex items-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
            </svg>
            <span>SORT</span>
          </button>
          
          <!-- Filter Button -->
          <button class="flex items-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            <span>FILTER</span>
          </button>
          
          <!-- Calendar Icon -->
          <button class="p-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-b-lg border border-gray-300 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <!-- Table Header -->
          <thead class="bg-indigo-900 text-white">
            <tr>
              <th class="text-left py-3 px-4 font-medium">Name</th>
              <th class="text-left py-3 px-4 font-medium">Owner Hotel Id</th>
              <th class="text-left py-3 px-4 font-medium">Locations</th>
              <th class="text-left py-3 px-4 font-medium">Rooms</th>
              <th class="text-left py-3 px-4 font-medium">Fee</th>
              <th class="text-left py-3 px-4 font-medium">Payment Status</th>
              <th class="text-left py-3 px-4 font-medium">Property Name</th>
              <th class="text-left py-3 px-4 font-medium">From</th>
              <th class="text-left py-3 px-4 font-medium">TO</th>
              <th class="text-left py-3 px-4 font-medium">Status</th>
            </tr>
          </thead>
          
          <!-- Table Body -->
          <tbody class="divide-y divide-gray-200">
            <tr 
              v-for="booking in bookings" 
              :key="booking.id"
              class="hover:bg-gray-50"
            >
              <!-- Name Column -->
              <td class="py-3 px-4">
                <div class="flex items-center space-x-3">
                  <img 
                    :src="booking.avatar" 
                    :alt="booking.name"
                    class="w-8 h-8 rounded-full"
                  >
                  <div>
                    <div class="font-medium text-gray-800">{{ booking.name }}</div>
                    <div class="text-sm text-gray-500">{{ booking.email }}</div>
                  </div>
                </div>
              </td>
              
              <!-- Owner Hotel Id -->
              <td class="py-3 px-4 text-gray-700">{{ booking.ownerHotelId }}</td>
              
              <!-- Location -->
              <td class="py-3 px-4 text-gray-700">{{ booking.location }}</td>
              
              <!-- Rooms -->
              <td class="py-3 px-4 text-gray-700">{{ booking.rooms }}</td>
              
              <!-- Fee -->
              <td class="py-3 px-4 text-gray-700">${{ booking.fee }}</td>
              
              <!-- Payment Status -->
              <td class="py-3 px-4">
                <span 
                  :class="{
                    'text-green-600 font-medium': booking.paymentStatus === 'Paid',
                    'text-red-600 font-medium': booking.paymentStatus === 'Unpaid'
                  }"
                >
                  {{ booking.paymentStatus }}
                </span>
              </td>
              
              <!-- Property Name -->
              <td class="py-3 px-4 text-gray-700">{{ booking.propertyName }}</td>
              
              <!-- From -->
              <td class="py-3 px-4 text-gray-700">{{ booking.from }}</td>
              
              <!-- To -->
              <td class="py-3 px-4 text-gray-700">{{ booking.to }}</td>
              
              <!-- Status -->
              <td class="py-3 px-4">
                <button
                  @click="$emit('action-click', booking.status, booking)"
                  :class="getStatusButtonClass(booking.status)"
                  class="px-3 py-1 rounded text-sm font-medium transition-colors duration-200"
                >
                  {{ booking.status }}
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  bookings: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['action-click'])

const getStatusButtonClass = (status) => {
  switch (status) {
    case 'Able to Book':
    case 'Able Book':
      return 'bg-blue-100 text-blue-800 hover:bg-blue-200'
    case 'Unable to Book':
      return 'bg-red-100 text-red-800 hover:bg-red-200'
    case 'Decision':
      return 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200'
    case 'Request':
      return 'bg-orange-100 text-orange-800 hover:bg-orange-200'
    default:
      return 'bg-gray-100 text-gray-800 hover:bg-gray-200'
  }
}
</script>