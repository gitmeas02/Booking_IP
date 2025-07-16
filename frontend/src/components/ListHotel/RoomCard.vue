<template>
  <div
    class="hotel-card"
    @click="$emit('click')"
  >
    <!-- Image -->
    <div class="image">
      <img
        v-if="room.room_types[0]?.images?.length"
        :src="
          'http://localhost:9000/' + room.room_types[0].images[0].thumbnail_url
        "
        :alt="`Image of ${room.property_name}`"
        class="img"
      />
      <div
        v-else
        class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400 text-sm"
      >
        No Image
      </div>
    </div>

    <!-- Hotel Info -->
    <div class="hotel-card-info">
      <!-- Header -->
      <div class="hotel-header">
        <div class="hotel-name-container">
          <h1 class="hotel-name">{{ room.property_name }}</h1>
          <div class="stars-container">
            <span class="star-icon" v-for="n in room?.star_rating" :key="n"
              >★</span
            >
          </div>
        </div>

        <div class="review-container">
          <div class="review-score-container">
            <span class="review-text">Review Score</span>
            <div class="score-badge">{{ 1 ?? "N/A" }}</div>
            <!-- //.getRoomById   -->
          </div>
          <a href="#" class="comments-link">
            Comments (1)
            <!-- {{ room.feedback?.length ?? 0 }} -->
          </a>
        </div>
      </div>

      <!-- Location -->
      <div class="location-container">
        <a href="#" class="location-link">{{ room.location?.city }}</a>
        <a
          :href="
            'https://www.google.com/maps?q=' +
            encodeURIComponent(room.location?.street)
          "
          target="_blank"
          class="map-link"
        >
          Show On Map
        </a>
        <span class="distance-text">
          <!-- {{ room.hotel?.location?.distanceFromCenter ?? 'N/A' }} from center -->
          {{ room.location.city ?? "N/A" }} from center
        </span>
      </div>

      <!-- Price -->
      
      <!-- Room Info -->
      <div class="room-info">
          <p class="text-gray-700 text-xl">
          Price: $
          {{
            room.room_types[0]?.room_prices?.length
              ? room.room_types[0].room_prices[0].custom_price
              : room.room_types[0].default_price
          }}
        </p>
        <p>{{ room.room_types[0].description.slice(0,99) }}</p>
      </div>

      <!-- Amenities -->
      <div class="amenities-container">
        <div class="amenities-list">
          <div
            class="amenity-item"
            v-for="(amenity, aIndex) in room.room_types[0].amenities.slice(0,6)"
            :key="aIndex"
          >
            <component
              :is="icons[amenity.amenity_name]"
              class="w-4 h-4 text-gray-600"
            />
            <span class="amenity-text">{{ amenity.amenity_name }}</span>
          </div>
        </div>
      </div>

      <!-- View Button -->
      <div class="action-container">
        <button class="view-button" @click.stop="viewHotel(room.id)">
          View Hotel
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { getImageUrl } from "@/utils/imageUtils";
import {
  AirVent, // for Kids’ play area
  // Dice, // for Board games
  AlertTriangle, // for EV charging station
  Baby, // for Balcony or terrace
  Bath, // for Airport shuttle
  BatteryCharging, // for Bar
  Bell, // for Pet-friendly, Pet bowls, Pet bed
  Briefcase, // for Fire extinguishers
  Camera, // for 24-hour front desk
  // Broom, // for Daily housekeeping
  // Elevator, // for Elevator
  Car, // for Beachfront
  Clock, // for Microwave
  Coffee, // for Coffee/tea maker
  DoorOpen, // for Towels (placeholder, as no direct towel icon)
  // HairDryer, // for Hairdryer
  Droplet, // for Sauna
  Dumbbell, // for Room service
  EggFried, // for Smoke alarms
  FireExtinguisher, // for Air conditioning
  Flame, // for Swimming pool
  Heart, // for Flat-screen TV
  Lock, // for Safe

  // Fridge, // for Mini fridge
  Microwave, // for Free parking, Car rental
  PawPrint, // for Mini-market
  // Wheelchair, // for Wheelchair accessible, Accessible bathroom
  Plane, // for Kitchen, Kitchenette, Restaurant
  // Stove, // for Stove
  // Oven, // for Oven
  Sandwich, // for Breakfast included
  ShoppingCart, // for Private bathroom, Bathtub, Hot tub
  // Shower, // for Shower, Hot water
  Square, // for Fitness center
  Sun, // for Spa (placeholder for wellness)
  ThermometerSun, // for Baby cot
  ToyBrick, // for Free Wi-Fi, High-speed internet
  Tv, // for Business center
  Users, // for Toiletries (placeholder for soap/shampoo)
  Utensils, // for Toaster (replacing BreadSlice)
  // Dishwasher, // for Dishwasher
  Waves, // for Heating
  Wifi, // for Meeting room
  Wine, // for Bar
} from "lucide-vue-next";

