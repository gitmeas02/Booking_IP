<template>
  <div class="room-card">
    <div class="room-image-section">
      <img
        v-if="room.images && room.images.length"
        :src="'http://localhost:9000/' + room.images[0].image_url"
        alt="Room"
        class="room-image"
      />
      <a href="#" class="room-photos-link">Room photos and details</a>
    </div>

    <div class="room-details-section">
      <h4 class="room-type">
        {{ room.name || "Standard Double with Fan" }}
      </h4>
      <div class="room-amenities">
        <div class="amenity" v-for="(amenity, index) in room.amenities" :key="index">
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
          👥 {{ room.capacity }} {{ room.capacity > 1 ? "guests" : "guest" }} 1
          room
        </div>
        <div class="stay-duration">5 nights • Nov 29, 2024</div>
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
import { computed, ref } from "vue";

const props = defineProps({
  room: {
    type: Object,
    required: true,
    default: () => ({
      images: [],
      name: "",
      capacity: 1,
      default_price: "0.00",
      amenities: [],
    }),
  },
  roomCount: {
    type: Number,
    default: 1,
  },
});

const emit = defineEmits(["reserve"]);

const selectedRoomCount = ref(1);

// Use the passed roomCount prop directly
const sameRoomCount = computed(() => props.roomCount);

const reserveRoom = () => {
  emit("reserve", { ...props.room, quantity: selectedRoomCount.value });
  console.log("Reserving room:", props.room, "Quantity:", selectedRoomCount.value);
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
}

.room-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
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
