<template>
  <div class="room-card">
    <div class="room-image-section">
      <img
        v-if="hasRoomImage"
        :src="getRoomImageUrl()"
        alt="Room"
        class="room-image"
        @error="handleImageError"
        @load="handleImageLoad"
      />
      <div v-if="!hasRoomImage" class="no-image-placeholder">
        <div class="placeholder-content">
          <div class="placeholder-icon">🏨</div>
          <div class="placeholder-text">No image available</div>
        </div>
      </div>
      <a href="#" class="room-photos-link">Room photos and details</a>
    </div>
    <div class="room-details-section">
      <h4 class="room-type">
        {{ room.name || "Standard Double with Fan" }}
      </h4>
      <div class="room-amenities">
        <div
          class="amenity"
          v-for="(amenity, index) in room?.amenities || []"
          :key="amenity.id || index"
        >
          {{ amenity.amenity_name }}
        </div>
      </div>
      <div class="room-specs">
        <div class="spec-item">
          🛏️ {{ room.capacity }} {{ room.capacity > 1 ? "guests" : "guest" }}
        </div>
      </div>
      <a href="#" class="see-facilities-link">See all room facilities</a>
    </div>
    <div class="room-pricing-section">
      <div class="pricing-info">
        <div class="guests-info">
          👥 {{ room.capacity }} {{ room.capacity > 1 ? "guests" : "guest" }} 1 room
        </div>
        <div class="stay-duration">{{ stayDuration }} • {{ formatDate(selectedStartDate) }}</div>
        <div class="price-breakdown">
          <span class="original-price">USD {{ room.default_price }} -12%</span>
          <div class="final-price">
            USD {{ (parseFloat(room.default_price) * 0.88).toFixed(2) }}
          </div>
        </div>
        <div class="tax-info">Per night before taxes and fees</div>
        <div class="payment-info">
          <div class="no-payment">No payment today</div>
          <div class="availability-info">
            Our last {{ sameRoomCount }} {{ sameRoomCount > 1 ? "rooms" : "room" }}!
          </div>
        </div>
      </div>
      <div class="room-quantity">
        <select v-model="selectedRoomCount">
          <option v-for="n in sameRoomCount" :key="n" :value="n">{{ n }}</option>
        </select>
      </div>
      <button class="book-now-btn" @click="reserveRoom">Book now</button>
      <div class="minutes-info">Booking only takes 2 minutes</div>
    </div>
  </div>
</template>
<script setup>
import { getImageUrl } from "@/utils/imageUtils";
import axios from "axios";
import dayjs from "dayjs";
import { computed, ref } from "vue";
import { useRouter } from "vue-router";

const props = defineProps({
  room: { type: Object, required: true },
  roomCount: { type: Number, default: 1 },
  selectedStartDate: { type: [String, Object], required: true },
  selectedEndDate: { type: [String, Object], required: true },
});

// Debug: Log room data to understand structure
console.log('Room data in RoomTypeCard:', props.room);
if (props.room?.images) {
  console.log('Room images:', props.room.images);
} else {
  console.log('No images found in room data');
}

// Check all possible image fields
const imageFields = ['images', 'photos', 'image_url', 'image', 'image_path'];
imageFields.forEach(field => {
  if (props.room?.[field]) {
    console.log(`Found ${field}:`, props.room[field]);
  }
});

const router = useRouter();
const selectedRoomCount = ref(1);
const sameRoomCount = computed(() => props.roomCount);

// Use centralized getImageUrl function from utils

// Helper to get room image URL with fallbacks
const getRoomImageUrl = () => {
  const room = props.room;
  
  // Try different possible image field structures
  if (room?.images && room.images.length > 0) {
    const imageUrl = room.images[0].image_url || room.images[0].url || room.images[0];
    return getImageUrl(imageUrl);
  }
  
  // Try other possible field names
  if (room?.image_url) {
    return getImageUrl(room.image_url);
  }
  
  if (room?.image) {
    return getImageUrl(room.image);
  }
  
  if (room?.photos && room.photos.length > 0) {
    const imageUrl = room.photos[0].url || room.photos[0];
    return getImageUrl(imageUrl);
  }
  
  console.warn('No image found in room data:', room);
  return '';
};

// Check if room has any image
const hasRoomImage = computed(() => {
  const room = props.room;
  return !!(
    (room?.images && room.images.length > 0) ||
    room?.image_url ||
    room?.image ||
    (room?.photos && room.photos.length > 0)
  );
});

// Image loading handlers
const handleImageError = (event) => {
  console.error('Room image failed to load:', event.target.src);
  event.target.style.display = 'none';
  const parent = event.target.parentElement;
  if (parent && !parent.querySelector('.fallback-room-image')) {
    const fallback = document.createElement('div');
    fallback.className = 'fallback-room-image';
    fallback.innerHTML = `
      <div class="fallback-content">
        <div class="fallback-icon">🏨</div>
        <div class="fallback-text">Image unavailable</div>
      </div>
    `;
    parent.appendChild(fallback);
  }
};

const handleImageLoad = (event) => {
  console.log('Room image loaded successfully:', event.target.src);
};

// Helper to normalize the date (accepts string, object, or a value with property)
const getDateValue = (date) => {
  if (!date) return "";
  return typeof date === "string" ? date : (date.value || date);
};

