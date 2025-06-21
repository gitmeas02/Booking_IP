<template>
  <!-- Hotel Information -->
  <div
    class="grid responsive-grid mt-8 mb-4 mx-4 md:mx-16 items-start grid-cols-1 md:grid-cols-2 gap-8 border-gray-200 border-1 rounded-[12px] p-8"
  >
    <!-- Hotel Details -->
    <div>
      <h2 class="text-2xl font-bold mb-4">Hotel Details</h2>
      
      <!-- Hotel Image Carousel -->
      <div class="relative mb-4 rounded-lg overflow-hidden cursor-pointer" @click="openModal('hotel')">
        <div class="h-48 w-full relative">
          <img
            v-if="bookingDetails.hotelPhotos && bookingDetails.hotelPhotos.length > 0"
            :src="'http://localhost:9000/ownerimages/' + bookingDetails.hotelPhotos[currentHotelImageIndex].url"
            alt="Hotel image"
            class="h-full w-full object-cover transition-opacity duration-500"
          />
          <div class="absolute inset-0 bg-opacity-20 hover:bg-opacity-10 transition-all duration-300"></div>
          
          <!-- Image Counter -->
          <div class="absolute top-3 right-3 bg-black bg-opacity-60 text-white px-2 py-1 rounded text-sm">
            {{ currentHotelImageIndex + 1 }} / {{ bookingDetails.hotelPhotos?.length || 0 }}
          </div>
          
          <!-- Click to view indicator -->
          <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
            <div class="bg-white bg-opacity-90 px-4 py-2 rounded-full text-gray-800 font-medium">
              Click to view all photos
            </div>
          </div>
        </div>
      </div>
      
      <div class="flex items-start gap-3">
        <MapPin class="h-5 w-5 text-primary mt-0.5" />
        <div>
          <h3 class="font-medium">Location</h3>
          <p class="text-muted-foreground">{{ bookingDetails.location }}</p>
        </div>
      </div>
      <div class="flex items-start gap-3">
        <Shield class="h-5 w-5 text-primary mt-0.5" />
        <div>
          <h3 class="font-medium">Safety Measures</h3>
          <p class="text-muted-foreground">{{ bookingDetails.roomDescription }}</p>
        </div>
      </div>
    </div>

    <!-- Room Details -->
    <div>
      <h2 class="text-2xl font-bold mb-4">Room Details</h2>
      
      <!-- Room Image Carousel -->
      <div class="relative mb-4 rounded-lg overflow-hidden cursor-pointer" @click="openModal('room')">
        <div class="h-48 w-full relative">
          <img
            v-if="bookingDetails.roomImages && bookingDetails.roomImages.length > 0"
            :src="'http://localhost:9000/' + bookingDetails.roomImages[currentRoomImageIndex].image_url"
            alt="Room image"
            class="h-full w-full object-cover transition-opacity duration-500"
          />
          <div class="absolute inset-0  bg-opacity-20 hover:bg-opacity-10 transition-all duration-300"></div>
          
          <!-- Image Counter -->
          <div class="absolute top-3 right-3 bg-black bg-opacity-60 text-white px-2 py-1 rounded text-sm">
            {{ currentRoomImageIndex + 1 }} / {{ bookingDetails.roomImages?.length || 0 }}
          </div>
          
          <!-- Click to view indicator -->
          <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
            <div class="bg-white bg-opacity-90 px-4 py-2 rounded-full text-gray-800 font-medium">
              Click to view all photos
            </div>
          </div>
        </div>
      </div>
      
      <div class="flex items-start gap-3 mt-2">
        <Bed class="h-5 w-5 text-primary mt-0.5" />
        <div>
          <h3 class="font-medium">{{ bookingDetails.roomType }} Room</h3>
          <p class="text-muted-foreground">{{ bookingDetails.guest }} Guests</p>
        </div>
      </div>
      <div class="mt-4">
        <ul class="ml-8 list-disc space-y-1">
          <li
            class="text-muted-foreground"
            v-for="(amenity, index) in bookingDetails.amenities"
            :key="index"
          >
            {{ amenity }}
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Summary and Form -->
  <div
    class="grid responsive-grid mt-8 mb-4 mx-4 md:mx-16 items-start grid-cols-1 md:grid-cols-3 gap-8"
  >
    <div class="min-w-[300px] w-full">
      <BookingSummary
        :hotelName="bookingDetails.hotelName"
        :location="bookingDetails.location"
        :near="bookingDetails.near"
        :checkin="bookingDetails.checkin"
        :checkout="bookingDetails.checkout"
        :roomType="bookingDetails.roomType"
        :guest="bookingDetails.guest"
        :roomPrice="roomPrice"
        :nights="bookingDetails.nights"
        :roomRate="bookingDetails.roomRate"
        :taxes="bookingDetails.taxes"
        :quantity="quantity"
        :cancellation="bookingDetails.cancellation"
      />
    </div>
    <div class="md:col-span-2">
      <FormInfo ref="formInfoRef" />
      <div class="mr-16 ml-16 mt-8">
        <button
          type="button"
          @click="submitForm"
          class="w-full btn bg-blue-950 p-2 text-white"
        >
          Complete Booking
        </button>
      </div>
    </div>
  </div>

  <!-- Image Modal Viewer -->
  <div 
    v-if="showModal" 
    class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4"
    @click="closeModal"
  >
    <div class="relative max-w-6xl max-h-full w-full h-full flex items-center justify-center">
      <!-- Close Button -->
      <button 
        @click="closeModal"
        class="absolute top-4 right-4 z-10 bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-2 rounded-full transition-all duration-200"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>

      <!-- Previous Button -->
      <button 
        v-if="getCurrentImages().length > 1"
        @click.stop="previousImage"
        class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-3 rounded-full transition-all duration-200"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </button>

      <!-- Next Button -->
      <button 
        v-if="getCurrentImages().length > 1"
        @click.stop="nextImage"
        class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 bg-white bg-opacity-20 hover:bg-opacity-30 text-white p-3 rounded-full transition-all duration-200"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </button>

      <!-- Main Image -->
      <div class="relative w-full h-full flex items-center justify-center" @click.stop>
        <img
          :src="getModalImageSrc()"
          :alt="modalType === 'hotel' ? 'Hotel image' : 'Room image'"
          class="max-w-full max-h-full object-contain rounded-lg"
        />
        
        <!-- Image Info -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-60 text-white px-4 py-2 rounded-full">
          <span class="text-sm font-medium">
            {{ modalImageIndex + 1 }} of {{ getCurrentImages().length }} 
            {{ modalType === 'hotel' ? 'Hotel' : 'Room' }} Photos
          </span>
        </div>
      </div>

      <!-- Thumbnail Strip -->
      <div 
        v-if="getCurrentImages().length > 1"
        class="absolute bottom-20 left-1/2 transform -translate-x-1/2 flex gap-2 bg-black bg-opacity-40 p-2 rounded-lg max-w-full overflow-x-auto"
      >
        <button
          v-for="(image, index) in getCurrentImages()"
          :key="index"
          @click.stop="setModalImage(index)"
          class="flex-shrink-0 w-16 h-12 rounded overflow-hidden border-2 transition-all duration-200"
          :class="index === modalImageIndex ? 'border-white' : 'border-transparent opacity-60 hover:opacity-80'"
        >
          <img
            :src="getThumbnailSrc(image)"
            :alt="`${modalType} thumbnail ${index + 1}`"
            class="w-full h-full object-cover"
          />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted, onUnmounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import FormInfo from "@/components/CheckoutComponents/FormInfo.vue";