const icons = {
  "Air conditioning": AirVent,
  Heating: Flame,
  "Free Wi-Fi": Wifi,
  "Flat-screen TV": Tv,
  Safe: Lock,
  // "Mini fridge": Fridge,
  Microwave: Microwave,
  "Coffee/tea maker": Coffee,
  "Balcony or terrace": DoorOpen,
  "Private bathroom": Bath,
  // "Shower": Shower,
  Bathtub: Bath,
  Towels: Square, // Placeholder, no direct towel icon
  // "Hairdryer": HairDryer,
  Toiletries: Droplet, // Placeholder for soap/shampoo
  // "Hot water": Shower,
  Kitchen: Utensils,
  Kitchenette: Utensils,
  // "Stove": Stove,
  // "Oven": Oven,
  Toaster: Sandwich, // Replaced BreadSlice with Sandwich
  // "Dishwasher": Dishwasher,
  "Swimming pool": Waves,
  "Hot tub": Bath, // No specific hot tub icon
  Spa: Heart, // Placeholder for wellness
  Sauna: ThermometerSun,
  "Fitness center": Dumbbell,
  Beachfront: Sun,
  "24-hour front desk": Clock,
  // "Daily housekeeping": Broom,
  // "Elevator": Elevator,
  "Free parking": Car,
  "Pet-friendly": PawPrint,
  "Business center": Briefcase,
  "Meeting room": Users,
  "High-speed internet": Wifi,
  Restaurant: Utensils,
  Bar: Wine,
  "Room service": Bell,
  "Breakfast included": EggFried,
  "Mini-market": ShoppingCart,
  // "Wheelchair accessible": Wheelchair,
  // "Accessible bathroom": Wheelchair,
  "Airport shuttle": Plane,
  "Car rental": Car,
  "EV charging station": BatteryCharging,
  "Baby cot": Baby,
  "Kids’ play area": ToyBrick,
  // "Board games": Dice,
  "Pet bowls": PawPrint,
  "Pet bed": PawPrint,
  "Smoke alarms": AlertTriangle,
  "Fire extinguishers": FireExtinguisher,
  CCTV: Camera,
};
const props = defineProps({
  room: { type: Object, required: true },
});

const emit = defineEmits(['click']);

function viewHotel(id) {
  // Navigate to hotel product details page
  console.log("Navigate to hotel details:", id);
  emit('click', id);
}
</script>

<style scoped>
.hotel-card {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: start;
  /* width: fit-content; */
}

.image {
  width: 279px;
  height: 220px;
  border-radius: 10px 0 0 10px;
  overflow: hidden;
}

.image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.hotel-card-info {
  width: 472px;
  height: fit-content;
  border-top: gray 1px solid;
  border-right: gray 1px solid;
  border-bottom: gray 1px solid;
  border-radius: 0 10px 10px 0;
  height: 220px;
}

.hotel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-left: 17px;
  padding-right: 17px;
  padding-top: 5px;
  padding-bottom: 0px;
}

.hotel-name-container {
  display: flex;
  flex-direction: column;
}

.hotel-name {
  font-size: 16px;
  margin: 0;
}

.stars-container {
  display: flex;
}

.star-icon {
  display: inline-block;
  font-size: 14px;
  color: #facc15;
  line-height: 1;
}

.review-container {
  text-align: right;
}

.review-score-container {
  display: flex;
  align-items: center;
  gap: 8px;
  justify-content: flex-end;
}

.review-text {
  font-size: 14px;
}

.score-badge {
  background-color: #1e3a8a;
  color: white;
  font-size: 14px;
  width: 24px;
  height: 23px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 3px;
}

.comments-link {
  display: flex;
  height: fit-content;
  color: #3b82f6;
  font-size: 10px;
  text-decoration: none;
}

.comments-link:hover {
  text-decoration: underline;
}

.location-container {
  display: flex;
  gap: 27px;
  padding: 0px 21px;
}

.location-link {
  color: #3b82f6;
  font-size: 14px;
  display: flex;
  align-items: center;
  text-decoration: none;
}

.location-link:hover {
  text-decoration: underline;
}

.map-link {
  color: #3b82f6;
  font-size: 14px;
  display: flex;
  align-items: center;
  text-decoration: none;
}

.map-link:hover {
  text-decoration: underline;
}

.distance-text {
  color: #3b82f6;
  font-size: 14px;
  display: flex;
  align-items: center;
}

.price-container {
  padding: 7px 20px;
}

.price-text {
  display: flex;
  height: fit-content;
  font-size: 12px;
  margin: 0;
}

.room-info {
  padding: 3px 12px;
}

.room-list {
  list-style-type: disc;
  padding-left: 32px;
}

.room-list-item {
  font-size: 12px;
}

.amenities-container {
  padding: 0;
}

.amenities-list {
  display: flex;
  flex-wrap: wrap;
  gap: 12px 7px;
  padding: 0 21px;
}

.amenity-item {
  display: flex;
  align-items: center;
  height: fit-content;
  gap: 8px;
}

.amenity-icon {
  font-size: 20px;
}

.amenity-text {
  display: flex;
  font-size: 10px;
  color: #1f2937;
}

.action-container {
  padding: 4px 16px;
  display: flex;
  justify-content: flex-end;
}

.view-button {
  background-color: #2563eb;
  color: white;
  font-size: 14px;
  font-weight: 500;
  padding: 8px 16px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: background-color 0.2s ease, transform 0.2s ease,
    box-shadow 0.2s ease;
}

.view-button:hover {
  background-color: #1d4ed8;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.view-button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.4);
}

/* Media query for responsive design */
@media (max-width: 768px) {
  .hotel-card {
    flex-direction: column;
    width: 100%;
  }

  .image {
    width: 100%;
    height: 200px;
    border-radius: 10px 10px 0 0;
  }

  .hotel-card-info {
    width: 100%;
    height: auto;
    border-radius: 0 0 10px 10px;
    border-top: none;
  }

  .hotel-header {
    flex-direction: column;
    align-items: flex-start;
    padding: 12px;
  }

  .review-container {
    margin-top: 12px;
    text-align: left;
  }

  .location-container {
    flex-direction: column;
    gap: 8px;
    padding: 12px;
  }

  .price-container {
    padding: 12px;
  }

  .room-info {
    padding: 12px;
  }

  .amenities-list {
    gap: 10px;
    padding: 12px;
  }

  .action-container {
    justify-content: center;
    padding: 12px;
  }

  .view-button {
    width: 100%;
    max-width: 200px;
    font-size: 16px;
    padding: 10px;
  }
}
</style>
