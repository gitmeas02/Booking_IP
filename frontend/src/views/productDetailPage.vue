<template>
  <div v-if="hotelDetail" class="booking-container">
    <div class="hotel-listing">
      <div class="hotel-header">
        <div>
          <h1 class="hotel-name">{{ hotelDetail.property_name }}</h1>
          <p class="hotel-location">
            Location: {{ hotelDetail.location.street }}
          </p>
        </div>
        <div class="review-score">
          <div class="score">8</div>
          <div class="score-text">0 reviews</div>
        </div>
      </div>

      <div class="main-image-container">
        <img
          v-if="hotelDetail.photos && hotelDetail.photos.length"
          :src="getImageUrl(hotelDetail.photos[currentImageIndex].url)"
          alt="Hotel main image"
          class="main-image"
          @click="openPhotoModal(currentImageIndex)"
          @error="handleImageError"
          @load="handleImageLoad"
        />
        <div v-if="imageLoading" class="image-loading">Loading...</div>
        <div v-if="imageError" class="image-error">
          <span>Image unavailable</span>
        </div>
        <!-- Debug button -->
        <button 
          v-if="hotelDetail?.photos?.length"
          @click="testImageLoad(getImageUrl(hotelDetail.photos[0]?.url))"
          class="debug-button"
        >
          Test Image Load
        </button>
        <div class="navigation-dots">
          <span
            v-for="(_, index) in hotelDetail.photos"
            :key="index"
            :class="['dot', { active: index === currentImageIndex }]"
            @click="
              setCurrentImage(index);
              openPhotoModal(index);
            "
          ></span>
        </div>
      </div>

      <div class="thumbnail-gallery">
        <div
          v-for="(image, index) in hotelDetail.photos"
          :key="index"
          class="thumbnail-container"
          @click="openPhotoModal(index)"
        >
          <img
            :src="getImageUrl(image.url)"
            alt="Room thumbnail"
            class="thumbnail"
            @error="handleThumbnailError"
            @load="handleThumbnailLoad"
          />
          <div
            v-if="index === hotelDetail.photos.length - 1"
            class="photo-count"
          >
            <span>{{ hotelDetail.photos.length }} photos</span>
          </div>
        </div>
      </div>

      <div style="margin-top: 20px"></div>

      <div class="hotel-header-section">
        <div class="hotel-main-info">
          <div class="hotel-badges">
            <span class="best-seller-badge">Best seller</span>
          </div>
          <h1 class="hotel-name">{{ hotelDetail.property_name }}</h1>
          <div class="hotel-meta">
            <div class="rating-stars">⭐⭐⭐⭐</div>
            <span class="location-text"
              >{{ hotelDetail.location?.street }},
              {{ hotelDetail.location?.city }}</span
            >
            <a
              :href="
                'https://www.google.com/maps?q=' +
                encodeURIComponent(hotelDetail.location?.street)
              "
              target="_blank"
              class="see-map-link"
              >SEE MAP</a
            >
          </div>
        </div>
        <div class="hotel-review-section">
          <div class="priceinfo-container">
            <div class="price-info">
              <button class="view-deal-btn">VIEW MY DEAL</button>
            </div>
          </div>
        </div>
      </div>

      <div class="navigation-tabs">
        <button
          class="tab-btn"
          :class="{ active: activeTab === 'overview' }"
          @click="activeTab = 'overview'"
        >
          Overview
        </button>
        <button
          class="tab-btn"
          :class="{ active: activeTab === 'rooms' }"
          @click="activeTab = 'rooms'"
        >
          Rooms
        </button>
        <button
          class="tab-btn"
          :class="{ active: activeTab === 'recommendations' }"
          @click="activeTab = 'recommendations'"
        >
          Trip recommendations
        </button>
        <button
          class="tab-btn"
          :class="{ active: activeTab === 'comments' }"
          @click="scrollToComments"
        >
          Comments
        </button>
        <button
          class="tab-btn"
          :class="{ active: activeTab === 'facilities' }"
          @click="activeTab = 'facilities'"
        >
          Facilities
        </button>
        <button
          class="tab-btn"
          :class="{ active: activeTab === 'reviews' }"
          @click="activeTab = 'reviews'"
        >
          Reviews
        </button>
        <button
          class="tab-btn"
          :class="{ active: activeTab === 'location' }"
          @click="activeTab = 'location'"
        >
          Location
        </button>
        <button
          class="tab-btn"
          :class="{ active: activeTab === 'policies' }"
          @click="activeTab = 'policies'"
        >
          Policies
        </button>
      </div>

      <div class="main-content-area">
        <div class="left-content">
          <div v-if="activeTab === 'overview'">
            <div class="about-section">
              <h3>About us</h3>
              <p>
                {{
                  hotelDetail.description?.[0] ||
                  "Nestled in Kampot City Center, Bamboo Bungalows offers two travellers an ideal blend of relaxation and exploration. Enjoy riverside dining..."
                }}
              </p>
              <a href="#" class="read-more-link">Read more</a>
            </div>

            <div class="room-selection-section">
              <h3>Select your room</h3>
              <div class="room-filters">
                <button class="filter-btn">🚭 Non-smoking (21)</button>
                <button class="filter-btn">📏 ≥ 20 m² (20)</button>
                <button class="filter-btn">🛏️ ≥ 40 m² (4)</button>
                <button class="filter-btn">💳 Pay later option (15)</button>
                <button class="filter-btn">❌ Free cancellation (26)</button>
                <button class="filter-btn">🏨 Pay at the hotel (19)</button>
                <a href="#" class="show-more-filters">Show 1 more</a>
              </div>
              <div class="room-cards">
                <RoomTypeCard
                  v-for="room in uniqueRoomTypesWithCountAndAvailableRoomType"
                  :key="room.id"
                  :room="room"
                  :roomCount="room.count"
                  :selectedStartDate="selectedStartDate"
                  :selectedEndDate="selectedEndDate"
                  @reserve="reserveRoom"
                />
              </div>
            </div>
          </div>

          <div v-if="activeTab === 'rooms'" class="rooms-tab-content">
            <h3>Available Rooms</h3>
            <div class="room-cards">
              <RoomTypeCard
                v-for="room in uniqueRoomTypesWithCountAndAvailableRoomType"
                :key="room.id"
                :room="room"
                :roomCount="room.count"
                :selectedStartDate="selectedStartDate"
                :selectedEndDate="selectedEndDate"
                @reserve="reserveRoom"
              />
            </div>
          </div>

          <div v-if="activeTab === 'location'" class="location-tab-content">
            <h3>Location & Nearby</h3>
            <p>
              {{ hotelDetail.location?.address }},
              {{ hotelDetail.location?.city }}
            </p>
          </div>

          <div v-if="activeTab === 'policies'" class="policies-tab-content">
            <h3>Hotel Policies</h3>
            <p>Check-in: 3:00 PM - 11:00 PM</p>
            <p>Check-out: 12:00 PM</p>
            <p>
              Cancellation policy and other hotel policies would be displayed
              here.
            </p>
          </div>

          <div
            v-if="activeTab === 'recommendations'"
            class="recommendations-tab-content"
          >
            <h3>Trip Recommendations</h3>
            <p>Discover nearby attractions and activities for your stay.</p>
          </div>

          <div v-if="activeTab === 'reviews'" class="reviews-tab-content">
            <h3>Guest Reviews</h3>
            <p>Detailed guest reviews and ratings would be displayed here.</p>
          </div>

          <div v-if="activeTab === 'comments'" class="comments-tab-content">
            <div class="comments-room-section">
              <div class="section-header">
                <h3 class="section-title">Available Rooms</h3>
                <p class="section-subtitle">
                  Book one of these rooms while browsing reviews
                </p>
              </div>

              <div class="room-cards">
                <RoomTypeCard
                  v-for="room in uniqueRoomTypesWithCountAndAvailableRoomType"
                  :key="room.id"
                  :room="room"
                  :selectedStartDate="selectedStartDate"
                  :selectedEndDate="selectedEndDate"
                  @reserve="reserveRoom"
                />
              </div>
            </div>
          </div>
        </div>

        <div class="right-sidebar">
          <div class="review-summary-card">
            <div class="review-header">
              <div class="review-score-large">0</div>
              <div class="review-info">
                <div class="review-status">
                  <span class="review-status-text">Very good</span>
                </div>
                <div class="review-count-text">100 reviews</div>
              </div>
            </div>

            <div class="review-categories">
              <div class="category-item">
                <span class="category-label">Location 8.4</span>
                <span class="category-badge">Excellent</span>
              </div>
              <div class="category-item">
                <span class="category-label">Service 8.3</span>
              </div>
              <div class="category-item">
                <span class="category-label">Value for money 7.8</span>
              </div>
              <div class="category-item">
                <span class="category-label">Cleanliness 7.7</span>
              </div>
            </div>

            <div class="guest-quote">
              <div class="quote-text">"Great place to relax"</div>
              <div class="quote-meta">👥 ●●●○○</div>
            </div>

            <a href="#" class="read-reviews-link">Read all reviews</a>
          </div>

          <div class="map-card">
            <div class="map-container">
              <iframe
                :src="`https://www.google.com/maps?q=${encodeURIComponent(
                  hotelDetail.location.street
                )},${encodeURIComponent(
                  hotelDetail.location.city
                )}&output=embed`"
                width="100%"
                height="300"
                style="border: 0"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                class="map-iframe"
              ></iframe>
              <div class="map-marker">📍</div>
              <a
                :href="`https://www.google.com/maps?q=${encodeURIComponent(
                  hotelDetail.location.street
                )},${encodeURIComponent(hotelDetail.location.city)}`"
                target="_blank"
                class="see-map-btn"
                >SEE MAP</a
              >
            </div>

            <div class="location-score">
              <span class="score">8.4</span>
              <span class="label">Excellent</span>
              <span class="subtext">1 location rating score</span>
            </div>

            <div class="parking-info">
              <span class="parking-icon">🅿️</span>
              <span>Parking</span>
              <span class="free-badge">FREE</span>
            </div>

            <div class="landmarks-section">
              <h4>Popular landmarks</h4>
              <div class="landmark-item">
                <span class="landmark-icon">🏛️</span>
                <span class="landmark-name">Farm Link</span>
                <span class="landmark-distance">770 m</span>
              </div>
              <div class="landmark-item">
                <span class="landmark-icon">🕌</span>
                <span class="landmark-name">Bokor Mountain</span>
                <span class="landmark-distance">1.8 km</span>
              </div>
              <div class="landmark-item">
                <span class="landmark-icon">🏛️</span>
                <span class="landmark-name">Phnom Chhnork Cave Temple</span>
                <span class="landmark-distance">11.5 km</span>
              </div>
              <div class="landmark-item">
                <span class="landmark-icon">🏔️</span>
                <span class="landmark-name">Phnom Bokor National Park</span>
                <span class="landmark-distance">16.6 km</span>
              </div>

              <h4>Closest landmarks</h4>
              <div class="landmark-item">
                <span class="landmark-icon">🏛️</span>
                <span class="landmark-name">Farm Link</span>
                <span class="landmark-distance">770 m</span>
              </div>
              <div class="landmark-item">
                <span class="landmark-icon">🏞️</span>
                <span class="landmark-name">Kampot River</span>
                <span class="landmark-distance">1.1 km</span>
              </div>
              <div class="landmark-item">
                <span class="landmark-icon">🏪</span>
                <span class="landmark-name">Phsar-meay Kampot</span>
                <span class="landmark-distance">1.1 km</span>
              </div>
              <div class="landmark-item">
                <span class="landmark-icon">🌿</span>
                <span class="landmark-name">Bokor Office</span>
                <span class="landmark-distance">1.2 km</span>
              </div>
              <div class="landmark-item">
                <span class="landmark-icon">🏪</span>
                <span class="landmark-name">Rusty Mart</span>
                <span class="landmark-distance">1.3 km</span>
              </div>
              <div class="landmark-item">
                <span class="landmark-icon">🏛️</span>
                <span class="landmark-name">Bokor Clinic</span>
                <span class="landmark-distance">1.3 km</span>
              </div>
            </div>

            <a href="#" class="see-nearby-link">See nearby places</a>
          </div>
        </div>
      </div>

      <!-- Modal Viewer -->
      <div v-if="modalVisible" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
          <button class="modal-close" @click="closeModal">
            <X stroke-width="3" />
          </button>
          <button
            class="modal-nav left"
            @click="prevPhoto"
            :disabled="currentPhotoIndex === 0"
          >
            <ChevronLeft />
          </button>
          <img 
            :src="getImageUrl(hotelDetail.photos[currentPhotoIndex].url)" 
            class="modal-image"
            @error="handleModalImageError"
            @load="handleModalImageLoad"
          />
          <button
            class="modal-nav right"
            @click="nextPhoto"
            :disabled="currentPhotoIndex === images.length - 1"
          >
            <ChevronRight />
          </button>
        </div>
      </div>

      <div ref="commentsSection" class="comments-bottom-section">
        <CommentSection />
      </div>
    </div>
  </div>

  <div v-else>
    <p>Loading room and hotel info...</p>
  </div>
</template>

<script setup>
import { useRoomStore } from "@/stores/store";
import { useSearchStore } from "@/stores/useSearchStore";
import dayjs from "dayjs";
import { ChevronLeft, ChevronRight, X } from "lucide-vue-next";
import { computed, onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import CommentSection from "./commentSection.vue";
import RoomTypeCard from "./RoomTypeCard.vue";

const router = useRouter();
const hotelStore = useRoomStore();
const searchStore = useSearchStore();
const route = useRoute();
const hotelId = route.params.id;
const hotelDetail = ref(null);
const activeTab = ref("overview");
const modalVisible = ref(false);
const currentImageIndex = ref(0);
const currentPhotoIndex = ref(0);
const imageLoading = ref(false);
const imageError = ref(false);

// Image URL helper function
const getImageUrl = (imageUrl) => {
  if (!imageUrl) {
    console.warn('No image URL provided');
    return '/placeholder-image.jpg'; // fallback image
  }
  
  // Handle different URL formats
  if (imageUrl.startsWith('http')) {
    return imageUrl;
  }
  
  // Clean up the image URL - remove 'ownerimages/' prefix if present
  let cleanImageUrl = imageUrl;
  if (cleanImageUrl.startsWith('ownerimages/')) {
    cleanImageUrl = cleanImageUrl.replace('ownerimages/', '');
  }
  
  // Use Laravel image proxy instead of direct MinIO access
  const fullUrl = `http://localhost:8100/api/images/${cleanImageUrl}`;
  console.log('Generated image URL:', fullUrl);
  console.log('Original imageUrl:', imageUrl);
  console.log('Cleaned imageUrl:', cleanImageUrl);
  return fullUrl;
};

