<template>
  <div class="p-4 space-y-4 bg-gray-50 min-h-screen">
    <!-- Controls -->
    <div class="flex justify-between items-center bg-white p-4 shadow rounded-lg">
      <div class="flex gap-2 items-center">
        <button 
          @click="goToToday" 
          class="px-3 py-1 bg-amber-200 hover:bg-amber-300 rounded transition-colors"
        >
          Today
        </button>
        <button 
          @click="previousMonth" 
          class="text-xl hover:bg-gray-100 p-2 rounded transition-colors"
        >
          ◀
        </button>
        <span class="font-bold text-lg min-w-[200px] text-center">
          {{ months[selectedMonth] }} {{ selectedYear }}
        </span>
        <button 
          @click="nextMonth" 
          class="text-xl hover:bg-gray-100 p-2 rounded transition-colors"
        >
          ▶
        </button>
      </div>
      <button 
        class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded transition-colors" 
        @click="router.push('/upload-property')"
      >
        Upload Room
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-8">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-amber-500 mx-auto"></div>
      <p class="mt-2 text-gray-600">Loading rooms...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
      {{ error }}
      <button @click="fetchRooms" class="ml-2 underline">Retry</button>
    </div>

    <!-- Calendar Container -->
    <div v-else class="bg-white shadow rounded-lg overflow-hidden">
      <!-- Horizontal Scroll Container -->
      <div class="overflow-x-auto">
        <div class="min-w-max">
          <!-- Calendar Header -->
          <div 
            class="grid text-center text-sm font-semibold bg-gray-100 border-b sticky top-0 z-10"
            :style="{ gridTemplateColumns: `250px repeat(${monthDays.length}, minmax(80px, 1fr))` }"
          >
            <div class="p-3 border-r bg-gray-100 sticky left-0 z-20">Room</div>
            <div 
              v-for="(day, i) in monthDays" 
              :key="'head-' + i" 
              class="p-3 border-r whitespace-nowrap"
            >
              <div class="font-medium">{{ formatDisplayDate(day) }}</div>
              <div class="text-xs text-gray-500">
                {{ formatWeekday(day) }}
              </div>
            </div>
          </div>

          <!-- Room Rows -->
          <div 
            v-for="room in rooms" 
            :key="room.id" 
            class="grid text-center border-b hover:bg-gray-50 transition-colors"
            :style="{ gridTemplateColumns: `250px repeat(${monthDays.length}, minmax(80px, 1fr))` }"
          >
            <!-- Room Info - Sticky -->
            <div class="flex items-center gap-3 p-3 border-r bg-white sticky left-0 z-10 shadow-sm">
              <img 
                :src="room.image?.[0] || 'https://via.placeholder.com/50'" 
                :alt="`${room.name} image`"
                class="w-12 h-12 object-cover rounded-lg"
                @error="handleImageError"
              />
              <div class="text-left">
                <div class="font-bold text-sm">{{ room.hotel }}</div>
                <div class="text-xs text-gray-500">{{ room.name }}</div>
                <div class="text-xs text-blue-600">ID: {{ room.id }}</div>
              </div>
            </div>

            <!-- Day Cells -->
            <div
              v-for="day in monthDays"
              :key="`day-${room.id}-${day}`"
              class="h-24 border-r text-xs flex flex-col items-center justify-center cursor-pointer relative transition-all duration-200"
              :class="getDayStatusClass(room, day)"
              @click="onDayClick(room, day)"
              :title="getDayTooltip(room, day)"
            >
              <div class="font-semibold">${{ getDayPrice(room, day) }}</div>
              <div class="text-xs opacity-75">{{ getDayStatus(room, day) }}</div>
              <div v-if="isDateBooked(room, parseDate(day))" class="text-xs text-gray-600 truncate w-full px-1">
                {{ getGuestName(room, day) }}
              </div>
              <div v-else-if="isDateBlocked(room, parseDate(day))" class="text-xs text-gray-600 truncate w-full px-1">
                {{ getBlockDateRange(room, day) }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Legend -->
    <div class="flex gap-4 text-sm bg-white p-4 rounded-lg shadow flex-wrap">
      <div class="flex items-center gap-2">
        <div class="w-4 h-4 bg-green-50 border border-green-200 rounded"></div>
        <span>Available</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="w-4 h-4 bg-red-100 border border-red-200 rounded"></div>
        <span>Booked</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="w-4 h-4 bg-gray-200 border border-gray-300 rounded"></div>
        <span>Blocked</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="w-4 h-4 bg-blue-50 border border-blue-200 rounded"></div>
        <span>Hover to edit</span>
      </div>
    </div>

    <!-- Room Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="font-semibold text-gray-700">Total Rooms</h3>
        <p class="text-2xl font-bold text-blue-600">{{ rooms.length }}</p>
      </div>
      <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="font-semibold text-gray-700">Available Days</h3>
        <p class="text-2xl font-bold text-green-600">{{ availableDaysCount }}</p>
      </div>
      <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="font-semibold text-gray-700">Booked Days</h3>
        <p class="text-2xl font-bold text-red-600">{{ bookedDaysCount }}</p>
      </div>
    </div>

    <!-- Popup Component -->
    <ControllDateRoom
      v-if="showPopup"
      :roomImage="selectedRoom.image?.[0]"
      :startDate="selectedStartDate"
      :endDate="selectedEndDate"
      :hotelName="selectedRoom.hotel"
      :roomDetail="selectedRoom.name"
      :displayDate="selectedStartDate"
      :initialPrice="selectedPrice"
      :initialStatus="selectedStatus"
      @cancel="closePopup"
      @update="handleUpdate"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import ControllDateRoom from '@/components/AdminComponents/ControllDateRoom.vue';

const router = useRouter();

// Constants
const months = [
  'January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December'
];

// Reactive state
const selectedMonth = ref(new Date().getMonth());
const selectedYear = ref(new Date().getFullYear());
const rooms = ref([]);
const loading = ref(false);
const error = ref('');

// Popup state
const showPopup = ref(false);
const selectedRoom = ref({});
const selectedStartDate = ref('');
const selectedEndDate = ref('');
const selectedPrice = ref(0);
const selectedStatus = ref('');

// Computed properties
const monthDays = computed(() => {
  const days = [];
  const date = new Date(selectedYear.value, selectedMonth.value, 1);
  
  while (date.getMonth() === selectedMonth.value) {
    days.push(formatDate(new Date(date)));
    date.setDate(date.getDate() + 1);
  }
  
  return days;
});

const availableDaysCount = computed(() => {
  return rooms.value.reduce((total, room) => {
    return total + monthDays.value.filter(day => {
      const date = parseDate(day);
      return !isDateBooked(room, date) && !isDateBlocked(room, date);
    }).length;
  }, 0);
});

const bookedDaysCount = computed(() => {
  return rooms.value.reduce((total, room) => {
    return total + monthDays.value.filter(day => {
      const date = parseDate(day);
      return isDateBooked(room, date);
    }).length;
  }, 0);
});

// Utility functions
function formatDate(date) {
  return date.toISOString().split('T')[0];
}

function parseDate(str) {
  const [y, m, d] = str.split('-').map(Number);
  return new Date(y, m - 1, d);
}

function formatDisplayDate(dateStr) {
  const date = parseDate(dateStr);
  return date.getDate();
}

function formatWeekday(dateStr) {
  const date = parseDate(dateStr);
  return date.toLocaleDateString('en-US', { weekday: 'short' });
}

function handleImageError(event) {
  event.target.src = 'https://via.placeholder.com/50?text=No+Image';
}

// Navigation functions
function previousMonth() {
  if (selectedMonth.value === 0) {
    selectedMonth.value = 11;
    selectedYear.value--;
  } else {
    selectedMonth.value--;
  }
}

function nextMonth() {
  if (selectedMonth.value === 11) {
    selectedMonth.value = 0;
    selectedYear.value++;
  } else {
    selectedMonth.value++;
  }
}

function goToToday() {
  const today = new Date();
  selectedMonth.value = today.getMonth();
  selectedYear.value = today.getFullYear();
}

// Room data management
async function fetchRooms() {
  loading.value = true;
  error.value = '';
  
  try {
    const response = await axios.get("http://localhost:5000/hotels", {
      timeout: 10000 // 10 second timeout
    });
    
    if (!response.data || !Array.isArray(response.data)) {
      throw new Error('Invalid data format received from server');
    }
    
    rooms.value = response.data.flatMap(hotel => 
      hotel.rooms?.map(room => ({
        ...room,
        hotel: hotel.name,
        hotelId: hotel.id,
        // Ensure required properties exist
        bookings: room.bookings || [],
        blockedDates: room.blockedDates || [],
        price: room.price || [],
        basePrice: room.basePrice || 0
      })) || []
    );
    
  } catch (err) {
    console.error('Error fetching rooms:', err);
    error.value = err.response?.data?.message || err.message || 'Failed to load rooms';
  } finally {
    loading.value = false;
  }
}

// Status checking functions
function isDateBooked(room, date) {
  if (!room.bookings) return false;
  
  return room.bookings.some(booking => {
    const bookingStart = parseDate(booking.startDate);
    const bookingEnd = parseDate(booking.endDate);
    return date >= bookingStart && date < bookingEnd;
  });
}

function isDateBlocked(room, date) {
  if (!room.blockedDates) return false;
  
  return room.blockedDates.some(blocked => {
    const blockedStart = parseDate(blocked.startDate);
    const blockedEnd = parseDate(blocked.endDate);
    return date >= blockedStart && date < blockedEnd;
  });
}

function getDayPrice(room, dateStr) {
  const date = parseDate(dateStr);
  
  // Check for specific pricing first
  if (room.price && room.price.length > 0) {
    for (const priceRange of room.price) {
      const startDate = parseDate(priceRange.startDate);
      const endDate = parseDate(priceRange.endDate);
      
      if (date >= startDate && date <= endDate) {
        return priceRange.amount;
      }
    }
  }
  
  // Fall back to base price
  return room.basePrice || 0;
}

function getDayStatus(room, dateStr) {
  const date = parseDate(dateStr);
  
  if (isDateBooked(room, date)) return 'Booked';
  if (isDateBlocked(room, date)) return 'Blocked';
  return 'Available';
}

function getDayStatusClass(room, dayStr) {
  const date = parseDate(dayStr);
  
  if (isDateBooked(room, date)) {
    return 'bg-red-100 cursor-not-allowed text-red-700 border-red-200';
  }
  
  if (isDateBlocked(room, date)) {
    return 'bg-gray-200 text-gray-600 cursor-pointer hover:bg-gray-300';
  }
  
  return 'bg-green-50 text-green-700 cursor-pointer hover:bg-blue-50 hover:border-blue-300';
}

function getDayTooltip(room, dateStr) {
  const date = parseDate(dateStr);
  const status = getDayStatus(room, dateStr);
  const price = getDayPrice(room, dateStr);
  let tooltip = `${room.hotel} - ${room.name}\nDate: ${dateStr}\nStatus: ${status}\nPrice: $${price}`;
  
  if (isDateBooked(room, date)) {
    tooltip += `\nGuest: ${getGuestName(room, dateStr)}`;
  } else if (isDateBlocked(room, date)) {
    tooltip += `\nBlocked: ${getBlockDateRange(room, dateStr)}`;
  }
  
  return tooltip;
}

// Get guest name for a booked date
function getGuestName(room, dateStr) {
  const date = parseDate(dateStr);
  const booking = room.bookings?.find(booking => {
    const bookingStart = parseDate(booking.startDate);
    const bookingEnd = parseDate(booking.endDate);
    return date >= bookingStart && date < bookingEnd;
  });
  return booking?.guestName || 'N/A';
}

// Get block date range for a blocked date
function getBlockDateRange(room, dateStr) {
  const date = parseDate(dateStr);
  const block = room.blockedDates?.find(blocked => {
    const blockedStart = parseDate(blocked.startDate);
    const blockedEnd = parseDate(blocked.endDate);
    return date >= blockedStart && date < blockedEnd;
  });
  if (block) {
    return `${block.startDate} - ${block.endDate}`;
  }
  return '';
}

// Event handlers
function onDayClick(room, dateStr) {
  const date = parseDate(dateStr);
  
  // Don't allow editing booked dates
  if (isDateBooked(room, date)) {
    return;
  }

  selectedRoom.value = room;
  selectedStartDate.value = dateStr;
  
  // Set end date to next day
  const nextDay = new Date(date);
  nextDay.setDate(nextDay.getDate() + 1);
  selectedEndDate.value = formatDate(nextDay);
  
  selectedPrice.value = getDayPrice(room, dateStr);
  selectedStatus.value = isDateBlocked(room, date) ? 'blocked' : 'available';
  
  showPopup.value = true;
}

function closePopup() {
  showPopup.value = false;
  selectedRoom.value = {};
  selectedStartDate.value = '';
  selectedEndDate.value = '';
  selectedPrice.value = 0;
  selectedStatus.value = '';
}

async function handleUpdate(updateData) {
  try {
    console.log('Update received:', updateData);
    
    // Here you would typically make an API call to update the data
    // await axios.put(`/api/rooms/${selectedRoom.value.id}/availability`, updateData);
    
    // For now, just update local state
    const room = rooms.value.find(r => r.id === selectedRoom.value.id);
    if (room) {
      // Update the room data based on the update
      // This is a simplified example - you'd implement based on your API structure
      if (updateData.price !== undefined) {
        // Update or add price range
        const existingPriceIndex = room.price.findIndex(p => 
          parseDate(p.startDate) <= parseDate(selectedStartDate.value) &&
          parseDate(p.endDate) >= parseDate(selectedStartDate.value)
        );
        
        if (existingPriceIndex >= 0) {
          room.price[existingPriceIndex].amount = updateData.price;
        }
      }
    }
    
    showPopup.value = false;
    
  } catch (err) {
    console.error('Error updating room:', err);
    error.value = 'Failed to update room data';
  }
}

// Watchers
watch([selectedMonth, selectedYear], () => {
  // Optionally refetch data when month changes
  // fetchRooms();
});

// Lifecycle
onMounted(() => {
  fetchRooms();
});
</script>

<style scoped>
/* Custom scrollbar for better UX */
.overflow-x-auto::-webkit-scrollbar {
  height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

/* Animation for loading spinner */
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

/* Transition effects */
.transition-colors {
  transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
}

.transition-all {
  transition: all 0.2s ease-in-out;
}
</style>