import BookingSummary from "@/components/CheckoutComponents/BookingSummary.vue";
import { MapPin, Shield, Bed } from "lucide-vue-next";
import { useRoomStore } from "@/stores/store";
import dayjs from "dayjs";

const route = useRoute();
const router = useRouter();
const roomStore = useRoomStore();
const formInfoRef = ref(null);
const isLoading = ref(false);
const errorMessage = ref("");

// Carousel state
const currentHotelImageIndex = ref(0);
const currentRoomImageIndex = ref(0);
const hotelCarouselInterval = ref(null);
const roomCarouselInterval = ref(null);

// Modal state
const showModal = ref(false);
const modalType = ref('hotel'); // 'hotel' or 'room'
const modalImageIndex = ref(0);

const roomId = route.params.id;
const roomIds = route.query.roomIds?.split(",").map((id) => Number(id)) || [];
const hotelId = Number(route.query.hotelId);
const checkin = route.query.checkin;
const checkout = route.query.checkout;
const quantity = Number(route.query.quantity) || 1;
const roomName = route.query.roomName;
const roomPrice = Number(route.query.roomPrice);
const capacity = Number(route.query.capacity) || 2;

const nights = checkin && checkout ? dayjs(checkout).diff(dayjs(checkin), "day") : 1;
const roomRate = roomPrice * quantity * nights;
const taxes = Math.round(roomRate * 0.1);
const totalPrice = roomRate + taxes;

