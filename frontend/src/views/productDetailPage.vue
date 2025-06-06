<template>
  <div v-if="room && hotel">
    <!-- Hotel Listing Section -->
    <div class="hotel-listing">
      <div class="hotel-header">
        <div>
          <h1 class="hotel-name">{{ hotel.name }}</h1>
          <p class="hotel-location">
            Location: {{ hotel.location?.address }}, {{ hotel.location?.commune }},
            {{ hotel.location?.district }}, {{ hotel.location?.city }},
            {{ hotel.location?.country }}
          </p>
        </div>
        <div class="review-score">
          <div class="score">{{ hotel.reviewScore }}</div>
          <div class="score-text">{{ getReviewScoreText(hotel.reviewScore) }}</div>
        </div>
      </div>

      <div class="main-image-container">
        <img :src="images[currentImageIndex]" alt="Hotel room" class="main-image" v-if="images.length"
          @click="openPhotoModal(currentImageIndex)" />
        <div class="navigation-dots">
          <span v-for="(_, index) in images" :key="index" :class="['dot', { active: index === currentImageIndex }]"
            @click="setCurrentImage(index); openPhotoModal(index)"></span>
        </div>
      </div>

      <div class="thumbnail-gallery">
        <div v-for="(image, index) in images" :key="index" class="thumbnail-container" @click="openPhotoModal(index)">
          <img :src="image" alt="Room thumbnail" class="thumbnail" />
          <div v-if="index === images.length - 1" class="photo-count">
            <span>{{ images.length }} photos</span>
          </div>
        </div>
      </div>
    </div>

    <div style="margin-top: 20px"></div>

    <!-- Hotel Booking App Section -->
    <div class="hotel-booking-app">
      <div class="hotel-header">
        <div class="hotel-title-section">
          <h1 class="hotel-name">{{ hotel.name }}</h1>
          <div class="hotel-location">
            <p>
              Location: {{ hotel.location?.address }}, {{ hotel.location?.commune }},
              {{ hotel.location?.district }}, {{ hotel.location?.city }},
              {{ hotel.location?.country }}
            </p>
          </div>
        </div>
        <div class="message-box">
          <h2>MESSAGE</h2>
        </div>
      </div>

      <div class="main-content">
        <!-- Hotel Information -->
        <div class="hotel-info">
          <div class="hotel-description">
            <p v-for="(desc, index) in hotel.description" :key="index">{{ desc }}</p>
          </div>
          <button class="show-map-btn">Show Map</button>

          <!-- Facilities Section -->
          <div class="facilities-section">
            <h3>Most popular facilities</h3>
            <div class="facilities-grid">
              <div class="facility" v-for="amenity in hotelAmenities" :key="amenity.id">
                <span class="icon">{{ amenity.icon }}</span> {{ amenity.name }}
              </div>
            </div>
          </div>


          <!-- Available Rooms -->
          <div class="available-property">
            <h3>AVAILABLE ROOMS</h3>
            <button class="view-more-btn">View More Listing</button>
            <div class="room-listings">
              <div v-for="(r, index) in roomsBelongToHotel" :key="index" class="room-card">
                <div class="room-image">
                  <img :src="r.image || r.images?.[0]" :alt="r.title" />
                </div>
                <div class="room-details">
                  <h4>${{ r.price }} / night</h4>
                  <p>{{ r.bedType }}</p>
                  <p>{{ r.location }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Booking Section -->
        <div class="booking-section">
          <div class="hotel-thumbnail">
            <img :src="images[0]" alt="Hotel" class="thumbnail-img" />
            <h3>{{ hotel.name }}</h3>
            <p>{{ hotel.location?.city }}</p>
          </div>

          <div class="booking-form">
            <h3>Where you'll sleep</h3>
            <div class="date-selection">
              <div class="check-in">
                <label>Check in</label>
                <input type="date" v-model="checkInDate" />
              </div>
              <div class="check-out">
                <label>Check out</label>
                <input type="date" v-model="checkOutDate" />
              </div>
            </div>

            <div class="price-details">
              <div class="price-per-night">
                <span>${{ room.price }}</span>
                <span>x {{ numberOfNights }} nights</span>
                <span>${{ totalRoomPrice }}</span>
              </div>
              <div class="booking-fee">
                <span>Booking Fee</span>
                <span>${{ bookingFee }}</span>
              </div>
              <div class="total-price">
                <span>Total</span>
                <span>${{ totalPrice }}</span>
              </div>
            </div>

            <button class="reserve-btn" @click="reserveRoom">Reserve</button>
            <p class="tax-note">Tax rates may apply</p>
          </div>

          <div class="map-section">
            <div class="map-container">
              <img src="https://plus.unsplash.com/premium_photo-1682310071124-33632135b2ee?w=500" alt="Map"
                class="map-image" />
              <div class="map-marker">📍</div>
            </div>
            <div class="direction-location">
              <h3>Direction Location</h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Comment Section -->
      <div id="comment-header" class=" font-bold text-2xl pb-4">Comment</div>

      <!-- Comment Input -->
      <div id="comment-fill" class=" flex justify-center items-center py-3 pr-7">

        <div id="comment-profile" class="flex items-center justify-between gap-2">
          <div id="comment-pic">😆</div>
          <div id="comment-name" class="font-bold">{{ userName }}</div>
        </div>

        <div id="comment-input" class="flex-1 ml-2">
          <input v-model="newComment" type="text" placeholder="Write a comment..."
            class="w-full px-6 py-2 border-b-1 focus:outline-none " />
        </div>

      </div>

      <!-- Comment Button -->
      <div id="comment-btn" class=" flex justify-end items-center gap-2 pr-6.5">
        <button class="text-base px-7 py-2 rounded-2xl" @click="cancel">Cancel</button>
        <button class="text-base px-7 py-2 rounded-lg border-2 border-gray-400" @click="handleComment">Comment</button>
      </div>

      <!-- Rendered Comments -->
      <div v-for="(comment, index) in comments" :key="index" class="flex items-center content-between mt-4 border-t border-gray-300 pt-4 px-6">
        <!--  information -->
        <div id="comment-info" class="flex items-start flex-col w-fit">
          <div class="flex items-center gap-2 mb-2">
            <div class="text-xl">😆</div>
            <div class="font-bold">{{ userName }}</div>
          </div>

          <div id="check-in-out" class="text-gray-500 text-sm flex items-center gap-2">
            <Calendar/> 3 nights.Jan.2024
          </div>

          <div id="room-type" class="text-gray-700 mt-2 text-sm flex items-center gap-2">
            <BedSingle/> Deluxe Room with Balcony
          </div>
        </div>
        
        <!-- User Comment -->
        <div id="comment-content" class="flex items-start self-start flex-col w-full gap-5">
          <div id="comment-date" class="">Reviewed: {{ comment.date }}</div>
          <div id="comment" class="font-bold text-xl">{{ comment.text }}</div>
        </div>

        <!-- Rating -->
        <div id="comment-rating" class="flex items-start self-start gap-2 bg-blue-900 text-white p-1 rounded-sm">
          1.0
        </div>

        
      </div>

    </div>

    <!-- Modal Viewer -->
    <div v-if="modalVisible" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <button class="modal-close" @click="closeModal">
          <X stroke-width="3" />
        </button>
        <button class="modal-nav left" @click="prevPhoto" :disabled="currentPhotoIndex === 0">
          <ChevronLeft />
        </button>
        <img :src="images[currentPhotoIndex]" class="modal-image" />
        <button class="modal-nav right" @click="nextPhoto" :disabled="currentPhotoIndex === images.length - 1">
          <ChevronRight />
        </button>
      </div>
    </div>

  </div>

  <div v-else>
    <p>Loading room and hotel info...</p>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useRoomStore } from '@/stores/store'
