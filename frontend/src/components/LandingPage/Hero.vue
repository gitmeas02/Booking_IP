<!-- src/pages/Home.vue or HeroSection.vue -->
<template>
  <div class="relative w-screen h-[70vh] flex items-center justify-center">
    <img src="../../assets/h-bg.jpg" alt="Hero Background" class="absolute w-screen h-full object-cover" />
    <DatepickerCard class="z-10" @search="handleSearch" />
  </div>
</template>

<script setup>
import DatepickerCard from '@/components/DatePicker/DatepickerCard.vue';
import { useRouter } from 'vue-router';
import { useSearchStore } from '@/stores/useSearchStore'; // Import your Pinia store

const router = useRouter();
const searchStore = useSearchStore(); // Use the store

const handleSearch = (searchParams) => {
  // 1. Save to Pinia store
  searchStore.setSearchParams(searchParams);

  // 2. Navigate with query params
  router.push({
    path: '/listroom',
    query: {
      street: searchParams.street,
      startDate: searchParams.startDate,
      endDate: searchParams.endDate,
      capacity: searchParams.capacity,
      rooms: searchParams.rooms,
      adults: searchParams.adults,
      children: searchParams.children,
      pets: searchParams.pets,
    },
  });
};
</script>