const bookingDetails = ref({
  hotelName: "Loading...",
  location: "Loading...",
  near: "Loading...",
  checkin: checkin ? dayjs(checkin).toDate() : null,
  checkout: checkout ? dayjs(checkout).toDate() : null,
  roomType: roomName || " ",
  guest: capacity * quantity,
  nights,
  roomRate,
  taxes,
  total: totalPrice,
  cancellation: dayjs().add(7, "day").toDate(),
  hotelPhotos: [],
  roomImages: [],
  amenities: [],
  roomDescription: ""
});

// Carousel functions
const startHotelCarousel = () => {
  if (hotelCarouselInterval.value) {
    clearInterval(hotelCarouselInterval.value);
  }
  
  hotelCarouselInterval.value = setInterval(() => {
    if (bookingDetails.value.hotelPhotos && bookingDetails.value.hotelPhotos.length > 1) {
      currentHotelImageIndex.value = (currentHotelImageIndex.value + 1) % bookingDetails.value.hotelPhotos.length;
    }
  }, 3000);
};

const startRoomCarousel = () => {
  if (roomCarouselInterval.value) {
    clearInterval(roomCarouselInterval.value);
  }
  
  roomCarouselInterval.value = setInterval(() => {
    if (bookingDetails.value.roomImages && bookingDetails.value.roomImages.length > 1) {
      currentRoomImageIndex.value = (currentRoomImageIndex.value + 1) % bookingDetails.value.roomImages.length;
    }
  }, 3000);
};

const stopCarousels = () => {
  if (hotelCarouselInterval.value) {
    clearInterval(hotelCarouselInterval.value);
    hotelCarouselInterval.value = null;
  }
  if (roomCarouselInterval.value) {
    clearInterval(roomCarouselInterval.value);
    roomCarouselInterval.value = null;
  }
};

// Modal functions
const openModal = (type) => {
  modalType.value = type;
  modalImageIndex.value = type === 'hotel' ? currentHotelImageIndex.value : currentRoomImageIndex.value;
  showModal.value = true;
  stopCarousels(); // Stop auto-scroll when modal is open
  document.body.style.overflow = 'hidden'; // Prevent body scroll
};

const closeModal = () => {
  showModal.value = false;
  document.body.style.overflow = 'auto'; // Restore body scroll
  // Restart carousels after modal closes
  setTimeout(() => {
    startHotelCarousel();
    startRoomCarousel();
  }, 100);
};

const getCurrentImages = () => {
  return modalType.value === 'hotel' ? bookingDetails.value.hotelPhotos : bookingDetails.value.roomImages;
};

const getModalImageSrc = () => {
  const images = getCurrentImages();
  if (!images || images.length === 0) return '';
  
  const image = images[modalImageIndex.value];
  if (modalType.value === 'hotel') {
    return `http://localhost:9000/ownerimages/${image.url}`;
  } else {
    return `http://localhost:9000/${image.image_url}`;
  }
};

const getThumbnailSrc = (image) => {
  if (modalType.value === 'hotel') {
    return `http://localhost:9000/ownerimages/${image.url}`;
  } else {
    return `http://localhost:9000/${image.image_url}`;
  }
};

const nextImage = () => {
  const images = getCurrentImages();
  modalImageIndex.value = (modalImageIndex.value + 1) % images.length;
};