// Image loading handlers
const handleImageError = (event) => {
  console.error('Main image failed to load:', event.target.src);
  imageError.value = true;
  imageLoading.value = false;
  
  // Create a fallback image with CSS
  event.target.style.display = 'none';
  const parent = event.target.parentElement;
  if (parent && !parent.querySelector('.fallback-image')) {
    const fallback = document.createElement('div');
    fallback.className = 'fallback-image';
    fallback.innerHTML = `
      <div class="fallback-content">
        <div class="fallback-icon">🏨</div>
        <div class="fallback-text">Image not available</div>
      </div>
    `;
    parent.appendChild(fallback);
  }
};

const handleImageLoad = (event) => {
  console.log('Main image loaded successfully:', event.target.src);
  imageError.value = false;
  imageLoading.value = false;
};

const handleThumbnailError = (event) => {
  console.error('Thumbnail failed to load:', event.target.src);
  event.target.style.display = 'none';
  const parent = event.target.parentElement;
  if (parent && !parent.querySelector('.fallback-thumbnail')) {
    const fallback = document.createElement('div');
    fallback.className = 'fallback-thumbnail';
    fallback.innerHTML = `
      <div class="fallback-content">
        <div class="fallback-icon">🏨</div>
      </div>
    `;
    parent.appendChild(fallback);
  }
};

