import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';

export const useAmenitiesStore = defineStore('amenitiesStore', () => {
  const amenitiesData = ref([]);
  const isLoadingAmenities = ref(false);
  const URL = import.meta.env.VITE_API_BASE_URL;

  const getAmenities = async (retries = 3, delay = 1000) => {
    isLoadingAmenities.value = true;
    for (let attempt = 1; attempt <= retries; attempt++) {
      try {
        const res = await axios.get(`${URL}/amenities`);
        if (!Array.isArray(res.data.data)) {
          console.error('Expected array, got:', res.data.data);
          amenitiesData.value = [];
          alert('Invalid amenities data.');
          return;
        }

        amenitiesData.value = res.data.data.map((group) => ({
          ...group,
          amenities: Array.isArray(group.amenities)
            ? group.amenities.map((a) => ({
                ...a,
                selected: false,
              }))
            : [],
        }));
        return;
      } catch (error) {
        console.error(`Attempt ${attempt} failed`, error);
        if (attempt === retries) {
          alert('Failed to load amenities.');
          amenitiesData.value = [];
        } else {
          await new Promise((resolve) => setTimeout(resolve, delay));
        }
      } finally {
        isLoadingAmenities.value = false;
      }
    }
  };

  return {
    amenitiesData,
    isLoadingAmenities,
    getAmenities,
  };
});
