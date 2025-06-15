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
      <div class="flex gap-2">
        <button 
          v-if="selectionMode"
          @click="cancelSelection"
          class="px-4 py-1 bg-gray-500 hover:bg-gray-600 text-white rounded transition-colors"
        >
          Cancel Selection
        </button>
        <button 
          v-if="selectionMode && selectedDates.length > 0"
          @click="confirmSelection"
          class="px-4 py-1 bg-green-500 hover:bg-green-600 text-white rounded transition-colors"
        >
          Block Selected Dates ({{ selectedDates.length }})
        </button>
        <button 
          class="px-4 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded transition-colors" 
          @click="router.push('/upload-property')"
        >
          Upload Room
        </button>
      </div>
    </div>

    <!-- Selection Mode Info -->
    <div v-if="selectionMode" class="bg-blue-100 border border-blue-300 text-blue-800 px-4 py-3 rounded-lg">
      <div class="flex items-center justify-between">
        <div>
          <strong>Multi-Date Selection Mode</strong> - {{ selectedRoom?.hotel }} - {{ selectedRoom?.name }}
          <div class="text-sm mt-1">
            Click on dates to select/deselect them for blocking. Selected: {{ selectedDates.length }} dates
          </div>
        </div>
        <div class="text-right text-sm">
          <div>Start: {{ selectionStartDate || 'None' }}</div>
          <div>End: {{ selectionEndDate || 'None' }}</div>
        </div>
      </div>
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
            class="grid text-center border-b hover:bg-gray-50 transition-colors relative"
            :style="{ gridTemplateColumns: `250px repeat(${monthDays.length}, minmax(80px, 1fr))` }"
          >
            <!-- Room Info - Sticky -->
            <div class="flex items-center gap-3 p-3 border-r bg-white sticky left-0 z-23 shadow-sm">
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
                <button 
                  v-if="!selectionMode"
                  @click="startMultiSelection(room)"
                  class="mt-1 px-2 py-1 text-xs bg-blue-500 hover:bg-blue-600 text-white rounded transition-colors"
                >
                  Multi-Block
                </button>
              </div>
            </div>

            <!-- Day Cells -->
            <div
              v-for="day in monthDays"
              :key="`day-${room.id}-${day}`"
              class="h-24 border-r text-xs flex flex-col items-center justify-center cursor-pointer relative transition-all duration-200"
              :class="getDayStatusClass(room, day)"
              :title="getDayTooltip(room, day)"
              @click="onDayClick(room, day)"
              @mouseenter="onDayHover(room, day)"
            >
              <div class="font-semibold z-22">${{ getDayPrice(room, day) }}</div>
              <div class="text-xs opacity-75 z-22">{{ getDayStatus(room, day) }}</div>
              
              <!-- Selection indicator -->
              <div 
                v-if="isDateSelected(room, day)" 
                class="absolute inset-0 bg-purple-200 bg-opacity-50 border-2 border-purple-400 rounded z-5"
              ></div>
              
              <!-- Hover indicator for range selection -->
              <div 
                v-if="isDateInHoverRange(room, day)" 
                class="absolute inset-0 bg-purple-100 bg-opacity-30 border border-purple-300 rounded z-4"
              ></div>
            </div>

            <!-- Booking and Blocked Bars -->
            <div class="absolute top-0 left-[250px] w-[calc(100%-250px)] h-full z-10">
              <template v-for="(booking, i) in room.bookings" :key="'booking-' + i">
                <div
                  v-if="getBookingOffset(booking)"
                  class="absolute h-8 text-xs font-medium rounded px-2 flex items-center text-white bg-blue-500 booking-bar"
                  :style="{
                    left: `${getBookingOffset(booking).offset * (100 / monthDays.length)}%`,
                    width: `${getBookingOffset(booking).length * (100 / monthDays.length)}%`,
                    top: '10px'
                  }"
                >
                  <span class="truncate">
                    {{ booking.guestName || 'N/A' }}
                    <span v-if="getBookingOffset(booking).length > 1">
                      ({{ getBookingOffset(booking).length }} nights)
                    </span>
                  </span>
                </div>
              </template>
              <template v-for="(blockedDate, i) in room.blockedDates" :key="'blocked-' + i">
                <div
                  v-if="getBlockedOffset(blockedDate)"
                  class="absolute h-8 text-xs font-medium rounded px-2 flex items-center text-white bg-gray-500 cursor-pointer booking-bar"
                  :style="{
                    left: `${getBlockedOffset(blockedDate).offset * (100 / monthDays.length)}%`,
                    width: `${getBlockedOffset(blockedDate).length * (100 / monthDays.length)}%`,
                    top: '50px'
                  }"
                  @click.stop="onBlockedDateClick(room, blockedDate)"
                >
                  <span class="truncate">
                    Blocked
                    <span v-if="getBlockedOffset(blockedDate).length > 1">
                      ({{ getBlockedOffset(blockedDate).length }} nights)
                    </span>
                  </span>
                </div>
              </template>
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
        <div class="w-4 h-4 bg-red-100 border border-red-200 rounded booked-pulse"></div>
        <span>Booked</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="w-4 h-4 bg-gray-200 border border-gray-300 rounded"></div>
        <span>Blocked</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="w-4 h-4 bg-purple-200 border border-purple-400 rounded"></div>
        <span>Selected for blocking</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="w-4 h-4 bg-blue-50 border border-blue-200 rounded"></div>
        <span>Click to manage</span>
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

    <!-- Single Date Popup Component -->
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
      :isBooked="isDateBooked(selectedRoom, parseDate(selectedStartDate))"
      @cancel="closePopup"
      @update="handleUpdate"
    />

    <!-- Multi-Date Block Confirmation Modal -->
    <div v-if="showMultiBlockModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Confirm Block Dates</h3>
        <p class="text-gray-600 mb-4">
          Block {{ selectedDates.length }} dates for <strong>{{ selectedRoom?.hotel }} - {{ selectedRoom?.name }}</strong>?
        </p>
        <div class="text-sm text-gray-500 mb-4 max-h-32 overflow-y-auto">
          <strong>Selected dates:</strong><br>
          {{ selectedDates.sort().join(', ') }}
        </div>
        <div class="flex gap-3 justify-end">
          <button 
            @click="showMultiBlockModal = false"
            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors"
          >
            Cancel
          </button>
          <button 
            @click="confirmMultiBlock"
            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded transition-colors"
          >
            Block Dates
          </button>
        </div>
      </div>
    </div>
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