const handleThumbnailLoad = (event) => {
  console.log('Thumbnail loaded successfully:', event.target.src);
};

const handleModalImageError = (event) => {
  console.error('Modal image failed to load:', event.target.src);
  event.target.style.display = 'none';
  const parent = event.target.parentElement;
  if (parent && !parent.querySelector('.fallback-modal')) {
    const fallback = document.createElement('div');
    fallback.className = 'fallback-modal';
    fallback.innerHTML = `
      <div class="fallback-content">
        <div class="fallback-icon">🏨</div>
        <div class="fallback-text">Image not available</div>
      </div>
    `;
    parent.appendChild(fallback);
  }
};

const handleModalImageLoad = (event) => {
  console.log('Modal image loaded successfully:', event.target.src);
};

// Debug function to test image loading
const testImageLoad = (url) => {
  console.log('Testing image load for URL:', url);
  const img = new Image();
  img.onload = () => console.log('✅ Image loaded successfully:', url);
  img.onerror = () => console.log('❌ Image failed to load:', url);
  img.src = url;
};

// Use search store for dates - fallback to defaults if not set
const selectedStartDate = computed(() => searchStore.startDate || dayjs().format('YYYY-MM-DD'));
const selectedEndDate = computed(() => searchStore.endDate || dayjs().add(1, 'day').format('YYYY-MM-DD'));