const stayDuration = computed(() => {
  const start = dayjs(getDateValue(props.selectedStartDate));
  const end = dayjs(getDateValue(props.selectedEndDate));
  const nights = end.diff(start, "day");
  return `${nights} ${nights === 1 ? "night" : "nights"}`;
});

const formatDate = (date) => {
  return dayjs(getDateValue(date)).format("MMM DD, YYYY");
};

const reserveRoom = async () => {
  try {
    const startDate = dayjs(getDateValue(props.selectedStartDate)).format('YYYY-MM-DD');
    const endDate = dayjs(getDateValue(props.selectedEndDate)).format('YYYY-MM-DD');

    console.log("Checking room availability:", {
      name: props.room.name,
      default_price: props.room.default_price,
      start_date: startDate,
      end_date: endDate,
      quantity: selectedRoomCount.value,
    });
    
    // Use URLSearchParams for GET request
    const params = new URLSearchParams({
      name: props.room.name,
      default_price: props.room.default_price.toString(),
      start_date: startDate,
      end_date: endDate,
      quantity: selectedRoomCount.value.toString(),
    });

    const res = await axios.get(`http://localhost:8100/api/rooms/available?${params}`);
    console.log("API Response:", res.data);
    
    if (!res.data || !res.data.room_ids) {
      throw new Error('Invalid response format');
    }
    
    if (res.data.room_ids.length === 0) {
      alert("No available rooms for your selection.");
      return;
    }

    // Navigate to checkout with room IDs and metadata
    router.push({
      name: "checkout",
      params: { id: props.room.id }, // This is crucial for the :id in route
      query: {
        roomIds: res.data.room_ids.join(","),
        hotelId: props.room.hotel_id || props.room.application_id || 2,
        checkin: startDate,
        checkout: endDate,
        quantity: selectedRoomCount.value,
        roomName: props.room.name,
        roomPrice: props.room.default_price,
        capacity: props.room.capacity,
      },
    });
  } catch (err) {
    console.error("Failed to reserve room:", err);
    alert("Failed to reserve room. Please try again: " + err.message);
  }
};
</script>

<style scoped>
.room-card {
  border: 1px solid #e7e7e7;
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 16px;
  display: grid;
  grid-template-columns: 200px 1fr 200px;
  background: white;
}

.room-image-section {
  position: relative;
  background: #f5f5f5;
  display: flex;
  align-items: center;
  justify-content: center;
}

.room-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.no-image-placeholder,
.fallback-room-image {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
  display: flex;
  align-items: center;
  justify-content: center;
}

.placeholder-content,
.fallback-content {
  text-align: center;
  color: #666;
}

.placeholder-icon,
.fallback-icon {
  font-size: 32px;
  margin-bottom: 8px;
  opacity: 0.7;
}

.placeholder-text,
.fallback-text {
  font-size: 12px;
  font-weight: 500;
}

.room-photos-link {
  position: absolute;
  bottom: 8px;
  left: 8px;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  text-decoration: none;
}

.room-details-section {
  padding: 16px;
}

.room-type {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 12px;
  color: #262626;
}

.room-amenities {
  margin-bottom: 12px;
}

.amenity {
  font-size: 14px;
  color: #0e7b0e;
  margin-bottom: 4px;
  display: block;
}

.room-specs {
  margin-bottom: 8px;
}

.spec-item {
  font-size: 14px;
  color: #6b6b6b;
  margin-bottom: 4px;
  display: block;
}

.see-facilities-link {
  color: #0071c2;
  text-decoration: none;
  font-size: 14px;
}

.room-pricing-section {
  padding: 16px;
  background: #f8f9fa;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: flex-end;
  text-align: right;
}

.price-label {
  background: #e74c3c;
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 600;
  margin-bottom: 8px;
}

.availability-notice {
  font-size: 12px;
  color: #e74c3c;
  margin-bottom: 8px;
}

.guests-info,
.stay-duration {
  font-size: 12px;
  color: #6b6b6b;
  margin-bottom: 4px;
}

.price-breakdown {
  margin: 8px 0;
}

.original-price {
  font-size: 14px;
  text-decoration: line-through;
  color: #6b6b6b;
  margin-right: 8px;
}

.final-price {
  font-size: 24px;
  font-weight: bold;
  color: #262626;
}

.tax-info {
  font-size: 12px;
  color: #6b6b6b;
  margin-bottom: 8px;
}

.payment-info {
  margin-bottom: 12px;
}

.no-payment {
  font-size: 12px;
  color: #0e7b0e;
  font-weight: 500;
}

.last-rooms {
  font-size: 12px;
  color: #e74c3c;
  font-weight: 500;
}

.room-quantity {
  margin-bottom: 12px;
}

.room-quantity select {
  padding: 8px;
  border: 1px solid #e7e7e7;
  border-radius: 4px;
  width: 60px;
}

.book-now-btn {
  background: #0071c2;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 600;
  font-size: 14px;
  width: 100%;
  margin-bottom: 8px;
  transition: background 0.2s;
}

.book-now-btn:hover {
  background: #005999;
}

.minutes-info {
  font-size: 12px;
  color: #6b6b6b;
}
</style>