// Single date popup state
const showPopup = ref(false);
const selectedRoom = ref({});
const selectedStartDate = ref('');
const selectedEndDate = ref('');
const selectedPrice = ref(0);
const selectedStatus = ref('');

// Multi-date selection state
const selectionMode = ref(false);
const selectedDates = ref([]);
const hoverDate = ref('');
const selectionStartDate = ref('');
const selectionEndDate = ref('');
const showMultiBlockModal = ref(false);

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

const firstDay = computed(() => {
  return parseDate(monthDays.value[0]);
});

const lastDay = computed(() => {
  return parseDate(monthDays.value[monthDays.value.length - 1]);
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
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
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

function daysBetween(start, end) {
  const diff = new Date(end) - new Date(start);
  return Math.floor(diff / (1000 * 60 * 60 * 24));
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
    const response = await axios.get('http://localhost:5000/hotels', {
      timeout: 10000
    });

    rooms.value = response.data.flatMap(hotel =>
      hotel.rooms?.map(room => ({
        ...room,
        hotel: hotel.name,
        hotelId: hotel.id,
        bookings: room.bookings || [],
        blockedDates: room.blockedDates || [],
        price: room.price || [],
        basePrice: room.basePrice || 0
      })) || []
    );
  } catch (err) {
    console.error('Error fetching rooms:', err);
    error.value = err.response
      ? `Failed to fetch rooms: ${err.response.statusText}`
      : 'Failed to fetch rooms. Please check your network or try again later.';
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
  
  if (room.price && room.price.length > 0) {
    for (const priceRange of room.price) {
      const startDate = parseDate(priceRange.startDate);
      const endDate = parseDate(priceRange.endDate);
      
      if (date >= startDate && date <= endDate) {
        return priceRange.amount;
      }
    }
  }
  
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
  let classes = 'relative ';
  
  if (selectionMode.value && selectedRoom.value.id === room.id) {
    // In selection mode for this room
    if (isDateBooked(room, date)) {
      classes += 'bg-red-100 text-red-700 border-red-200 cursor-not-allowed opacity-50';
    } else if (isDateBlocked(room, date)) {
      classes += 'bg-gray-200 text-gray-600 cursor-pointer hover:bg-purple-100';
    } else {
      classes += 'bg-green-50 text-green-700 cursor-pointer hover:bg-purple-100 hover:border-purple-300';
    }
  } else {
    // Normal mode
    if (isDateBooked(room, date)) {
      classes += 'bg-red-100 text-red-700 border-red-200 cursor-pointer hover:bg-blue-50 hover:border-blue-300 booked-pulse';
    } else if (isDateBlocked(room, date)) {
      classes += 'bg-gray-200 text-gray-600 cursor-pointer hover:bg-blue-50 hover:border-blue-300';
    } else {
      classes += 'bg-green-50 text-green-700 cursor-pointer hover:bg-blue-50 hover:border-blue-300';
    }
  }
  
  return classes;
}

// Multi-date selection functions
function startMultiSelection(room) {
  selectionMode.value = true;
  selectedRoom.value = room;
  selectedDates.value = [];
  selectionStartDate.value = '';
  selectionEndDate.value = '';
  hoverDate.value = '';
}

function cancelSelection() {
  selectionMode.value = false;
  selectedRoom.value = {};
  selectedDates.value = [];
  selectionStartDate.value = '';
  selectionEndDate.value = '';
  hoverDate.value = '';
}

function isDateSelected(room, dateStr) {
  return selectionMode.value && 
         selectedRoom.value.id === room.id && 
         selectedDates.value.includes(dateStr);
}

function isDateInHoverRange(room, dateStr) {
  if (!selectionMode.value || selectedRoom.value.id !== room.id || !hoverDate.value) {
    return false;
  }
  
  if (selectedDates.value.length === 0) {
    return false;
  }
  
  // Show range from first selected date to hover date
  const firstSelected = selectedDates.value[0];
  const start = firstSelected < hoverDate.value ? firstSelected : hoverDate.value;
  const end = firstSelected < hoverDate.value ? hoverDate.value : firstSelected;
  
  return dateStr >= start && dateStr <= end && !selectedDates.value.includes(dateStr);
}

function toggleDateSelection(room, dateStr) {
  if (selectedRoom.value.id !== room.id) return;
  
  const date = parseDate(dateStr);
  
  // Don't allow selection of booked dates
  if (isDateBooked(room, date)) {
    return;
  }
  
  const index = selectedDates.value.indexOf(dateStr);
  if (index > -1) {
    // Remove date
    selectedDates.value.splice(index, 1);
  } else {
    // Add date
    selectedDates.value.push(dateStr);
  }
  
  // Update start and end dates
  if (selectedDates.value.length > 0) {
    const sortedDates = [...selectedDates.value].sort();
    selectionStartDate.value = sortedDates[0];
    selectionEndDate.value = sortedDates[sortedDates.length - 1];
  } else {
    selectionStartDate.value = '';
    selectionEndDate.value = '';
  }
}

function selectDateRange(room, startDate, endDate) {
  if (selectedRoom.value.id !== room.id) return;
  
  const start = parseDate(startDate);
  const end = parseDate(endDate);
  const actualStart = start <= end ? start : end;
  const actualEnd = start <= end ? end : start;
  
  // Add all dates in range that are not booked
  const currentDate = new Date(actualStart);
  while (currentDate <= actualEnd) {
    const dateStr = formatDate(currentDate);
    if (!isDateBooked(room, currentDate) && !selectedDates.value.includes(dateStr)) {
      selectedDates.value.push(dateStr);
    }
    currentDate.setDate(currentDate.getDate() + 1);
  }
  
  // Update start and end dates
  if (selectedDates.value.length > 0) {
    const sortedDates = [...selectedDates.value].sort();
    selectionStartDate.value = sortedDates[0];
    selectionEndDate.value = sortedDates[sortedDates.length - 1];
  }
}

function confirmSelection() {
  if (selectedDates.value.length === 0) return;
  showMultiBlockModal.value = true;
}

async function confirmMultiBlock() {
  try {
    const room = rooms.value.find(r => r.id === selectedRoom.value.id);
    if (!room) return;
    
    // Group consecutive dates into ranges
    const sortedDates = [...selectedDates.value].sort();
    const ranges = [];
    let currentStart = sortedDates[0];
    let currentEnd = sortedDates[0];
    
    for (let i = 1; i < sortedDates.length; i++) {
      const currentDate = parseDate(sortedDates[i]);
      const prevDate = parseDate(sortedDates[i - 1]);
      const dayDiff = (currentDate - prevDate) / (1000 * 60 * 60 * 24);
      
      if (dayDiff === 1) {
        // Consecutive date, extend current range
        currentEnd = sortedDates[i];
      } else {
        // Gap found, save current range and start new one
        ranges.push({ startDate: currentStart, endDate: getNextDay(currentEnd) });
        currentStart = sortedDates[i];
        currentEnd = sortedDates[i];
      }
    }
    
    // Add the last range
    ranges.push({ startDate: currentStart, endDate: getNextDay(currentEnd) });
    
    // Add ranges to blocked dates
    for (const range of ranges) {
      // Check for conflicts with existing bookings
      const isConflict = room.bookings.some(booking => {
        const bookingStart = parseDate(booking.startDate);
        const bookingEnd = parseDate(booking.endDate);
        const rangeStart = parseDate(range.startDate);
        const rangeEnd = parseDate(range.endDate);
        return rangeStart < bookingEnd && rangeEnd > bookingStart;
      });
      
      if (!isConflict) {
        room.blockedDates.push(range);
      }
    }
    
    // TODO: Send to backend
    // await axios.put(`http://localhost:5000/hotels/${room.hotelId}/rooms/${room.id}`, {
    //   blockedDates: room.blockedDates
    // });
    
    // Reset selection
    showMultiBlockModal.value = false;
    cancelSelection();
    
  } catch (err) {
    console.error('Error blocking dates:', err);
    error.value = 'Failed to block selected dates';
  }
}

function getNextDay(dateStr) {
  const date = parseDate(dateStr);
  date.setDate(date.getDate() + 1);
  return formatDate(date);
}

// Offset calculations
function getBookingOffset(booking) {
  try {
    if (!booking?.startDate || !booking?.endDate) return null;
    const start = parseDate(booking.startDate);
    const end = parseDate(booking.endDate);
    
    if (end <= firstDay.value || start > lastDay.value) return null;

    const adjustedStart = start < firstDay.value ? firstDay.value : start;
    const adjustedEnd = end > lastDay.value ? lastDay.value : end;

    const offset = daysBetween(firstDay.value, adjustedStart);
    const length = daysBetween(adjustedStart, adjustedEnd);
    return { offset, length: length > 0 ? length : 1 };
  } catch (err) {
    console.error("getBookingOffset error:", err);
    return null;
  }
}

function getBlockedOffset(blockedDate) {
  try {
    if (!blockedDate?.startDate || !blockedDate?.endDate) return null;
    const start = parseDate(blockedDate.startDate);
    const end = parseDate(blockedDate.endDate);
    
    if (end <= firstDay.value || start > lastDay.value) return null;

    const adjustedStart = start < firstDay.value ? firstDay.value : start;
    const adjustedEnd = end > lastDay.value ? lastDay.value : end;

    const offset = daysBetween(firstDay.value, adjustedStart);
    const length = daysBetween(adjustedStart, adjustedEnd);
    return { offset, length: length > 0 ? length : 1 };
  } catch (err) {
    console.error("getBlockedOffset error:", err);
    return null;
  }
}

function getDayTooltip(room, dateStr) {
  const date = parseDate(dateStr);
  const status = getDayStatus(room, dateStr);
  const price = getDayPrice(room, dateStr);
  let tooltip = `${room.hotel} - ${room.name}\nDate: ${dateStr}\nStatus: ${status}\nPrice: $${price}`;
  
  if (selectionMode.value && selectedRoom.value.id === room.id) {
    if (isDateBooked(room, date)) {
      tooltip += '\nCannot select booked dates';
    } else {
      tooltip += isDateSelected(room, dateStr) ? '\nClick to deselect' : '\nClick to select for blocking';
    }
  } else if (isDateBooked(room, date)) {
    const booking = room.bookings.find(b => {
      const start = parseDate(b.startDate);
      const end = parseDate(b.endDate);
      return date >= start && date < end;
    });
    tooltip += `\nGuest: ${booking?.guestName || 'N/A'}\nBooked: ${booking.startDate} to ${booking.endDate} (excl. last day)\nClick to manage (admin override)`;
  } else if (isDateBlocked(room, date)) {
    const block = room.blockedDates.find(b => {
      const start = parseDate(b.startDate);
      const end = parseDate(b.endDate);
      return date >= start && date < end;
    });
    tooltip += `\nBlocked: ${block.startDate} to ${block.endDate} (excl. last day)\nClick to edit block`;
  } else {
    tooltip += `\nClick to block this date`;
  }
  
  return tooltip;
}

// Event handlers
function onDayClick(room, dateStr) {
  if (selectionMode.value && selectedRoom.value.id === room.id) {
    // Handle multi-date selection
    toggleDateSelection(room, dateStr);
  } else if (!selectionMode.value) {
    // Handle single date popup
    selectedRoom.value = room;
    selectedStartDate.value = dateStr;
    
    // Set end date to the next day after the clicked date
    const clickedDate = parseDate(dateStr);
    const nextDay = new Date(clickedDate);
    nextDay.setDate(nextDay.getDate() + 1);
    selectedEndDate.value = formatDate(nextDay);
    
    selectedPrice.value = getDayPrice(room, dateStr);
    selectedStatus.value = isDateBlocked(room, parseDate(dateStr)) ? 'blocked' : isDateBooked(room, parseDate(dateStr)) ? 'booked' : 'available';
    
    console.log('Selected Start Date:', selectedStartDate.value);
    console.log('Selected End Date:', selectedEndDate.value);
    
    showPopup.value = true;
  }
}

function onDayHover(room, dateStr) {
  if (selectionMode.value && selectedRoom.value.id === room.id) {
    hoverDate.value = dateStr;
    
    // Auto-select range on shift+click
    if (selectedDates.value.length > 0) {
      // Visual feedback for range selection
    }
  }
}

function onBlockedDateClick(room, blockedDate) {
  if (!selectionMode.value) {
    selectedRoom.value = room;
    selectedStartDate.value = blockedDate.startDate;
    selectedEndDate.value = blockedDate.endDate;
    selectedPrice.value = getDayPrice(room, blockedDate.startDate);
    selectedStatus.value = 'blocked';
    showPopup.value = true;
  }
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
    
    const room = rooms.value.find(r => r.id === selectedRoom.value.id);
    if (room) {
      if (updateData.status === 'blocked') {
        const start = parseDate(updateData.startDate || selectedStartDate.value);
        const end = parseDate(updateData.endDate || selectedEndDate.value);
        const isConflict = room.bookings.some(booking => {
          const bookingStart = parseDate(booking.startDate);
          const bookingEnd = parseDate(booking.endDate);
          return start < bookingEnd && end > bookingStart;
        });
        
        if (isConflict) {
          error.value = 'Cannot block dates that overlap with existing bookings';
          return;
        }
        
        room.blockedDates = room.blockedDates.filter(block => 
          parseDate(block.startDate) !== parseDate(updateData.startDate || selectedStartDate.value) || 
          parseDate(block.endDate) !== parseDate(updateData.endDate || selectedEndDate.value)
        );
        
        room.blockedDates.push({
          startDate: updateData.startDate || selectedStartDate.value,
          endDate: updateData.endDate || selectedEndDate.value
        });
        
        // TODO: Send to backend
        // await axios.put(`http://localhost:5000/hotels/${room.hotelId}/rooms/${room.id}`, {
        //   blockedDates: room.blockedDates
        // });
      } else if (updateData.status === 'available') {
        room.blockedDates = room.blockedDates.filter(block => 
          parseDate(block.startDate) !== parseDate(updateData.startDate || selectedStartDate.value) || 
          parseDate(block.endDate) !== parseDate(updateData.endDate || selectedEndDate.value)
        );
        
        // TODO: Send to backend
        // await axios.put(`http://localhost:5000/hotels/${room.hotelId}/rooms/${room.id}`, {
        //   blockedDates: room.blockedDates
        // });
      }
      
      if (updateData.price !== undefined) {
        const start = parseDate(updateData.startDate || selectedStartDate.value);
        const end = parseDate(updateData.endDate || selectedEndDate.value);
        const existingPriceIndex = room.price.findIndex(p => 
          parseDate(p.startDate) <= start && parseDate(p.endDate) >= start
        );
        
        if (existingPriceIndex >= 0) {
          room.price[existingPriceIndex].amount = updateData.price;
          room.price[existingPriceIndex].startDate = updateData.startDate || selectedStartDate.value;
          room.price[existingPriceIndex].endDate = updateData.endDate || selectedEndDate.value;
        } else {
          room.price.push({
            startDate: updateData.startDate || selectedStartDate.value,
            endDate: updateData.endDate || selectedEndDate.value,
            amount: updateData.price
          });
        }
        
        // TODO: Send to backend
        // await axios.put(`http://localhost:5000/hotels/${room.hotelId}/rooms/${room.id}`, {
        //   price: room.price
        // });
      }
    }
    
    showPopup.value = false;
    
  } catch (err) {
    console.error('Error updating room:', err);
    error.value = 'Failed to update room data';
  }
}

// Keyboard event handlers
onMounted(() => {
  fetchRooms();
  
  // Add keyboard event listeners
  document.addEventListener('keydown', handleKeyDown);
  document.addEventListener('keyup', handleKeyUp);
});

// Cleanup event listeners
onUnmounted(() => {
  document.removeEventListener('keydown', handleKeyDown);
  document.removeEventListener('keyup', handleKeyUp);
});

const shiftPressed = ref(false);

function handleKeyDown(event) {
  if (event.key === 'Shift') {
    shiftPressed.value = true;
  }
  if (event.key === 'Escape' && selectionMode.value) {
    cancelSelection();
  }
}

function handleKeyUp(event) {
  if (event.key === 'Shift') {
    shiftPressed.value = false;
  }
}

// Enhanced day click with shift support
function onDayClickEnhanced(room, dateStr) {
  if (selectionMode.value && selectedRoom.value.id === room.id) {
    const date = parseDate(dateStr);
    
    // Don't allow selection of booked dates
    if (isDateBooked(room, date)) {
      return;
    }
    
    if (shiftPressed.value && selectedDates.value.length > 0) {
      // Range selection with shift
      const lastSelected = selectedDates.value[selectedDates.value.length - 1];
      selectDateRange(room, lastSelected, dateStr);
    } else {
      // Single date toggle
      toggleDateSelection(room, dateStr);
    }
  } else if (!selectionMode.value) {
    // Handle single date popup (existing logic)
    onDayClick(room, dateStr);
  }
}

// Watchers
watch([selectedMonth, selectedYear], () => {
  // Optionally refetch data when month changes
  // fetchRooms();
});

// Add onUnmounted import
import { onUnmounted } from 'vue';
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

/* Animation for booked dates */
@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}