const uniqueRoomTypesWithCountAndAvailableRoomType = computed(() => {
  if (!hotelDetail.value?.room_types) return [];

  const seen = new Map();

  hotelDetail.value.room_types.forEach((room) => {
    const isBlocked =
      room.block_dates?.some((block) => {
        const blockStart = dayjs(block.start_date);
        const blockEnd = dayjs(block.end_date);
        const selectStart = dayjs(selectedStartDate.value);
        const selectEnd = dayjs(selectedEndDate.value);

        return blockStart.isBefore(selectEnd) && blockEnd.isAfter(selectStart);
      }) ?? false;

    if (!isBlocked) {
      const key = `${room.name}-${room.default_price}`;
      if (seen.has(key)) {
        const existing = seen.get(key);
        existing.count++;
      } else {
        seen.set(key, {
          ...room,
          count: 1,
        });
      }
    }
  });

  return Array.from(seen.values());
});

const images = computed(() => {
  return (
    hotelDetail.value?.photos?.map(
      (photo) => getImageUrl(photo.url)
    ) || []
  );
});

const openPhotoModal = (index) => {
  currentPhotoIndex.value = index;
  modalVisible.value = true;
};

const closeModal = () => {
  modalVisible.value = false;
};

const prevPhoto = () => {
  if (currentPhotoIndex.value > 0) {
    currentPhotoIndex.value--;
  }
};

const nextPhoto = () => {
  if (currentPhotoIndex.value < images.value.length - 1) {
    currentPhotoIndex.value++;
  }
};

const setCurrentImage = (index) => {
  currentImageIndex.value = index;
};

const scrollToComments = () => {
  activeTab.value = "comments";
  const commentsSection = document.querySelector(".comments-bottom-section");
  if (commentsSection) {
    commentsSection.scrollIntoView({ behavior: "smooth" });
  }
};

