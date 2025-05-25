// stores/search.js
import { defineStore } from 'pinia'

export const useSearchStore = defineStore('search', {
  state: () => ({
    rooms: [],
    destination: null,
    startDate: null,
    endDate: null,
    adults: 1,
    children: 0,
    withPet: false,
  }),

  actions: {
    async fetchRoom() {
      const hotels = [
        {
          id: 1,
          name: "Hotel 1",
          stars: 5,
          reviewScore: 4.5,
          commentsCount: 10,
          location_image: "url",
          location: { city: "Phnom Penh", distanceFromCenter: "1.7 km" },
          comments: ["Free breakfast", "Free Wi-Fi"],
          price: 100,
          rooms: [
            {
              id: 1,
              name: "Room 1",
              size: "40m²",
              beds: "1 bed",
              images: ["./src/assets/Bed/bed.png"],
              price: 100,
              rating: 4.5,
              reviews: 10,
              type: "King Bed",
              details: "This is a king bed room with a beautiful view.",
              amenities: ["Air Conditioning", "TV", "Mini Bar", "Free Breakfast", "Free Wi-Fi"],
            }
          ],
          amenities: ["Air Conditioning", "TV", "Mini Bar", "Free Breakfast", "Free Wi-Fi"],
        },
        {
          id: 2,
          name: "Hotel 2",
          stars: 4,
          reviewScore: 4.2,
          commentsCount: 25,
          location_image: "url",
          location: { city: "Siem Reap", distanceFromCenter: "2.3 km" },
          comments: ["Great location", "Friendly staff"],
          price: 80,
          rooms: [
            {
              id: 2,
              name: "Room 2",
              size: "35m²",
              beds: "2 beds",
              images: ["./src/assets/Bed/bed.png"],
              price: 80,
              rating: 4.2,
              reviews: 25,
              type: "Double Bed",
              details: "A cozy room with modern amenities.",
              amenities: ["Air Conditioning", "TV", "Free Wi-Fi", "Mini Bar"],
            }
          ],
          amenities: ["Air Conditioning", "TV", "Mini Bar", "Free Wi-Fi"],
        },
        {
          id: 3,
          name: "Hotel 3",
          stars: 3,
          reviewScore: 3.8,
          commentsCount: 15,
          location_image: "url",
          location: { city: "Battambang", distanceFromCenter: "3.5 km" },
          comments: ["Affordable price", "Clean rooms"],
          price: 60,
          rooms: [
            {
              id: 3,
              name: "Room 3",
              size: "30m²",
              beds: "1 bed",
              images: ["./src/assets/Bed/bed.png"],
              price: 60,
              rating: 3.8,
              reviews: 15,
              type: "Single Bed",
              details: "A budget-friendly room with basic facilities.",
              amenities: ["Air Conditioning", "Free Wi-Fi"],
            }
          ],
          amenities: ["Air Conditioning", "Free Wi-Fi"],
        },
        {
          id: 4,
          name: "Hotel 4",
          stars: 5,
          reviewScore: 4.9,
          commentsCount: 50,
          location_image: "url",
          location: { city: "Sihanoukville", distanceFromCenter: "0.5 km" },
          comments: ["Luxurious stay", "Amazing view"],
          price: 200,
          rooms: [
            {
              id: 4,
              name: "Room 4",
              size: "50m²",
              beds: "1 king bed",
              images: ["./src/assets/Bed/bed.png"],
              price: 200,
              rating: 4.9,
              reviews: 50,
              type: "King Suite",
              details: "A luxurious suite with a stunning ocean view.",
              amenities: ["Air Conditioning", "TV", "Mini Bar", "Free Breakfast", "Free Wi-Fi", "Hot Water"],
            }
          ],
          amenities: ["Air Conditioning", "TV", "Mini Bar", "Free Breakfast", "Free Wi-Fi", "Hot Water"],
        },
      ]

      // Simulate delay
      await new Promise(resolve => setTimeout(resolve, 500))

      // Filter based on destination
      const filtered = hotels
        .filter(hotel => {
          return !this.destination || hotel.location.city.toLowerCase().includes(this.destination.toLowerCase())
        })
        .flatMap(hotel => hotel.rooms.map(room => ({ ...room, hotel: hotel.name, city: hotel.location.city })))

      this.rooms = filtered
    },

    setDestination(dest) {
      this.destination = dest
    },

    setDates({ start, end }) {
      this.startDate = start
      this.endDate = end
    },

    setRooms(roomsArray) {
      this.rooms = roomsArray
    },

    setGuests({ adults, children }) {
      this.adults = adults
      this.children = children
    },

    toggleSearch(value) {
      this.withPet = value
    },

    reset() {
      this.destination = ''
      this.startDate = null
      this.endDate = null
      this.rooms = []
      this.adults = 1
      this.children = 0
      this.withPet = false
    }
  }
})