.booked-pulse {
  animation: pulse 2s infinite;
}

/* Hover effect for booking and blocked bars */
.booking-bar {
  transition: transform 0.2s ease, opacity 0.2s ease;
}

.booking-bar:hover {
  transform: scale(1.05);
  opacity: 0.9;
}

/* Transition effects */
.transition-colors {
  transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
}

.transition-all {
  transition: all 0.2s ease-in-out;
}

/* Selection mode styles */
.z-4 {
  z-index: 4;
}

.z-5 {
  z-index: 5;
}

.z-22 {
  z-index: 22;
}

.z-23 {
  z-index: 23;
}

/* Multi-date selection indicators */
.selected-date {
  position: relative;
}

.selected-date::before {
  content: '';
  position: absolute;
  top: 2px;
  right: 2px;
  width: 8px;
  height: 8px;
  background: #8b5cf6;
  border-radius: 50%;
  z-index: 25;
}

/* Range selection hover effect */
.range-hover {
  background: linear-gradient(45deg, transparent 25%, rgba(139, 92, 246, 0.1) 25%, rgba(139, 92, 246, 0.1) 50%, transparent 50%, transparent 75%, rgba(139, 92, 246, 0.1) 75%);
  background-size: 8px 8px;
}

/* Confirmation modal backdrop */
.modal-backdrop {
  backdrop-filter: blur(2px);
}
</style>