const reserveRoom = async (room) => { // select rooms this mean we get rooms [4,2,.eg.]
  try {
    // Fetch available room IDs from backend
    const url = new URL("http://localhost:8100/api/rooms/available");
    url.searchParams.append("name", room.name);
    url.searchParams.append("default_price", room.default_price);
    url.searchParams.append("start_date", selectedStartDate.value);
    url.searchParams.append("end_date", selectedEndDate.value);
    url.searchParams.append("quantity", room.count || 1);
    
    const res = await fetch(url, {
      method: "GET",
      headers: { 
        "Content-Type": "application/json",
        "Accept": "application/json"
      }
    });
    
    console.log("Response status:", res.status);
    
    if (!res.ok) {
      throw new Error(`HTTP error! status: ${res.status}`);
    }
    
    const data = await res.json();
    console.log("Data received:", data);
    
    if (!data.room_ids || data.room_ids.length === 0) {
      alert("No available rooms for your selection.");
      return;
    }
    
    // Go to checkout with roomIds and booking info
    router.push({
      name: "checkout",
      query: {
        roomIds: data.room_ids.join(","),
        hotelId: hotelDetail.value.id,
        checkin: selectedStartDate.value,
        checkout: selectedEndDate.value,
        quantity: room.count || 1,
        roomName: room.name,
        roomPrice: room.default_price,
        capacity: room.capacity,
      },
    });
  } catch (err) {
    console.error("Reservation error:", err);
    alert("Failed to reserve room. Please try again.");
  }
};

onMounted(async () => {
  try {
    imageLoading.value = true;
    console.log("Fetching hotel with ID:", hotelId);
    
    hotelDetail.value = await hotelStore.fetchHotelById(hotelId);
    console.log("Hotel Detail:", hotelDetail.value);
    
    if (hotelDetail.value?.photos && hotelDetail.value.photos.length > 0) {
      console.log("Photos found:", hotelDetail.value.photos);
      console.log("First photo URL:", hotelDetail.value.photos[0]?.url);
      console.log("Generated image URL:", getImageUrl(hotelDetail.value.photos[0]?.url));
    } else {
      console.warn("No photos found in hotel detail");
    }
    
    imageLoading.value = false;
  } catch (error) {
    console.error("Failed to load hotel details:", error);
    imageLoading.value = false;
  }
});
</script>
<style scoped>
.booking-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
    "Helvetica Neue", Arial, sans-serif;
  color: #262626;
  background-color: #fff;
}

/* Breadcrumb */
.breadcrumb {
  font-size: 14px;
  margin: 16px 0;
  color: #0071c2;
}

.breadcrumb span:not(:last-child) {
  color: #0071c2;
  cursor: pointer;
}

.breadcrumb span:not(:last-child):hover {
  text-decoration: underline;
}

.breadcrumb span:last-child {
  color: #262626;
}

/* Image Gallery */
.image-gallery-container {
  margin-bottom: 24px;
}

.image-gallery-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 8px;
  height: 400px;
  border-radius: 8px;
  overflow: hidden;
}

.main-image-wrapper {
  position: relative;
  height: 100%;
  border-radius: 8px;
  overflow: hidden;
}

.main-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  cursor: pointer;
  transition: transform 0.2s;
}

.main-image:hover {
  transform: scale(1.02);
}

.see-all-photos-btn {
  position: absolute;
  bottom: 16px;
  right: 16px;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: background 0.2s;
}

.see-all-photos-btn:hover {
  background: rgba(0, 0, 0, 0.8);
}

.thumbnail-grid {
  display: grid;
  grid-template-rows: repeat(2, 1fr);
  grid-template-columns: repeat(2, 1fr);
  gap: 8px;
  height: 100%;
}

.thumbnail-wrapper {
  position: relative;
  cursor: pointer;
  overflow: hidden;
  border-radius: 4px;
}

.thumbnail {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.2s;
}

.thumbnail:hover {
  transform: scale(1.05);
}

.more-photos-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 16px;
}

/* Hotel Listing Section */
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
  background: #f5f5f5;
  display: flex;
  align-items: center;
  justify-content: center;
}

.main-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  cursor: pointer;
}

.image-loading {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: #666;
  font-size: 16px;
}

.image-error {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: #999;
  font-size: 14px;
  text-align: center;
}

.fallback-image,
.fallback-thumbnail,
.fallback-modal {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
}

.fallback-content {
  text-align: center;
  color: #666;
}

.fallback-icon {
  font-size: 48px;
  margin-bottom: 8px;
  opacity: 0.7;
}

.fallback-text {
  font-size: 14px;
  font-weight: 500;
}

.fallback-thumbnail .fallback-icon {
  font-size: 24px;
  margin-bottom: 4px;
}

.fallback-modal .fallback-icon {
  font-size: 64px;
  margin-bottom: 12px;
}

.fallback-modal .fallback-text {
  font-size: 18px;
}

