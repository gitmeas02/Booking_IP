<script setup>
import { MapPin, Star, Wifi, Car, Utensils, Cat, Bath } from "lucide-vue-next";
import { ref, computed } from "vue";
const booking = ref({
  checkIn: {
    date: "2025-04-12",
    time: "14:00", // 2:00 PM
  },
  checkOut: {
    date: "2025-04-14",
    time: "11:00", // 11:00 AM
  },
  price: {
    nights: 2,
    originalPrice: 200,
    stayPrice: 180,
    bookingFee: 20,
    total: 200,
    currency: "USD",
    paid: true,
  },
  room: {
    adults: 2,
    children: 0,
  },
  specialRequests: "Late check-in",
});
const price=ref({
  originalPrice: 200,
  stayPrice: 180,
  bookingFee: 20,
  total: 200,
  currency: "USD",
  paid: false,
});
// const bookingId = ref("123456789");
// const bookingStatus = ref("Confirmed");
// const bookingDate = ref("2025-04-01");
// const bookingTime = ref("10:00 AM");
// const bookingDetails = ref({
//   id: bookingId.value,
//   status: bookingStatus.value,
//   date: bookingDate.value,
//   time: bookingTime.value,
// });
// const bookingMessage = ref(
//   "Your booking has been confirmed. We look forward to welcoming you!"
// );
// const bookingMessageStatus = ref(
//   "Your booking has been confirmed. We look forward to welcoming you!"
// );
// Format date for display (optional)
function formatDate(dateStr) {
  const date = new Date(dateStr);
  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
}

// Compute total nights
const totalNights = computed(() => {
  const checkInDate = new Date(booking.value.checkIn.date);
  const checkOutDate = new Date(booking.value.checkOut.date);
  const diffTime = checkOutDate - checkInDate;
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  return diffDays;
});
const amenities = [
  { name: "wifi", icon: Wifi },
  { name: "car", icon: Car },
  { name: "utensils", icon: Utensils },
  { name: "cat", icon: Cat },
  { name: "bath", icon: Bath },
];

const address = "https://maps.app.goo.gl/6CoSSLGub1CcD7dd7";

