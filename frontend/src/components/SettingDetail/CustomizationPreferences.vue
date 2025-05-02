<template>
  <div class="bg-white rounded-md font-sans">
    <!-- Header -->
    <div class="flex flex-col px-6 pt-6">
      <h2 class="text-xl font-semibold mb-1">Customization preferences</h2>
      <p class="text-gray-500 mb-4">Personalize your account to meet your needs.</p>
    </div>

    <!-- Currency Row -->
    <div class="flex items-center justify-between border-t border-gray-200 px-6 py-4 gap-4 flex-wrap sm:flex-nowrap">
      <span class="font-medium flex-1">Currency</span>
      <span class="text-gray-600 flex-1 text-left">$$$</span>
      <button class="text-blue-600 font-medium hover:underline w-full sm:w-auto text-left sm:text-right mt-2 sm:mt-0">
        Edit
      </button>
    </div>

    <!-- Language Row -->
    <div class="flex items-center justify-between border-t border-gray-200 px-6 py-4 gap-4 flex-wrap sm:flex-nowrap">
      <span class="font-medium flex-1">Language</span>
      <span class="text-gray-600 flex-1 text-left">American English</span>
      <button class="text-blue-600 font-medium hover:underline w-full sm:w-auto text-left sm:text-right mt-2 sm:mt-0">
        Edit
      </button>
    </div>
  </div>
</template>

<script setup>
// No logic yet
</script>