import { ChevronLeft, ChevronRight, Divide, X, Calendar,BedSingle } from 'lucide-vue-next';

const roomStore = useRoomStore()
const route = useRoute()
const roomId = parseInt(route.params.id)

const checkInDate = ref('')
const checkOutDate = ref('')
const bookingFee = ref(20)

const room = computed(() => roomStore.getRoomById(roomId))
const hotel = computed(() =>
  room.value ? roomStore.getHotelById(room.value.hotelId) : null
)

const roomsBelongToHotel = computed(() =>
  hotel.value ? roomStore.rooms.filter(r => r.hotelId === hotel.value.id) : []
)
const hotelAmenities = computed(() =>
  roomStore.amenities && hotel.value && hotel.value.amenities
    ? roomStore.amenities.filter(a => hotel.value.amenities.includes(a.id))
    : []
)
const images = ref([])
watch(room, newRoom => {
  if (newRoom && newRoom.images) {
    images.value = newRoom.images
  }
})

const currentImageIndex = ref(0)
const currentImage_thamnals= (index)=>{
  if (index >= 0 && index < images.value.length) {
    currentImageIndex.value = index
  } else {
    console.warn('Index out of bounds for image thumbnails')
  }
}
const setCurrentImage = (index) => {
  currentImageIndex.value = index
}

