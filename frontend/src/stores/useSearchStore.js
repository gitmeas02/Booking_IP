// src/stores/useSearchStore.js
import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useSearchStore = defineStore('search', () => {
  const street = ref('');
  const startDate = ref('');
  const endDate = ref('');
  const rooms = ref(1);
  const adults = ref(2);
  const children = ref(0);
  const capacity = ref(2);
  const pets = ref(false);

  const setSearchParams = (params) => {
    street.value = params.street;
    startDate.value = params.startDate;
    endDate.value = params.endDate;
    rooms.value = params.rooms;
    adults.value = params.adults;
    children.value = params.children;
    capacity.value = params.capacity;
    pets.value = params.pets;
  };

  return {
    street,
    startDate,
    endDate,
    rooms,
    adults,
    children,
    capacity,
    pets,
    setSearchParams,
  };
});
