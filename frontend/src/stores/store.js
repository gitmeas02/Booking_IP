import { defineStore } from "pinia";
import axios from "axios";

export const useRoomStore = defineStore("room", {
  state: () => ({
    properties: [],
    hotels: [],
    currentHotel: null,
  }),

  getters: {
    getHotelById: (state) => {
      return (id) => state.hotels.find((h) => h.id === Number(id));
    },
    getRoomsByHotelId: (state) => {
      return (id) => {
        const hotel = state.hotels.find((h) => h.id === Number(id));
        return hotel ? hotel.rooms || [] : [];
      };
    },
    getRoomById: (state) => {
      return (id) => {
        if (state.currentHotel && state.currentHotel.room_types) {
          return state.currentHotel.room_types.find((r) => r.id === Number(id));
        }
        return null;
      };
    },
    getCurrentHotel: (state) => state.currentHotel,
  },

  actions: {
    async fetchHotels(filters = {}) {
      // filters is an object containing search params
      try {
        const params = {
          street: filters.street || '',
          startDate: filters.startDate || '',
          endDate: filters.endDate || '',
          capacity: filters.capacity || '',
          pets: filters.pets || false,
        };
        const response = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/allhouse`, { params });
        this.hotels = response.data;
        return response.data;
      } catch (error) {
        console.error("Error fetching hotels:", error);
        throw error;
      }
    },

    async fetchHotelById(id) {
      try {
        const response = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/house/${id}`);
        this.currentHotel = response.data; // Store the fetched hotel in currentHotel
        return response.data;
      } catch (error) {
        console.error(`Error fetching hotel ${id}:`, error);
        throw error;
      }
    },
  },
});
