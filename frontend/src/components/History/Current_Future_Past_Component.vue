<script setup>
import { MapPin, Star, Wifi, Car, Utensils, Cat, Bath } from "lucide-vue-next";
import { ref, computed } from "vue";

const bookings = ref([
  {
    // for current date
    id: 1,
    checkIn: {
      date: "2025-05-20",
      time: "14:00", // 2:00 PM
    },
    checkOut: {
      date: "2025-06-14",
      time: "11:00", // 11:00 AM
    },
    nights: 2,
    originalPrice: 200,
    bookingFee: 20,
    total: 220,
    stayPrice: 200,
    currency: "USD",
    paid: true,
    adults: 2,
    children: 0,
    specialRequests: "Late check-in",
    
    // Hotel information
    hotel: {
      rating: 4.0,
      reviews: 52,
      address: {
        street: "123 Riverside Road",
        district: "Kampot",
        city: "Kampot",
        province: "Kampot Province",
        country: "Cambodia",
        postalCode: "07000",
        fullAddress: "123 Riverside Road, Kampot, Kampot Province, Cambodia",
        url:"https://maps.app.goo.gl/6CoSSLGub1CcD7dd7"
      },
      name: 'KHUN HOTEL',
      rooms: [
        {
          type: "Deluxe Room",
          images: [
            {
              id: 1,
              url:"https://www.jaypeehotels.com/blog/wp-content/uploads/2024/09/Blog-6-scaled.jpg",
            },
            {
              id: 2,
              url:"https://www.jaypeehotels.com/blog/wp-content/uploads/2024/09/Blog-7-scaled.jpg",
            }
          ]
        },
      ]
    },
    bookingId: 'KHN1234',
    bookedDate: 'Fri-Mar-6-2025',
    bookedTime: '13:24',
    paymentMethod: 'credit card',
  },
  {
    // for past date
    id: 2,
    checkIn: {
      date: "2025-03-02",
      time: "14:00", // 2:00 PM
    },
    checkOut: {
      date: "2025-04-02",
      time: "11:00", // 11:00 AM
    },
    nights: 2,
    originalPrice: 200,
    bookingFee: 20,
    total: 220,
    stayPrice: 200,
    currency: "USD",
    paid: true,
    adults: 2,
    children: 0,
    specialRequests: "Late check-in",
    
    // Hotel information
    hotel: {
      rating: 4.0,
      reviews: 52,
      address: {
        street: "123 Riverside Road",
        district: "Kampot",
        city: "Kampot",
        province: "Kampot Province",
        country: "Cambodia",
        postalCode: "07000",
        fullAddress: "123 Riverside Road, Kampot, Kampot Province, Cambodia",
        url:"https://maps.app.goo.gl/6CoSSLGub1CcD7dd7"
      },
      name: 'KHUN HOTEL',
      rooms: [
        {
          type: "Deluxe Room",
          images: [
            {
              id: 1,
              url:"https://www.jaypeehotels.com/blog/wp-content/uploads/2024/09/Blog-6-scaled.jpg",
            },
            {
              id: 2,
              url:"https://www.jaypeehotels.com/blog/wp-content/uploads/2024/09/Blog-7-scaled.jpg",
            }
          ]
        },
      ]
    },
    bookingId: 'KHN1234',
    bookedDate: 'Fri-Mar-6-2025',
    bookedTime: '13:24',
    paymentMethod: 'credit card',
  },
  {
    // for past date
    id: 2,
    checkIn: {
      date: "2025-03-02",
      time: "14:00", // 2:00 PM
    },
    checkOut: {
      date: "2025-04-02",
      time: "11:00", // 11:00 AM
    },
    nights: 2,
    originalPrice: 200,
    bookingFee: 20,
    total: 220,
    stayPrice: 200,
    currency: "USD",
    paid: true,
    adults: 2,
    children: 0,
    specialRequests: "Late check-in",
    
    // Hotel information
    hotel: {
      rating: 4.0,
      reviews: 52,
      address: {
        street: "123 Riverside Road",
        district: "Kampot",
        city: "Kampot",
        province: "Kampot Province",
        country: "Cambodia",
        postalCode: "07000",
        fullAddress: "123 Riverside Road, Kampot, Kampot Province, Cambodia",
        url:"https://maps.app.goo.gl/6CoSSLGub1CcD7dd7"
      },
      name: 'KHUN HOTEL',
      rooms: [
        {
          type: "Deluxe Room",
          images: [
            {
              id: 1,
              url:"https://www.jaypeehotels.com/blog/wp-content/uploads/2024/09/Blog-6-scaled.jpg",
            },
            {
              id: 2,
              url:"https://www.jaypeehotels.com/blog/wp-content/uploads/2024/09/Blog-7-scaled.jpg",
            }
          ]
        },
      ]
    },
    bookingId: 'KHN1234',
    bookedDate: 'Fri-Mar-6-2025',
    bookedTime: '13:24',
    paymentMethod: 'credit card',
  }
]);