const numberOfNights = computed(() => {
  if (checkInDate.value && checkOutDate.value) {
    const start = new Date(checkInDate.value)
    const end = new Date(checkOutDate.value)
    const diffTime = Math.abs(end - start)
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24))
  }
  return 0
})

const pricePerNight = computed(() => room.value?.price || 0)
const totalRoomPrice = computed(() => pricePerNight.value * numberOfNights.value)
const totalPrice = computed(() => totalRoomPrice.value + bookingFee.value)

const reserveRoom = () => {
  alert(`Reserved room ID ${roomId} from ${checkInDate.value} to ${checkOutDate.value}`)
}

const getReviewScoreText = (score) => {
  if (score >= 9) return 'Excellent'
  if (score >= 7) return 'Good'
  if (score >= 5) return 'Average'
  return 'Poor'
}

onMounted(async () => {
  await roomStore.fetchRooms()
  await roomStore.fetchHotels()
})

const modalVisible = ref(false)
const currentPhotoIndex = ref(0)

const openPhotoModal = (index) => {
  currentPhotoIndex.value = index
  modalVisible.value = true
}

const closeModal = () => {
  modalVisible.value = false
}

const nextPhoto = () => {
  if (currentPhotoIndex.value < images.value.length - 1) {
    currentPhotoIndex.value++
  }
}

const prevPhoto = () => {
  if (currentPhotoIndex.value > 0) {
    currentPhotoIndex.value--
  }
}


const userName = ref('Khun Meas');

const newComment = ref('');
const comments = ref([]);

const handleComment = () => {
  if (newComment.value.trim() !== '') {
    comments.value.push({
      text: newComment.value.trim(),
      date: new Date().toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      }),
    });
    newComment.value = '';
  }
};

const cancel = () => {
  newComment.value = ''
}

</script>
<style scoped>
.hotel-listing {
  max-width: 1200px;
  margin: 0 auto;
  font-family: Arial, sans-serif;
  color: #333;
}

.hotel-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 15px;
}

.hotel-name {
  font-size: 24px;
  font-weight: bold;
  margin: 0 0 5px 0;
  color: #333;
  text-transform: uppercase;
}

.hotel-location {
  font-size: 14px;
  margin: 0;
  color: #666;
}

.review-score {
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: #003580;
  color: white;
  padding: 8px 12px;
  border-radius: 5px;
  min-width: 45px;
  text-align: center;
}

.score {
  font-size: 20px;
  font-weight: bold;
}

.score-text {
  font-size: 12px;
}

.main-image-container {
  position: relative;
  width: 100%;
  height: 400px;
  overflow: hidden;
  border-radius: 8px;
  margin-bottom: 10px;
}

.main-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.navigation-dots {
  position: absolute;
  bottom: 15px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 8px;
}

.dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.5);
  cursor: pointer;
  transition: background-color 0.3s;
}

.dot.active {
  background-color: white;
}

.thumbnail-gallery {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 10px;
}

.thumbnail-container {
  position: relative;
  height: 120px;
  overflow: hidden;
  border-radius: 8px;
  cursor: pointer;
}

.thumbnail {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s;
}

.thumbnail-container:hover .thumbnail {
  transform: scale(1.05);
}

.photo-count {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  color: white;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 16px;
  font-weight: bold;
}

@media (max-width: 768px) {
  .hotel-header {
    flex-direction: column;
  }

  .review-score {
    margin-top: 10px;
    align-self: flex-start;
  }

  .main-image-container {
    height: 300px;
  }

  .thumbnail-gallery {
    grid-template-columns: repeat(2, 1fr);
  }
}

.hotel-booking-app {
  font-family: Arial, sans-serif;
  max-width: 1200px;
  margin: 0 auto;
  color: #333;
}

.hotel-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 20px;
}

.hotel-name {
  font-size: 1.5rem;
  margin: 0;
  color: #333;
}

.hotel-location {
  font-size: 0.8rem;
  margin-top: 5px;
}