const previousImage = () => {
  const images = getCurrentImages();
  modalImageIndex.value = modalImageIndex.value === 0 ? images.length - 1 : modalImageIndex.value - 1;
};

const setModalImage = (index) => {
  modalImageIndex.value = index;
};

// Keyboard navigation for modal
const handleKeydown = (event) => {
  if (!showModal.value) return;
  
  if (event.key === 'Escape') {
    closeModal();
  } else if (event.key === 'ArrowRight') {
    nextImage();
  } else if (event.key === 'ArrowLeft') {
    previousImage();
  }
};

onMounted(async () => {
  try {
    if (hotelId) {
      const hotelData = await roomStore.fetchHotelById(hotelId);
      console.log("Fetched hotel details:", hotelData);

      const selectedRooms = hotelData.room_types.filter((r) => roomIds.includes(r.id));
      console.log("Selected rooms:", selectedRooms);

      const roomImages = selectedRooms.flatMap((room) =>
        room.images?.map((image) => ({
          image_url: image.image_url,
        })) || []
      );
      console.log("Room images:", roomImages);

      const firstRoom = selectedRooms[0] || null;

      const amenities = firstRoom?.amenities
        ? firstRoom.amenities.map((a) => a.amenity_name)
        : [];
      console.log("Amenities:", amenities);

      const hotelPhotos =
        hotelData.photos && hotelData.photos.length > 0
          ? hotelData.photos
          : (firstRoom?.images || []).map((img) => ({
              url: img.thumbnail_url,
            }));
      console.log("Hotel photos:", hotelPhotos);

      bookingDetails.value = {
        ...bookingDetails.value,
        hotelName: hotelData.property_name,
        location: `${hotelData.location?.city}, ${hotelData.location?.country}`,
        roomDescription: firstRoom?.description || "No description available",
        amenities,
        hotelPhotos,
        roomImages,
        near: hotelData.location?.street || "City center",
      };

      // Start carousels after data is loaded
      if (hotelPhotos.length > 1) {
        startHotelCarousel();
      }
      if (roomImages.length > 1) {
        startRoomCarousel();
      }
    }
  } catch (error) {
    console.error("Error loading hotel details:", error);
  }

  // Add keyboard event listener
  document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  stopCarousels();
  document.removeEventListener('keydown', handleKeydown);
  document.body.style.overflow = 'auto'; // Ensure body scroll is restored
});

const createBooking = async (formData) => {
  try {
    isLoading.value = true;
    errorMessage.value = "";

    const bookingData = {
      user_id: 1,
      room_ids: roomIds,
      hotel_id: hotelId,
      check_in_date: checkin,
      check_out_date: checkout,
      number_of_guests: Number(formData.guestCount || capacity),
      total_price: totalPrice,
      special_request: formData.specialRequests || "",
      payment_method: formData.selectedPayment,
    };

    console.log("Sending booking data:", bookingData);

    const response = await axios.post(
      "http://localhost:8100/api/bookings",
      bookingData
    );

    if (response.data && response.data.booking_id) {
      alert("Booking successful! Your booking ID is: " + response.data.booking_id);
      router.push("/current-past-booked");
    } else {
      throw new Error("Invalid booking response");
    }
  } catch (error) {
    console.error("Booking creation error:", error);
    errorMessage.value = "Failed to create booking: " + error.message;
    alert("Failed to create booking. Please try again.");
  } finally {
    isLoading.value = false;
  }
};

const submitForm = () => {
  if (formInfoRef.value) {
    const formData = formInfoRef.value.getFormData();

    if (!formData.agreeToTerms) {
      alert("Please agree to the terms and conditions to continue.");
      return;
    }

    createBooking(formData);
  } else {
    alert("Form information is not available. Please try again.");
  }
};
</script>

<style scoped>
.responsive-grid {
  display: grid;
}

@media (max-width: 768px) {
  .responsive-grid {
    grid-template-columns: 1fr;
  }
}

/* Smooth scrolling for thumbnail strip */
.overflow-x-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
}

.overflow-x-auto::-webkit-scrollbar {
  height: 4px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background-color: rgba(255, 255, 255, 0.3);
  border-radius: 2px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background-color: rgba(255, 255, 255, 0.5);
}
</style>