// Get active booking based on tab
const activeBookings = computed(() => {
  return bookings.value.filter(booking => {
    const status = getBookingStatus(booking.checkOut.date, booking.checkOut.time);
    return props.activeTab === status;
  });
});

// Format date for display
function formatDate(dateStr) {
  // Normalize date format first
  const normalizeDate = (dateStr) => {
    const [year, month, day] = dateStr.split('-');
    return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
  };
  
  const date = new Date(normalizeDate(dateStr));
  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
}

// Compute booking status
function getBookingStatus(checkOutDate, checkOutTime = '12:00') {
  const now = new Date();
  
  // Normalize the date format (in case of "2025-4-5" format)
  const normalizeDate = (dateStr) => {
    const [year, month, day] = dateStr.split('-');
    return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
  };
  const normalizedDate = normalizeDate(checkOutDate);
  const checkout = new Date(`${normalizedDate}T${checkOutTime}:00`);
  
  return now < checkout ? 'current' : 'history';
}

// Compute total nights
function calculateTotalNights(checkInDate, checkOutDate) {
  // Normalize dates first
  const normalizeDate = (dateStr) => {
    const [year, month, day] = dateStr.split('-');
    return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
  };
  
  const checkIn = new Date(normalizeDate(checkInDate));
  const checkOut = new Date(normalizeDate(checkOutDate));
  const diffTime = checkOut - checkIn;
  return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
}

const amenities = [
  { name: "wifi", icon: Wifi },
  { name: "car", icon: Car },
  { name: "utensils", icon: Utensils },
  { name: "cat", icon: Cat },
  { name: "bath", icon: Bath },
];

function getGoogleMapsLink(address) {
  return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;
}

const props = defineProps({
  activeTab: {
    type: String,
    required: true,
  }
});
</script>