.debug-button {
  position: absolute;
  top: 10px;
  right: 10px;
  background: #007bff;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
  z-index: 10;
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
  background: #f5f5f5;
  display: flex;
  align-items: center;
  justify-content: center;
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

/* Modal Styles */
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

.modal-nav:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Hotel Header */
.hotel-header-section {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 16px;
  padding-bottom: 16px;
  border-bottom: 1px solid #e7e7e7;
}

.hotel-main-info {
  flex: 1;
}

.hotel-badges {
  margin-bottom: 8px;
}

.best-seller-badge {
  background: #ff8c00;
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
}

.hotel-name {
  font-size: 32px;
  font-weight: 700;
  margin: 8px 0;
  color: #262626;
  line-height: 1.2;
}

.hotel-meta {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-top: 8px;
}

.rating-stars {
  color: #ffa500;
  font-size: 16px;
}

.location-text {
  font-size: 14px;
  color: #262626;
}

.see-map-link {
  color: #0071c2;
  text-decoration: none;
  font-size: 14px;
  font-weight: 600;
}

.see-map-link:hover {
  text-decoration: underline;
}

.hotel-review-section {
  display: flex;
  align-items: flex-start;
  gap: 16px;
}

.review-score-card {
  background: #003580;
  color: white;
  padding: 12px 16px;
  border-radius: 8px;
  text-align: center;
  min-width: 120px;
}

.score-number {
  font-size: 28px;
  font-weight: bold;
  line-height: 1;
}

.score-label {
  font-size: 14px;
  margin: 4px 0 2px 0;
}

.review-count {
  font-size: 12px;
  opacity: 0.9;
}
.priceinfo-container {
  display: flex;
  flex-direction: column;
}
.price-info {
  text-align: right;
}

.currency {
  font-size: 14px;
  color: #6b6b6b;
}

.price {
  font-size: 28px;
  font-weight: bold;
  color: #262626;
  margin-left: 4px;
}

.view-deal-btn {
  background: #0071c2;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 600;
  font-size: 14px;
  margin-top: 8px;
  transition: background 0.2s;
}

.view-deal-btn:hover {
  background: #005999;
}

/* Navigation Tabs */
.navigation-tabs {
  display: flex;
  border-bottom: 2px solid #e7e7e7;
  margin-bottom: 24px;
}

.tab-btn {
  background: transparent;
  border: none;
  padding: 16px 24px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  color: #6b6b6b;
  border-bottom: 2px solid transparent;
  transition: all 0.2s;
}

.tab-btn:hover {
  color: #0071c2;
}

.tab-btn.active {
  color: #0071c2;
  border-bottom-color: #0071c2;
}

/* Main Content */
.main-content-area {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 32px;
}

.left-content {
  min-width: 0;
}

/* Highlights */
.highlights-section {
  margin-bottom: 32px;
}

.highlights-section h3 {
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 16px;
  color: #262626;
}

.highlights-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
}

.highlight-item {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 12px;
  border: 1px solid #e7e7e7;
  border-radius: 8px;
}

.highlight-icon {
  font-size: 20px;
  flex-shrink: 0;
}

.highlight-title {
  font-weight: 600;
  font-size: 14px;
  margin-bottom: 4px;
}

.highlight-subtitle {
  font-size: 12px;
  color: #6b6b6b;
}

/* Staycation Offers */
.staycation-section {
  margin-bottom: 32px;
}

.staycation-section h3 {
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 8px;
  color: #262626;
}

.staycation-subtitle {
  font-size: 14px;
  color: #6b6b6b;
  margin-bottom: 16px;
}

.offers-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 16px;
}

.offer-item {
  padding: 16px;
  border: 1px solid #e7e7e7;
  border-radius: 8px;
}

.offer-content h4 {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 12px;
  color: #262626;
}

.offer-content p {
  font-size: 14px;
  margin-bottom: 8px;
  color: #262626;
}

.see-amenities-link {
  color: #0071c2;
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
}

/* Facilities */
.facilities-section {
  margin-bottom: 32px;
}

.facilities-section h3 {
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 16px;
  color: #262626;
}

.facilities-columns {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px 32px;
}

.facility-item {
  font-size: 14px;
  margin-bottom: 8px;
  color: #262626;
}

/* About Section */
.about-section {
  margin-bottom: 32px;
}

.about-section h3 {
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 16px;
  color: #262626;
}

.about-section p {
  font-size: 14px;
  line-height: 1.5;
  color: #262626;
  margin-bottom: 8px;
}

.read-more-link {
  color: #0071c2;
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
}

.demand-notice {
  background: #fff4e6;
  border: 1px solid #ff8c00;
  border-radius: 8px;
  padding: 16px;
  margin-top: 16px;
  display: flex;
  align-items: center;
  gap: 12px;
}

.fire-icon {
  font-size: 20px;
  color: #ff8c00;
}

.demand-notice strong {
  color: #ff8c00;
}

.demand-notice p {
  margin: 4px 0 0 0;
  font-size: 14px;
}

/* Room Selection */
.room-selection-section h3 {
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 16px;
  color: #262626;
}

.room-filters {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 16px;
}

.filter-btn {
  background: #f5f5f5;
  color: #262626;
  border: 1px solid #e7e7e7;
  padding: 8px 12px;
  border-radius: 20px;
  cursor: pointer;
  font-size: 12px;
  transition: all 0.2s;
}

.filter-btn:hover {
  background: #e7e7e7;
}

.show-more-filters {
  color: #0071c2;
  text-decoration: none;
  font-size: 12px;
  padding: 8px 12px;
  align-self: center;
}

