// stores/store.js
import { defineStore } from "pinia";
import axios from "axios";

export const useRoomStore = defineStore("room", {
  state: () => ({
    hotels: [],
    properties: [],
    rooms:[]
  }),

  getters: {
    getHouseById: (state) => {
      return (id) => state.hotels.find(h => h.id === Number(id));
    },
    getRoomThrowHoteslId:(state)=>{
      return (id)=>state.hotels.find(h=> h.id === Number(id)).rooms; // get rooms by hotel id
    },
    getRoomById:(state)=>{
      return (id)=>state.rooms.find(r=> r.id=== Number(id)); // get room by id
    }
  },


  actions: {
    async fetchHotels() {
      this.properties = [
        { name: "Hotel", count: 123 },
        { name: "Apartment", count: 123 },
        { name: "Guest House", count: 123 },
        { name: "Villa", count: 85 },
        { name: "Resort", count: 45 },
        { name: "Hostel", count: 12 },
      ];
      try {
        const response = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/allhouse`);
        this.hotels = response.data;
        return response.data;
      } catch (error) {
        console.error("Error fetching hotels:", error);
        throw error;
      }
    },
  }
});