function getGoogleMapsLink(address) {
  return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
    address
  )}`;
}
const typRoom = "Deluxe Double Room";
const hotelName = "Khun Meas";
const hotelAddress = "89 Sothearos Blvd, Sangkat Tonle Bassac, Phnom Penh, Cambodia";
const hotelRating = 4.9;
const hotelReviews = 129;

const roomImages = [
  "https://www.jaypeehotels.com/blog/wp-content/uploads/2024/09/Blog-6-scaled.jpg",
  "https://www.jaypeehotels.com/blog/wp-content/uploads/2024/09/Blog-7-scaled.jpg",
  "https://www.jaypeehotels.com/blog/wp-content/uploads/2024/09/Blog-8-scaled.jpg",
];
</script>

<template>
  <div class="overflow-hidden flex flex-col gap-6 justify-center items-center">
    <div class="flex flex-col md:flex-row justify-center border-gray-200 border-1 rounded-[12px] w-fit">
        <!-- Hotel Details and Message-->
      <div class="p-6 border-l border-1 border-gray-200  rounded-[12px]">
        <div class="flex justify-between items-start mb-4">
          <h3 class="text-lg font-bold uppercase text-hotel-primary">
            {{ hotelName }}
          </h3>
          <div class="flex items-center bg-gray-100 px-2 py-1 rounded">
            <Star class="h-4 w-4 text-yellow-500 mr-1" />
            <span class="text-sm font-medium">{{ hotelRating }}</span>
            <span class="text-xs text-gray-500 ml-1"
              >({{ hotelReviews }} reviews)</span
            >
          </div>
        </div>

        <div class="flex items-start mb-4">
          <MapPin class="h-4 w-4 text-gray-500 mt-1 mr-2 flex-shrink-0" />
          <div>
            <p class="text-sm text-gray-600">{{ hotelAddress }}</p>
            <a
              :href="getGoogleMapsLink(address)"
              target="_blank"
              rel="noopener noreferrer"
              class="text-sm font-medium text-hotel-primary hover:underline mt-2 inline-flex items-center px-3 py-1 bg-gray-100 rounded-md transition-all hover:bg-gray-200"
            >
              <MapPin class="h-4 w-4 mr-1" />
              View on Google Maps
            </a>
          </div>
        </div>

        <!-- Facilities -->
        <div class="flex flex-wrap gap-3 mb-6">
          <div
            v-for="(amenity, index) in amenities"
            :key="index"
            class="flex items-center text-xs text-gray-600"
          >
            <component :is="amenity.icon" class="h-4 w-4 text-gray-500 mr-1" />
            <span class="ml-1">{{ amenity.name }}</span>
          </div>
        </div>

        <div class="h-40 relative rounded-md overflow-hidden mb-5">
          <img
            :src="roomImages[0]"
            :alt="typRoom"
            class="w-full h-full object-cover"
          />
        </div>

        <button
          class="w-full border border-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-100 transition-all"
        >
          MESSAGE
        </button>
      </div>
   <!-- Booking Details -->
        <div class="p-6  bg-white border-gray-200  rounded-[12px]">
        <h3 class="text-md font-bold text-gray-700 mb-4">Your booking details</h3>
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
            <img
                :src="roomImages[1]"
                :alt="typRoom"
                class="w-20 h-20 object-cover rounded-md"
            />
            <div class="ml-3 text-sm">
                <p class="font-medium">{{ hotelName }}</p>
                <p>{{ typRoom }}</p>
                <div class="flex items-center mt-1">
                <Star class="h-3 w-3 text-yellow-500 mr-1" />
                <span class="text-xs"
                    >{{ hotelRating }} · {{ hotelReviews }} reviews</span
                >
                </div>

                <a
                :href="getGoogleMapsLink(address)"
                target="_blank"
                rel="noopener noreferrer"
                class="text-sm font-medium text-hotel-primary hover:underline mt-2 inline-flex items-center px-2 py-1 bg-gray-100 rounded-md transition-all hover:bg-gray-200"
                >
                <MapPin class="h-4 w-4 mr-1" />
                Get directions
                </a>
            </div>
            </div>

            <p class="text-sm font-medium mt-4">Room Type: {{ typRoom }}</p>
        </div>
        <!-- Checkin and Checkout -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
            <p class="text-xs text-gray-500">Check-in</p>
            <p class="font-medium">
                {{ formatDate(booking.checkIn.date) }}
            </p>
            <p class="text-xs text-gray-500">{{ booking.checkIn.time }}</p>
            </div>

            <div>
            <p class="text-xs text-gray-500">Check-out</p>
            <p class="font-medium">
                {{ formatDate(booking.checkOut.date) }}
            </p>
            <p class="text-xs text-gray-500">{{ booking.checkOut.time }}</p>
            </div>
        </div>
        <!--  -->
        <div class="mb-4">
            <p class="text-xs text-gray-500">Total length of stay:</p>
            <p class="font-medium">{{ booking.price.nights }} night</p>
        </div>
        <!--  -->
        <div class="mb-6">
            <p class="text-xs text-gray-500">You selected</p>
            <p class="font-medium">1 room for {{ booking.room.adults }} adults</p>
        </div>
        <!--  -->
        <div class="border-t border-gray-200 pt-4">
            <h4 class="font-medium mb-2">Price details</h4>
            <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span>Original Price, {{ booking.price.nights }} night</span>
                <span>${{ booking.price.originalPrice }}</span>
            </div>
            <div class="flex justify-between">
                <span>Stay Price, {{ booking.price.nights }} night</span>
                <span>${{ booking.price.stayPrice }}</span>
            </div>
            <div class="flex justify-between">
                <span>Booking Fee</span>
                <span>${{ booking.price.bookingFee }}</span>
            </div>
            </div>
        </div>
        <!--  -->
        <div class="border-t border-gray-200 pt-4 mt-4">
            <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Total</h3>
            <span class="font-bold">${{ booking.price.total }}</span>
            </div>

            <p class="text-xs text-gray-500 mb-4">
            Includes taxes and fees in property currency ({{
                booking.price.currency
            }})
            </p>

            <div
            :class="[
                'text-3xl font-bold',
                booking.price.paid ? 'text-green-600' : 'text-orange-500',
            ]"
            >
            {{ booking.price.paid ? "Paid" : "Payment Pending" }}
            </div>
        </div>
        </div>
    </div>

  
    <div class="flex flex-col md:flex-row justify-center border-gray-200 border-1 rounded-[12px] w-fit">
        <!-- Hotel Details and Message-->
      <div class="p-6 border-l border-1 border-gray-200  rounded-[12px]">
        <div class="flex justify-between items-start mb-4">
          <h3 class="text-lg font-bold uppercase text-hotel-primary">
            {{ hotelName }}
          </h3>
          <div class="flex items-center bg-gray-100 px-2 py-1 rounded">
            <Star class="h-4 w-4 text-yellow-500 mr-1" />
            <span class="text-sm font-medium">{{ hotelRating }}</span>
            <span class="text-xs text-gray-500 ml-1"
              >({{ hotelReviews }} reviews)</span
            >
          </div>
        </div>

        <div class="flex items-start mb-4">
          <MapPin class="h-4 w-4 text-gray-500 mt-1 mr-2 flex-shrink-0" />
          <div>
            <p class="text-sm text-gray-600">{{ hotelAddress }}</p>
            <a
              :href="getGoogleMapsLink(address)"
              target="_blank"
              rel="noopener noreferrer"
              class="text-sm font-medium text-hotel-primary hover:underline mt-2 inline-flex items-center px-3 py-1 bg-gray-100 rounded-md transition-all hover:bg-gray-200"
            >
              <MapPin class="h-4 w-4 mr-1" />
              View on Google Maps
            </a>
          </div>
        </div>

        <!-- Facilities -->
        <div class="flex flex-wrap gap-3 mb-6">
          <div
            v-for="(amenity, index) in amenities"
            :key="index"
            class="flex items-center text-xs text-gray-600"
          >
            <component :is="amenity.icon" class="h-4 w-4 text-gray-500 mr-1" />
            <span class="ml-1">{{ amenity.name }}</span>
          </div>
        </div>

        <div class="h-40 relative rounded-md overflow-hidden mb-5">
          <img
            :src="roomImages[0]"
            :alt="typRoom"
            class="w-full h-full object-cover"
          />
        </div>

        <button
          class="w-full border border-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-100 transition-all"
        >
          MESSAGE
        </button>
      </div>
   <!-- Booking Details -->
        <div class="p-6  bg-white border-gray-200  rounded-[12px]">
        <h3 class="text-md font-bold text-gray-700 mb-4">Your booking details</h3>
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
            <img
                :src="roomImages[1]"
                :alt="typRoom"
                class="w-20 h-20 object-cover rounded-md"
            />
            <div class="ml-3 text-sm">
                <p class="font-medium">{{ hotelName }}</p>
                <p>{{ typRoom }}</p>
                <div class="flex items-center mt-1">
                <Star class="h-3 w-3 text-yellow-500 mr-1" />
                <span class="text-xs"
                    >{{ hotelRating }} · {{ hotelReviews }} reviews</span
                >
                </div>

                <a
                :href="getGoogleMapsLink(address)"
                target="_blank"
                rel="noopener noreferrer"
                class="text-sm font-medium text-hotel-primary hover:underline mt-2 inline-flex items-center px-2 py-1 bg-gray-100 rounded-md transition-all hover:bg-gray-200"
                >
                <MapPin class="h-4 w-4 mr-1" />
                Get directions
                </a>
            </div>
            </div>

            <p class="text-sm font-medium mt-4">Room Type: {{ typRoom }}</p>
        </div>
        <!-- Checkin and Checkout -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
            <p class="text-xs text-gray-500">Check-in</p>
            <p class="font-medium">
                {{ formatDate(booking.checkIn.date) }}
            </p>
            <p class="text-xs text-gray-500">{{ booking.checkIn.time }}</p>
            </div>

            <div>
            <p class="text-xs text-gray-500">Check-out</p>
            <p class="font-medium">
                {{ formatDate(booking.checkOut.date) }}
            </p>
            <p class="text-xs text-gray-500">{{ booking.checkOut.time }}</p>
            </div>
        </div>
        <!--  -->
        <div class="mb-4">
            <p class="text-xs text-gray-500">Total length of stay:</p>
            <p class="font-medium">{{ booking.price.nights }} night</p>
        </div>
        <!--  -->
        <div class="mb-6">
            <p class="text-xs text-gray-500">You selected</p>
            <p class="font-medium">1 room for {{ booking.room.adults }} adults</p>
        </div>
        <!--  -->
        <div class="border-t border-gray-200 pt-4">
            <h4 class="font-medium mb-2">Price details</h4>
            <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span>Original Price, {{ booking.price.nights }} night</span>
                <span>${{ booking.price.originalPrice }}</span>
            </div>
            <div class="flex justify-between">
                <span>Stay Price, {{ booking.price.nights }} night</span>
                <span>${{ booking.price.stayPrice }}</span>
            </div>
            <div class="flex justify-between">
                <span>Booking Fee</span>
                <span>${{ booking.price.bookingFee }}</span>
            </div>
            </div>
        </div>
        <!--  -->
        <div class="border-t border-gray-200 pt-4 mt-4">
            <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Total</h3>
            <span class="font-bold">${{ booking.price.total }}</span>
            </div>

            <p class="text-xs text-gray-500 mb-4">
            Includes taxes and fees in property currency ({{
                booking.price.currency
            }})
            </p>

            <div
            :class="[
                
                'text-2xl font-bold',
                price.paid ? 'text-green-600' : 'text-orange-500',
            ]"
            >
            {{ price.paid ? "Paid" : "Payment Pending" }}
            </div>
        </div>
        </div>
    </div>
  </div>
</template>