.message-box {
  background-color: #8b4513;
  color: white;
  padding: 10px 30px;
  border-radius: 5px;
}

.message-box h2 {
  margin: 0;
  font-size: 1rem;
}

.main-content {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.hotel-info {
  flex: 2;
  min-width: 300px;
}

.hotel-description {
  font-size: 0.9rem;
  line-height: 1.5;
}

.show-map-btn {
  background-color: white;
  border: 1px solid #ccc;
  padding: 5px 15px;
  margin: 10px 0;
  cursor: pointer;
}

.facilities-section {
  margin: 20px 0;
}

.facilities-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: 10px;
}

.facility {
  display: flex;
  align-items: center;
  font-size: 0.9rem;
}

.icon {
  margin-right: 5px;
}

.available-property {
  margin: 20px 0;
}

.view-more-btn {
  background-color: white;
  border: 1px solid #ccc;
  padding: 5px 15px;
  margin: 10px 0;
  cursor: pointer;
}

.room-listings {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 15px;
  margin-top: 15px;
}

.room-card {
  border: 1px solid #ddd;
  border-radius: 5px;
  overflow: hidden;
}

.room-image img {
  width: 100%;
  height: 150px;
  object-fit: cover;
}

.room-details {
  padding: 10px;
}

.room-details h4 {
  margin: 0;
  color: #333;
}

.booking-section {
  flex: 1;
  min-width: 300px;
}

.hotel-thumbnail {
  margin-bottom: 15px;
}

.thumbnail-img {
  width: 100%;
  max-height: 100px;
  object-fit: cover;
  border-radius: 5px;
}

.booking-form {
  border: 1px solid #ddd;
  border-radius: 5px;
  padding: 15px;
  margin-bottom: 20px;
}

.date-selection {
  display: flex;
  justify-content: space-between;
  margin: 15px 0;
}

.check-in,
.check-out {
  flex: 1;
}

.check-in label,
.check-out label {
  display: block;
  font-size: 0.8rem;
  margin-bottom: 5px;
}

.check-in input,
.check-out input {
  width: 90%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 3px;
}

.price-details {
  margin: 20px 0;
}

.price-per-night,
.booking-fee,
.total-price {
  display: flex;
  justify-content: space-between;
  margin: 10px 0;
}

.total-price {
  font-weight: bold;
  border-top: 1px solid #ddd;
  padding-top: 10px;
}

.reserve-btn {
  width: 100%;
  background-color: #8b4513;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
}

.tax-note {
  text-align: right;
  font-size: 0.8rem;
  color: #777;
  margin-top: 5px;
}

.map-section {
  margin-top: 20px;
}

.map-container {
  position: relative;
  height: 200px;
  border-radius: 5px;
  overflow: hidden;
}

.map-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.map-marker {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 2rem;
}

.direction-location {
  background-color: #8b4513;
  color: white;
  padding: 10px;
  text-align: center;
  border-radius: 0 0 5px 5px;
}

.direction-location h3 {
  margin: 0;
  font-size: 1rem;
}

.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.9);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}

.modal-content {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  width: 100vw;
  height: 100vh;
  position: relative;
}

.modal-img-container {
  flex: 1 1 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
}

.modal-image {
  max-width: 90vw;
  max-height: 90vh;
  object-fit: contain;
  border-radius: 8px;
  box-shadow: 0 2px 16px rgba(0, 0, 0, 0.5);
  background: #fff;
}

.modal-close {
  position: fixed;
  top: 32px;
  right: 32px;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  width: 48px;
  height: 48px;
  font-size: 32px;
  border-radius: 50%;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1001;
}

.modal-nav {
  background: rgba(0, 0, 0, 0.7);
  color: white;
  width: 56px;
  height: 56px;
  font-size: 32px;
  border-radius: 50%;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  transition: 0.2s;
}

.modal-nav.left {
  position: fixed;
  left: 32px;
  top: 50%;
  transform: translateY(-50%);
}

.modal-nav.right {
  position: fixed;
  right: 32px;
  top: 50%;
  transform: translateY(-50%);
}

@media (max-width: 768px) {
  .hotel-header {
    flex-direction: column;
  }

  .message-box {
    width: 100%;
    margin-top: 10px;
  }

  .date-selection {
    flex-direction: column;
  }

  .check-in,
  .check-out {
    margin-bottom: 10px;
  }

  .room-listings {
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  }
}
</style>