.room-availability-notice {
  background: #fff2f2;
  border: 1px solid #e74c3c;
  border-radius: 8px;
  padding: 12px;
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.warning-icon {
  color: #e74c3c;
}

/* Room Cards */

/* Right Sidebar */
.right-sidebar {
  min-width: 300px;
}

.review-summary-card {
  background: white;
  border: 1px solid #e7e7e7;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 24px;
}

.review-header {
  display: flex;
  align-items: center;
  margin-bottom: 16px;
}

.review-score-large {
  font-size: 36px;
  font-weight: bold;
  color: #003580;
  margin-right: 16px;
}

.review-status {
  font-size: 16px;
  font-weight: 600;
  color: #262626;
}

.review-count-text {
  font-size: 14px;
  color: #6b6b6b;
}

.review-categories {
  margin-bottom: 16px;
}

.category-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
  font-size: 14px;
}

.category-label {
  color: #262626;
}

.category-badge {
  background: #0e7b0e;
  color: white;
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 12px;
}

.guest-quote {
  background: #f8f9fa;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 16px;
  font-style: italic;
}

.quote-text {
  font-size: 14px;
  color: #262626;
}

.quote-meta {
  font-size: 12px;
  color: #6b6b6b;
  margin-top: 4px;
}

.read-reviews-link {
  color: #0071c2;
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
}

/* Map Card */
.map-card {
  background: white;
  border: 1px solid #e7e7e7;
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 24px;
}

.map-container {
  position: relative;
  height: 200px;
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
  font-size: 24px;
}

.see-map-btn {
  position: absolute;
  bottom: 12px;
  right: 12px;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}

.location-score {
  background: #f8f9fa;
  padding: 16px;
  text-align: center;
}

.location-score .score {
  font-size: 28px;
  font-weight: bold;
  color: #0e7b0e;
}

.location-score .label {
  font-size: 14px;
  font-weight: 600;
  color: #262626;
}

.location-score .subtext {
  font-size: 12px;
  color: #6b6b6b;
}

.location-badge {
  display: flex;
  align-items: center;
  background: #0e7b0e;
  color: white;
  padding: 8px 12px;
  margin: 16px;
  border-radius: 4px;
  font-size: 14px;
}

.location-icon {
  margin-right: 8px;
}

.parking-info {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 16px 16px;
  font-size: 14px;
}

.free-badge {
  background: #0e7b0e;
  color: white;
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
}

.landmarks-section {
  padding: 16px;
  border-top: 1px solid #e7e7e7;
}

.landmarks-section h4 {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 12px;
  color: #262626;
}

.landmark-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 8px;
  font-size: 14px;
}

.landmark-name {
  flex: 1;
  margin-left: 8px;
}

.landmark-distance {
  color: #6b6b6b;
  font-size: 12px;
}

.see-nearby-link {
  color: #0071c2;
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
}

