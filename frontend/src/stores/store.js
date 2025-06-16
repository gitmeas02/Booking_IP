import axios from "axios";
import { defineStore } from "pinia";

export const useRoomStore = defineStore("room", {
  state: () => ({
    hotels: [],
    properties: [],
  }),

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
  },
});

 // getters: {
  //   getRoomsByHotelId: (state) => (hotelId) => {
  //     return state.rooms.filter((room) => room.hotelId === hotelId);
  //   },
  //   getRoomById: (state) => (id) => {
  //     return state.rooms.find((room) => room.id === id);
  //   },
  //   getHotelById: (state) => (id) => {
  //     return state.hotels.find((hotel) => hotel.id === id);
  //   },
  //   searchRoomBy_name_hotelName_range_date: (state) => (name, hotelName, start_date, end_date) => {
  //     return state.rooms.filter((room) => {
  //       const matchName = room.name.toLowerCase().includes(name.toLowerCase());
  //       const matchHotel = state.hotels.find(h => h.id === room.hotelId && h.name.toLowerCase().includes(hotelName.toLowerCase()));
  //       const matchDate = room.availableDates?.some(date => {
  //         const dateObj = new Date(date);
  //         return dateObj >= new Date(start_date) && dateObj <= new Date(end_date);
  //       });
  //       return matchName && matchHotel && matchDate;
  //     });
  //   }
  // }