<template>
  <div class="grid grid-cols-1 gap-4 m-4">
    <!-- Current Bookings -->
    <template v-if="activeTab === 'current' && activeBookings.length">
      <div 
        v-for="booking in activeBookings" 
        :key="booking.id"
        class="flex flex-col md:flex-row border-gray-200 border rounded-[12px] w-full"
      >
        <!-- Hotel Details and Message-->
        <div class="p-6 border-b md:border-b-0 md:border-r border-gray-200 rounded-[12px] w-full md:w-1/2">
          <div class="flex justify-between items-start mb-4">
            <h3 class="text-lg font-bold uppercase text-hotel-primary">
              {{ booking.hotel.name }}
            </h3>
            <div class="flex items-center bg-gray-100 px-2 py-1 rounded">
              <Star class="h-4 w-4 text-yellow-500 mr-1" />
              <span class="text-sm font-medium">{{ booking.hotel.rating.toFixed(1) }}</span>
              <span class="text-xs text-gray-500 ml-1">({{ booking.hotel.reviews }} reviews)</span>
            </div>
          </div>

          <div class="flex items-start mb-4">
            <MapPin class="h-4 w-4 text-gray-500 mt-1 mr-2 flex-shrink-0" />
            <div>
              <p class="text-sm text-gray-600">{{ booking.hotel.address.fullAddress }}</p>
              <a
                :href="getGoogleMapsLink(booking.hotel.address.fullAddress)"
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
              :src="booking.hotel.rooms[0].images[0].url"
              :alt="booking.hotel.rooms[0].type"
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
        <div class="p-6 bg-white rounded-[12px] w-full md:w-1/2">
          <h3 class="text-md font-bold text-gray-700 mb-4">Your booking details</h3>
          <div class="">
            <div class="flex items-center justify-between">
              <img
                :src="booking.hotel.rooms[0].images[1].url"
                :alt="booking.hotel.rooms[0].type"
                class="w-20 h-20 object-cover rounded-md"
              />
              <div class="ml-3 text-sm">
                <p class="font-medium">{{ booking.hotel.name }}</p>
                <p>{{ booking.hotel.rooms[0].type }}</p>
                <div class="flex items-center mt-1">
                  <Star class="h-3 w-3 text-yellow-500 mr-1" />
                  <span class="text-xs">{{ booking.hotel.rating.toFixed(1) }} · {{ booking.hotel.reviews }} reviews</span>
                </div>

                <a
                  :href="getGoogleMapsLink(booking.hotel.address.fullAddress)"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="text-sm font-medium text-hotel-primary hover:underline mt-2 inline-flex items-center px-2 py-1 bg-gray-100 rounded-md transition-all hover:bg-gray-200"
                >
                  <MapPin class="h-4 w-4 mr-1" />
                  Get directions
                </a>
              </div>
            </div>

            <p class="text-sm font-medium mt-4">Room Type: {{ booking.hotel.rooms[0].type }}</p>
          </div>
          
          <!-- Checkin and Checkout -->
          <div class="grid grid-cols-2 gap-4 mb-2">
            <div>
              <p class="text-xs text-gray-500">Check-in</p>
              <p class="font-medium">{{ formatDate(booking.checkIn.date) }}</p>
              <p class="text-xs text-gray-500">{{ booking.checkIn.time }}</p>
            </div>

            <div>
              <p class="text-xs text-gray-500">Check-out</p>
              <p class="font-medium">{{ formatDate(booking.checkOut.date) }}</p>
              <p class="text-xs text-gray-500">{{ booking.checkOut.time }}</p>
            </div>
          </div>
          
          <!--  -->
          <div class="mb-2">
            <p class="text-xs text-gray-500">Total length of stay:</p>
            <p class="font-medium">{{ calculateTotalNights(booking.checkIn.date, booking.checkOut.date) }} nights</p>
          </div>
          
          <!--  -->
          <div class="mb-4">
            <p class="text-xs text-gray-500">You selected</p>
            <p class="font-medium">1 room for {{ booking.adults }} adults</p>
          </div>
          
          <!--  -->
          <div class="border-t border-gray-200 pt-4">
            <h4 class="font-medium mb-2">Price details</h4>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span>Original Price, {{ booking.nights }} night</span>
                <span>${{ booking.originalPrice }}</span>
              </div>
              <div class="flex justify-between">
                <span>Stay Price, {{ booking.nights }} night</span>
                <span>${{ booking.stayPrice }}</span>
              </div>
              <div class="flex justify-between">
                <span>Booking Fee</span>
                <span>${{ booking.bookingFee }}</span>
              </div>
            </div>
          </div>
          
          <!--  -->
          <div class="border-t border-gray-200 pt-4 mt-2">
            <div class="flex justify-between items-center mb-2">
              <h3 class="text-lg font-bold">Total</h3>
              <span class="font-bold">${{ booking.total }}</span>
            </div>

            <p class="text-xs text-gray-500 mb-2">
              Includes taxes and fees in property currency ({{ booking.currency }})
            </p>

            <div :class="['text-3xl font-bold', booking.paid ? 'text-green-600' : 'text-orange-500']">
              {{ booking.paid ? "Paid" : "Payment Pending" }}
            </div>
          </div>
        </div>
      </div>
    </template>
    <div v-else-if="activeTab === 'current'" class="text-center py-8 text-gray-500">
      No current bookings found
    </div>
    <!-- History Bookings -->
    <template v-if="activeTab === 'history' && activeBookings.length">
      <div 
        v-for="booking in activeBookings" 
        :key="booking.id"
        class="border border-gray-200 rounded-[12px] p-6"
      >
        <div class="flex flex-col md:flex-row gap-6">
          <!-- Hotel Image -->
          <div class="w-full md:w-1/3">
            <img 
              :src="booking.hotel.rooms[0].images[0].url" 
              :alt="booking.hotel.name" 
              class="w-full h-54 object-center rounded-lg"
            >
          </div>
          
          <!-- Booking Details -->
          <div class="w-full md:w-2/3">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="text-lg font-bold">{{ booking.hotel.name }}</h3>
                <p class="text-gray-600">{{ booking.hotel.rooms[0].type }}</p>
              </div>
              <div class="flex items-center bg-gray-100 px-2 py-1 rounded">
                <Star class="h-4 w-4 text-yellow-500 mr-1" />
                <span class="text-sm font-medium">{{ booking.hotel.rating.toFixed(1) }}</span>
                <span class="text-xs text-gray-500 ml-1">({{ booking.hotel.reviews }} reviews)</span>
              </div>
            </div>
            
            <div class="my-4">
              <div class="flex items-center text-sm text-gray-500">
                <MapPin class="h-4 w-4 mr-1" />
                <span>{{ booking.hotel.address.fullAddress }}</span>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <p class="text-xs text-gray-500">Check-in</p>
                <p class="font-medium">{{ formatDate(booking.checkIn.date) }}</p>
                <p class="text-xs text-gray-500">{{ booking.checkIn.time }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500">Check-out</p>
                <p class="font-medium">{{ formatDate(booking.checkOut.date) }}</p>
                <p class="text-xs text-gray-500">{{ booking.checkOut.time }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500">Booking ID</p>
                <p class="font-medium">{{ booking.bookingId }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500">Booked on</p>
                <p class="font-medium">{{ booking.bookedDate }} at {{ booking.bookedTime }}</p>
              </div>
            </div>
            
            <div class="border-t border-gray-200 pt-4">
              <div class="flex justify-between items-center">
                <div>
                  <p class="text-sm text-gray-500">Total paid</p>
                  <p class="text-xl font-bold">${{ booking.total }}</p>
                </div>
                <div 
                  :class="['px-4 py-2 rounded-lg', booking.paid ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800']"
                >
                  {{ booking.paid ? 'Completed' : 'Cancelled' }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
    <div v-else-if="activeTab === 'history'" class="text-center py-8 text-gray-500">
      No past bookings found
      
    </div>
  </div>
</template>
<style scoped>
/* 
.booking-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.booking-item {
    display: flex;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    background-color: #fff;
}

.hotel-details {
    width: 35%;
    padding: 15px;
    border-right: 1px solid #eee;
    display: flex;
    flex-direction: column;
}

.hotel-image {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 4px;
}

.hotel-info {
    padding: 10px 0;
}

.hotel-info h3 {
    margin: 0;
    color: #333;
    font-size: 16px;
}

.hotel-info p {
    margin: 5px 0;
    color: #666;
}

.rating {
    color: #f8b400;
    font-size: 14px;
}

.reviews {
    color: #999;
    font-size: 12px;
}

.booking-details {
    flex: 1;
    padding: 15px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.booking-header-right {
    margin-bottom: 10px;
}

.hotel-booking-name {
    font-weight: bold;
    font-size: 14px;
}

.booking-id {
    color: #999;
    font-weight: normal;
}

.booking-date {
    margin-top: 5px;
    font-size: 13px;
}

.booking-dates {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 10px;
}

.check-date {
    display: flex;
    flex-direction: column;
}

.label {
    color: #999;
    font-size: 12px;
}

.value {
    font-weight: bold;
    font-size: 14px;
}

.time {
    color: #666;
    font-size: 13px;
}

.payment-info {
    align-self: flex-end;
    font-size: 14px;
}

.payment-info.paid {
    color: #28a745;
}

.payment-info.cancel {
    color: #dc3545;
}

.price {
    font-weight: bold;
    margin-left: 5px;
}

.cancel-btn {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
    padding: 0;
    font-size: 14px;
    font-weight: bold;
} */
</style>
