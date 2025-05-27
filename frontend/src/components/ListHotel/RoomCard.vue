<template> 
  <div
    class="hotel-card cursor-pointer"
    @click="$router.push({ name: 'ProductsDetails', params: { id: room.id } })"
  >
    <!-- Image -->
    <div class="image">
      <img
        v-if="room.images?.length"
        :src="room.images[0]"
        :alt="`Image of ${room.name}`"
        class="img"
      />
      <div v-else class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
        No Image
      </div>
    </div>

    <!-- Hotel Info -->
    <div class="hotel-card-info">
      <!-- Header -->
      <div class="hotel-header">
        <div class="hotel-name-container">
          <h1 class="hotel-name">{{ room.hotel?.name }}</h1>
          <div class="stars-container">
            <span class="star-icon" v-for="n in room.hotel?.stars" :key="n">★</span>
          </div>
        </div>

        <div class="review-container">
          <div class="review-score-container">
            <span class="review-text">Review Score</span>
            <div class="score-badge">{{ room.hotel?.reviewScore ?? 'N/A' }}</div>
          </div>
          <a href="#" class="comments-link">
            Comments ({{ room.hotel?.comments?.length ?? 0 }})
          </a>
        </div>
      </div>

      <!-- Location -->
      <div class="location-container">
        <a href="#" class="location-link">{{ room.hotel?.location?.city }}</a>
        <a href="#" class="map-link">Show On Map</a>
        <span class="distance-text">
          {{ room.hotel?.location?.distanceFromCenter ?? 'N/A' }} from center
        </span>
      </div>

      <!-- Price -->
      <div class="price-container">
        <div class="price-text">US$ {{ room.price }}</div>
      </div>

      <!-- Room Info -->
      <div class="room-info">
        <ul class="room-list">
          <li class="room-list-item">Size: {{ room.size }}</li>
          <li class="room-list-item">Beds: {{ room.beds }}</li>
        </ul>
      </div>

      <!-- Amenities -->
      <div class="amenities-container">
        <div class="amenities-list">
          <div
            class="amenity-item"
            v-for="(amenity, aIndex) in room.amenities"
            :key="aIndex"
          >
            <component
              :is="icons[amenity]"
              class="w-4 h-4 text-gray-600"
            />
            <span class="amenity-text">{{ amenity }}</span>
          </div>
        </div>
      </div>

      <!-- View Button -->
      <div class="action-container">
        <button
          class="view-button"
          @click.stop="viewHotel(room.id)"
        >
          View Hotel
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import {
  AirVent,
  Coffee,
  Phone,
  ShowerHead,
  Tv,
  Wifi,
  Wine
} from 'lucide-vue-next';

const props = defineProps({
  room: { type: Object, required: true }
});

const icons = {
  "Air Conditioning": AirVent,
  "TV": Tv,
  "Mini Bar": Wine,
  "Free Breakfast": Coffee,
  "Free Wi-Fi": Wifi,
  "Hot Water": ShowerHead,
  "Phone Call": Phone
};

function viewHotel(id) {
  // Optional: additional behavior before navigating
  console.log('View hotel (room id):', id);
}
</script>



<style scoped>
.hotel-card {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: start;
    width: fit-content;
}

.image {
    width: 279px;
    height: 220px;
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
    outline: 1px solid #dddede;
    border-radius: 4px;
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
    font-size: 13px;
    width: 88px;
    height: 23px;
    border: none;
    border-radius: 2px;
    cursor: pointer;
}

.view-button:hover {
    background-color: #1d4ed8;
}

/* Media query for responsive design */
@media (max-width: 768px) {

    .hotel-header,
    .location-container {
        flex-direction: column;
        align-items: flex-start;
    }

    .review-container {
        margin-top: 16px;
        text-align: left;
    }

    .review-score-container {
        justify-content: flex-start;
    }

    .location-link,
    .map-link,
    .distance-text {
        margin: 8px 0;
    }

    .amenities-list {
        gap: 16px;
    }
}
</style>