/* Bottom Booking Bar */
.bottom-booking-bar {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background: white;
  border-top: 1px solid #e7e7e7;
  padding: 12px 20px;
  box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
  z-index: 100;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.booking-bar-content {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 32px;
}

.booking-info {
  flex: 1;
  max-width: 800px;
  display: flex;
  justify-content: center;
}

.booking-form {
  display: flex;
  align-items: center;
  background: #ffffff;
  border: 2px solid #e7e7e7;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  width: 100%;
  max-width: 700px;
}

.input-group {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding: 8px 16px;
  position: relative;
}

.input-label {
  font-size: 10px;
  font-weight: 600;
  color: #0071c2;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 2px;
}

.date-input {
  border: none;
  background: transparent;
  font-size: 13px;
  font-weight: 500;
  color: #262626;
  outline: none;
  width: 100%;
  cursor: pointer;
  padding: 0;
}

.date-input:focus {
  outline: none;
}

.guests-selector {
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 0;
  width: 100%;
}

.guests-info {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.guests-count,
.rooms-count {
  font-size: 13px;
  font-weight: 500;
  color: #262626;
  display: flex;
  align-items: center;
  gap: 4px;
}

.input-separator {
  width: 1px;
  height: 32px;
  background: #e7e7e7;
  margin: 0;
}

.booking-action {
  display: flex;
  align-items: center;
  gap: 16px;
  flex-shrink: 0;
}

.price-display {
  text-align: right;
  padding-right: 4px;
}

.price-label {
  font-size: 11px;
  color: #6b6b6b;
  margin-bottom: 1px;
}

.price-amount {
  font-size: 18px;
  font-weight: 700;
  color: #262626;
  line-height: 1;
}

.price-period {
  font-size: 11px;
  color: #6b6b6b;
  margin-top: 1px;
}

.update-btn {
  background: linear-gradient(135deg, #0071c2 0%, #005999 100%);
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  font-size: 13px;
  min-width: 160px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 113, 194, 0.2);
}

.update-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 16px rgba(0, 113, 194, 0.3);
  background: linear-gradient(135deg, #005999 0%, #003d82 100%);
}

.update-btn:active {
  transform: translateY(0);
}

.btn-text {
  font-weight: 600;
  letter-spacing: 0.5px;
}

.btn-icon {
  font-size: 16px;
  transition: transform 0.2s ease;
}

.update-btn:hover .btn-icon {
  transform: translateX(2px);
}

/* Scrolled State */
.bottom-booking-bar.scrolled {
  background: linear-gradient(
    135deg,
    rgba(25, 48, 65, 0.95) 0%,
    rgba(11, 48, 75, 0.95) 100%
  );
  backdrop-filter: blur(20px);
  border-top: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.3);
}

.bottom-booking-bar.scrolled .booking-form {
  background: rgba(255, 255, 255, 0.95);
  border: 2px solid rgba(255, 255, 255, 0.3);
  backdrop-filter: blur(10px);
}

.bottom-booking-bar.scrolled .booking-form:hover {
  border-color: rgba(255, 255, 255, 0.6);
  background: rgba(255, 255, 255, 1);
}

.bottom-booking-bar.scrolled .input-label {
  color: #0071c2;
}

.bottom-booking-bar.scrolled .date-input,
.bottom-booking-bar.scrolled .guests-count,
.bottom-booking-bar.scrolled .rooms-count {
  color: #262626;
}

.bottom-booking-bar.scrolled .input-separator {
  background: rgba(231, 231, 231, 0.6);
}

.bottom-booking-bar.scrolled .price-display {
  color: #ffffff;
}

.bottom-booking-bar.scrolled .price-label,
.bottom-booking-bar.scrolled .price-period {
  color: rgba(255, 255, 255, 0.8);
}

.bottom-booking-bar.scrolled .price-amount {
  color: #ffffff;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.bottom-booking-bar.scrolled .update-btn {
  background: linear-gradient(135deg, #ff8c00 0%, #e67e00 100%);
  border: 2px solid #ff8c00;
  box-shadow: 0 4px 16px rgba(255, 140, 0, 0.4);
}

.bottom-booking-bar.scrolled .update-btn:hover {
  background: linear-gradient(135deg, #e67e00 0%, #cc7000 100%);
  border-color: #e67e00;
  box-shadow: 0 6px 20px rgba(255, 140, 0, 0.5);
}

/* Enhanced Mobile Responsive */
@media (max-width: 768px) {
  .bottom-booking-bar {
    padding: 16px 12px;
  }

  .booking-bar-content {
    flex-direction: column;
    gap: 16px;
  }

  .booking-info {
    width: 100%;
    max-width: none;
  }

  .booking-form {
    flex-direction: column;
    align-items: stretch;
  }

  .input-group {
    padding: 12px 16px;
    border-bottom: 1px solid #f0f0f0;
  }

  .input-group:last-child {
    border-bottom: none;
  }

  .input-separator {
    display: none;
  }

  .booking-action {
    width: 100%;
    justify-content: space-between;
    align-items: center;
  }

  .price-display {
    text-align: left;
  }

  .update-btn {
    min-width: 140px;
    padding: 14px 20px;
  }

  .guests-info {
    flex-direction: row;
    gap: 16px;
  }

  .guests-count,
  .rooms-count {
    font-size: 13px;
  }
}

@media (max-width: 480px) {
  .booking-action {
    flex-direction: column;
    gap: 12px;
    align-items: stretch;
  }

  .update-btn {
    width: 100%;
    min-width: auto;
  }

  .price-display {
    text-align: center;
    padding: 8px 0;
    background: rgba(0, 113, 194, 0.05);
    border-radius: 6px;
  }

  .bottom-booking-bar.scrolled .price-display {
    background: rgba(255, 255, 255, 0.1);
  }
}

/* Comments Bottom Section */
.comments-bottom-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin-top: 48px;
  padding-top: 32px;
  border-top: 2px solid #e7e7e7;
  background: #f8f9fa;
  border-radius: 8px 8px 0 0;
}

/* Enhanced Mobile Responsive for Comments */
@media (max-width: 768px) {
  .comments-bottom-section {
    margin-top: 32px;
    padding-top: 24px;
  }
}

/* Additional Room Options Section */
.additional-rooms-section {
  margin-top: 32px;
}

.section-header {
  margin-bottom: 16px;
}

.section-title {
  font-size: 22px;
  font-weight: 700;
  color: #262626;
}

.section-subtitle {
  font-size: 14px;
  color: #6b6b6b;
  margin-top: 4px;
}

.room-cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 16px;
}

/* Comments Room Section */
.comments-room-section {
  margin-bottom: 32px;
}

.comments-room-section .section-header {
  margin-bottom: 20px;
}

.comments-room-section .section-title {
  font-size: 20px;
  font-weight: 600;
  color: #262626;
  margin-bottom: 4px;
}

.comments-room-section .section-subtitle {
  font-size: 14px;
  color: #6b6b6b;
}

.comments-room-section .room-cards-grid {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

/* Mobile responsive for comments room section */
@media (max-width: 768px) {
  .comments-room-section .room-cards-grid {
    gap: 12px;
  }
}
</style>
