<!-- src/pages/Hero.vue or Home.vue -->
<template>
  <div class="relative w-screen h-[70vh] flex items-center justify-center">
    <img src="../../assets/h-bg.jpg" alt="Hero Background" class="absolute w-screen h-full object-cover" />
    <DatepickerCard class="z-10" @search="handleSearch" />
  </div>
</template>

<script setup>
import DatepickerCard from '@/components/DatePicker/DatepickerCard.vue';
import { useRouter } from 'vue-router';
import { useSearchStore } from '@/stores/useSearchStore';

const router = useRouter();
const searchStore = useSearchStore();

const handleSearch = (searchParams) => {
  // Save to Pinia store
  searchStore.setSearchParams(searchParams);

  // Navigate to listroom with query params
  router.push({
    path: '/listroom',
    query: {
      street: searchParams.street || '',
      startDate: searchParams.startDate || '',
      endDate: searchParams.endDate || '',
      capacity: searchParams.capacity || 2,
      rooms: searchParams.rooms || 1,
      adults: searchParams.adults || 1,
      children: searchParams.children || 0,
      pets: searchParams.pets ? 'true' : 'false',
    },
  });
};
</script>
