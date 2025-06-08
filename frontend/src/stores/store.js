import { defineStore } from "pinia";

export const useRoomStore = defineStore("room", {
  state: () => ({
    rooms: [],
    hotels: [],
    properties: [], amenities: [
      { id: "Air Conditioning", name: "Air Conditioning", icon: "❄️" },
      { id: "TV", name: "TV", icon: "📺" },
      { id: "Mini Bar", name: "Mini Bar", icon: "🍹" },
      { id: "Free Breakfast", name: "Free Breakfast", icon: "🥐" },
      { id: "Free Wi-Fi", name: "Free Wi-Fi", icon: "📶" },
      { id: "Free Parking", name: "Free Parking", icon: "🅿️" },
      { id: "Hot Water", name: "Hot Water", icon: "💧" },
    ],
    destination: null,
    startDate: null,
    endDate: null,
    adults: 1,
    children: 0,
  }),
  actions: {
    async fetchRooms() {
      this.rooms = [
        {
          id: 12,
          hotelId: 1,
          price: 123,
          name: "Deluxe Suite",
          available: true,
          blockedDates: [{ from: "2024-07-01", to: "2024-07-05" }],
          allowPet: true,
          amenities: ["Free Wi-Fi", "Air Conditioning", "TV", "Mini Bar"],
          ownerId: "112",
          hotelName: "Hotel Sunshine",
          size: "40 m²",
          beds: "1 King Bed",
          images: [
            "https://picsum.photos/200",
            "https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1074&q=80",
            "https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&auto=format&fit=crop&w=1074&q=80",
          ],
        },
        {
          id: 1,
          hotelId: 1, // Added hotelId
          name: "Standard Room",
          price: 123,
          available: false,
          blockedDates: [{ from: "2024-06-20", to: "2024-06-25" }],
          allowPet: false,
          amenities: ["Free Wi-Fi", "Air Conditioning"],
          ownerId: "ownerB",
          hotelName: "Hotel Moonlight",
          size: "25 m²",
          beds: "2 Single Beds",
          images: ["https://picsum.photos/200"],
        },
        {
          id: 2,
          hotelId: 2,
          name: "Family Room",
          price: 123,
          available: true,
          blockedDates: [],
          allowPet: true,
          amenities: ["Free Wi-Fi", "Air Conditioning", "TV", "Mini Bar"],
          ownerId: "ownerC",
          hotelName: "Hotel Oceanview",
          size: "50 m²",
          beds: "1 King Bed, 2 Single Beds",
          images: ["https://picsum.photos/200"],
        },
        {
          id: 3,
          hotelId: 3, // Added hotelId
          name: "Single Room",
          price: 123,
          available: false,
          blockedDates: [{ from: "2024-07-10", to: "2024-07-15" }],
          allowPet: false,
          amenities: ["Free Wi-Fi", "Air Conditioning", "TV", "Mini Bar"],
          ownerId: "ownerD",
          hotelName: "Hotel Riverside",
          size: "20 m²",
          beds: "1 Single Bed",
          images: ["https://picsum.photos/200"],
        },
        {
          id: 4,
          hotelId: 4, // Added hotelId
          name: "Executive Suite",
          price: 123,
          available: true,
          blockedDates: [{ from: "2024-08-01", to: "2024-08-10" }],
          allowPet: true,
          amenities: ["Free Wi-Fi", "Air Conditioning", "TV", "Mini Bar"],
          ownerId: "ownerE",
          hotelName: "Hotel Mountainview",
          size: "45 m²",
          beds: "1 King Bed",
          images: ["https://picsum.photos/200"],
        },
      ];
    },
    async fetchHotels() {
      this.properties = [
        { name: "Hotel", count: 123 },
        { name: "Apartment", count: 123 },
        { name: "Guest House", count: 123 },
        { name: "Villa", count: 85 },
        { name: "Resort", count: 45 },
        { name: "Hostel", count: 12 },
      ];
      this.hotels = [
        {
          id: 1,
          name: "Hotel Sunshine",
          stars: 5,
          reviewScore: 4.5,
          location_image: "url",
          location: {
            district: "Sangkat Chak Tomuk, Daun Penh",
            commune: "Phsar Thmei I",
            city: "Phnom Penh",
            distanceFromCenter: "1.7 km",
            address: "No 123 Street 244",
            country: "Cambodia",
          },
          description: [
            "Located in Phnom Penh, Khun Hotel offers accommodation 5 minutes away from Central Market.",
          ],
          comments: ["Free breakfast", "Free Wi-Fi"],
          price: 100,
          roomId: [12, 1],
          rooms: [],
          amenities: [
            "Air Conditioning",
            "TV",
          ],
        },
        {
          id: 2,
          name: "Hotel Moonlight",
          stars: 4,
          reviewScore: 4.2,
          location_image: "url",
           location: {
            district: "Sangkat Chak Tomuk, Daun Penh",
            commune: "Phsar Thmei I",
            city: "Phnom Penh",
            distanceFromCenter: "1.7 km",
            address: "No 123 Street 244",
            country: "Cambodia",
          },
          description: [
            "Located in Phnom Penh, Khun Hotel offers accommodation 5 minutes away from Central Market.",
          ],
          comments: ["Great location", "Friendly staff"],
          price: 80,
          roomId: [2],
          rooms: [],
          amenities: ["Air Conditioning", "TV", "Mini Bar", "Free Wi-Fi"],
        },
        {
          id: 3,
          name: "Hotel Oceanview",
          stars: 3,
          reviewScore: 3.8,
          location_image: "url",
          description: [
            "Located in Phnom Penh, Khun Hotel offers accommodation 5 minutes away from Central Market.",
          ],
           location: {
            district: "Sangkat Chak Tomuk, Daun Penh",
            commune: "Phsar Thmei I",
            city: "Phnom Penh",
            distanceFromCenter: "1.7 km",
            address: "No 123 Street 244",
            country: "Cambodia",
          },
          comments: ["Affordable price"],
          price: 60,
          roomId: [3],
          rooms: [],
          amenities: ["Air Conditioning", "Free Wi-Fi"],
        },
        {
          id: 4,
          name: "Hotel Riverside",
          stars: 5,
          reviewScore: 4.9,
          description: [
            "Located in Phnom Penh, Khun Hotel offers accommodation 5 minutes away from Central Market.",
          ],
          location_image: "url",
           location: {
            district: "Sangkat Chak Tomuk, Daun Penh",
            commune: "Phsar Thmei I",
            city: "Phnom Penh",
            distanceFromCenter: "1.7 km",
            address: "No 123 Street 244",
            country: "Cambodia",
          },
          comments: ["Luxurious stay", "Amazing view"],
          price: 200,
          roomId: [4],
          rooms: [],
          amenities: [
            "Air Conditioning",
            "TV",
            "Mini Bar",
            "Free Breakfast",
            "Free Wi-Fi",
            "Hot Water",
          ],
        },
      ];

      this.hotels.forEach((hotel) => {
        const matchingRooms = this.rooms.filter((room) =>
          hotel.roomId.includes(room.id)
        );
        matchingRooms.forEach((room) => {
          room.hotel = hotel; // ← Add full hotel object to room
        });
        hotel.rooms = matchingRooms;
      });
    },
  },
  getters: {
    getRoomsByHotelId: (state) => (hotelId) => {
    return state.rooms.filter((room) => room.hotelId === hotelId);
  },
    getRoomById: (state) => (id) => {
      return state.rooms.find((room) => room.id === id);
    },
    getHotelById: (state) => (id) => {
      return state.hotels.find((hotel) => hotel.id === id);
    },
   searchRoomBy_name_hotelName_range_date(name,start_date,end_date){

   }
  },
});
