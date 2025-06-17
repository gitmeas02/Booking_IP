<template>
  <div class="mx-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <!-- Total Customer Card -->
      <div class="bg-white rounded-lg border border-gray-300 p-4">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
          <div class="flex-1">
            <p class="text-sm text-gray-600">Total Customer</p>
            <div class="flex items-center space-x-2">
              <span class="text-xl font-semibold text-gray-800">{{ formatNumber(stats.totalCustomer.value) }}</span>
              <span class="text-sm text-green-500 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                {{ stats.totalCustomer.change }}%
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Fee Today Card -->
      <div class="bg-red-800 text-white rounded-lg p-4">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-red-700 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
          </div>
          <div class="flex-1">
            <p class="text-sm text-red-100">Fee today</p>
            <div class="flex items-center space-x-2">
              <span class="text-xl font-semibold">{{ stats.feeToday.value }}</span>
              <span class="text-sm text-red-300 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                {{ stats.feeToday.change }}%
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Unpaid Card -->
      <div class="bg-white rounded-lg border border-gray-300 p-4">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </div>
          <div class="flex-1">
            <p class="text-sm text-gray-600">Unpaid</p>
            <div class="flex items-center space-x-2">
              <span class="text-xl font-semibold text-gray-800">{{ stats.unpaid.value }}</span>
              <span class="text-sm text-red-500 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                {{ stats.unpaid.change }}%
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Paid Card -->
      <div class="bg-white rounded-lg border border-gray-300 p-4">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <div class="flex-1">
            <p class="text-sm text-gray-600">paid</p>
            <div class="flex items-center space-x-2">
              <span class="text-xl font-semibold text-gray-800">{{ stats.paid.value }}</span>
              <span class="text-sm text-green-500 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                {{ stats.paid.change }}%
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  stats: {
    type: Object,
    required: true
  }
})

const formatNumber = (num) => {
  return num.toLocaleString()
}
</script>