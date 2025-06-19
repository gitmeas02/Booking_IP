<template>
  <div class="bg-gray-200 px-6 py-4">
    <div class="flex items-center space-x-4">
      <!-- Manage Hotel Dropdown -->
      <div class="relative">
        <button
          @click="toggleDropdown('manageHotel')"
          class="bg-white px-4 py-2 rounded-lg border border-gray-300 flex items-center space-x-2 text-gray-700 hover:bg-gray-50"
        >
          <span>Manage Hotel</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div
          v-if="dropdowns.manageHotel"
          class="absolute top-full left-0 mt-1 w-48 bg-white border border-gray-300 rounded-lg shadow-lg z-10"
        >
          <div class="py-1">
            <button
              v-for="option in manageHotelOptions"
              :key="option"
              @click="selectOption('manageHotel', option)"
              class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100"
            >
              {{ option }}
            </button>
          </div>
        </div>
      </div>

      <!-- Location Dropdown -->
      <div class="relative">
        <button
          @click="toggleDropdown('location')"
          class="bg-white px-4 py-2 rounded-lg border border-gray-300 flex items-center space-x-2 text-gray-700 hover:bg-gray-50"
        >
          <span>Location</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div
          v-if="dropdowns.location"
          class="absolute top-full left-0 mt-1 w-48 bg-white border border-gray-300 rounded-lg shadow-lg z-10"
        >
          <div class="py-1">
            <button
              v-for="option in locationOptions"
              :key="option"
              @click="selectOption('location', option)"
              class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100"
            >
              {{ option }}
            </button>
          </div>
        </div>
      </div>

      <!-- Amount Dropdown -->
      <div class="relative">
        <button
          @click="toggleDropdown('amount')"
          class="bg-white px-4 py-2 rounded-lg border border-gray-300 flex items-center space-x-2 text-gray-700 hover:bg-gray-50"
        >
          <span>Amount</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        <div
          v-if="dropdowns.amount"
          class="absolute top-full left-0 mt-1 w-48 bg-white border border-gray-300 rounded-lg shadow-lg z-10"
        >
          <div class="py-1">
            <button
              v-for="option in amountOptions"
              :key="option"
              @click="selectOption('amount', option)"
              class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100"
            >
              {{ option }}
            </button>
          </div>
        </div>
      </div>

      <!-- Message Button -->
      <button
        @click="$emit('message-click')"
        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium"
      >
        Message
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const emit = defineEmits(['manage-hotel', 'location-change', 'amount-change', 'message-click'])

// Dropdown state
const dropdowns = ref({
  manageHotel: false,
  location: false,
  amount: false
})

// Dropdown options
const manageHotelOptions = ref(['All Hotels', 'Khun Hotel', 'Luxury Resort', 'Budget Inn'])
const locationOptions = ref(['All Locations', 'Kampot', 'Phnom Penh', 'Siem Reap', 'Sihanoukville'])
const amountOptions = ref(['All Amounts', '$0 - $50', '$50 - $100', '$100 - $200', '$200+'])

// Methods
const toggleDropdown = (dropdown) => {
  // Close all other dropdowns
  Object.keys(dropdowns.value).forEach(key => {
    if (key !== dropdown) {
      dropdowns.value[key] = false
    }
  })
  // Toggle the clicked dropdown
  dropdowns.value[dropdown] = !dropdowns.value[dropdown]
}

const selectOption = (dropdown, option) => {
  dropdowns.value[dropdown] = false
  
  switch (dropdown) {
    case 'manageHotel':
      emit('manage-hotel', option)
      break
    case 'location':
      emit('location-change', option)
      break
    case 'amount':
      emit('amount-change', option)
      break
  }
}

const closeDropdowns = (event) => {
  if (!event.target.closest('.relative')) {
    Object.keys(dropdowns.value).forEach(key => {
      dropdowns.value[key] = false
    })
  }
}

onMounted(() => {
  document.addEventListener('click', closeDropdowns)
})

onUnmounted(() => {
  document.removeEventListener('click', closeDropdowns)
})
</script>