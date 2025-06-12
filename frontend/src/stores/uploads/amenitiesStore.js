import axios from 'axios';
import { defineStore } from 'pinia';


export const useAmenitiesStore = defineStore('amenities', {
  state: () => ({
    amenitiesData: [],
    isLoadingAmenities: false,
    error: null,
  }),
  actions: {
    async getAmenities() {
      this.isLoadingAmenities = true;
      this.error = null;
      try {
        const response = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/amenities`);
        const result = response.data.data;
        this.amenitiesData = result.map((group) => ({
          id: group.id,
          name: group.name,
          amenities: Array.isArray(group.amenities)
            ? group.amenities.map((a) => ({
                id: a.id,
                amenity_name: a.amenity_name,
                selected: false,
              }))
            : [],
        }));
      } catch (error) {
        this.error = 'Failed to fetch amenities';
        console.error('Error fetching amenities:', error);
      } finally {
        this.isLoadingAmenities = false;
      }
    },
  },
});