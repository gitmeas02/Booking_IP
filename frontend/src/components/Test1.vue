<template>
  <button @click="goToToday" class="p-2 bg-amber-200 m-1 rounded">Today</button>

  <div class="flex items-center justify-between p-4 bg-white shadow-md">
    <div class="calendar-header flex items-center gap-4">
      <ChevronLeft @click="previousWeek" class="cursor-pointer" />
      <h2 class="text-lg font-semibold">{{ months[selectedMonth] }} {{ selectedYear }}</h2>
      <ChevronRight @click="nextWeek" class="cursor-pointer" />
    </div>
  </div>

  <div class="overflow-x-auto">
    <div class="min-w-max">
      <div class="grid grid-cols-[200px_repeat(7,1fr)] border-b text-sm font-semibold text-center bg-gray-100">
        <div class="border-r p-2">Rooms</div>
        <div v-for="(day, i) in days" :key="i" class="border-r p-2">
          <div class="flex flex-col items-center leading-tight">
            <div class="font-semibold text-gray-800">{{ getDayLabel(day.date).weekday }}</div>
            <div class="text-sm text-gray-600">{{ getDayLabel(day.date).day }}</div>
          </div>
        </div>
      </div>

      <div v-for="(room, index) in rooms" :key="index" class="grid grid-cols-[200px_repeat(7,1fr)] text-center border-b relative">
        <div class="flex items-center gap-2 p-2 border-r bg-white">
          <img :src="room.image?.[0] || 'https://via.placeholder.com/40'" class="w-10 h-10 rounded object-cover" />
          <div>
            <div class="font-bold">{{ room.hotel }}</div>
            <div class="text-xs text-gray-500">{{ room.name }}</div>
          </div>
        </div>

        <div class="relative col-span-7 grid grid-cols-7 h-16">
          <div
            v-for="(day, i) in days"
            :key="i"
            class="border-r text-xs text-gray-700 flex items-center justify-center relative z-0"
            :class="{
              'bg-gray-100': isDateBlocked(room, day.date),
              'cursor-pointer': true
            }"
            @click="(e) => handleDayClick(e, room, day, i)"
          >
            {{ getDayPrice(room, day.date) }}$
          </div>

          <template v-for="(booking, i) in room.bookings" :key="'booking-'+i">
            <div
              v-if="getBookingOffset(booking)"
              class="absolute top-2 h-10 text-xs font-medium rounded-full px-2 flex items-center text-white z-10"
              :class="'bg-blue-500'"
              :style="{
                left: `${getBookingOffset(booking).offset * (100 / days.length)}%`,
                width: `${getBookingOffset(booking).length * (100 / days.length)}%`
              }"
            >
              {{ booking.guest }} ({{ getBookingOffset(booking).length }} nights)
            </div>
          </template>

          <template v-for="(blockedDate, i) in room.blockedDates" :key="'blocked-'+i">
            <div
              v-if="getBlockedOffset(blockedDate)"
              class="absolute top-2 h-10 text-xs font-medium rounded-full px-2 flex items-center text-white z-10"
              :class="'bg-gray-500'"
              :style="{
                left: `${getBlockedOffset(blockedDate).offset * (100 / days.length)}%`,
                width: `${getBlockedOffset(blockedDate).length * (100 / days.length)}%`
              }"
            >
              Blocked
              <span v-if="getBlockedOffset(blockedDate).length > 1">
                ({{ getBlockedOffset(blockedDate).length }} nights)
              </span>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import { ChevronRight, ChevronLeft } from 'lucide-vue-next';

// Month labels
const months = [
  'January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December'
];

// Calendar state
const calendarStart = ref(new Date());
const calendarLength = 7;
const selectedMonth = ref(calendarStart.value.getMonth());
const selectedYear = ref(calendarStart.value.getFullYear());

// Generate days for the header
const days = computed(() => {
  const week = [];
  for (let i = 0; i < calendarLength; i++) {
    const date = new Date(calendarStart.value);
    date.setDate(calendarStart.value.getDate() + i);
    week.push({ date });
  }
  return week;
});

// Improved getDayLabel function
function getDayLabel(date) {
  const weekdayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
  return {
    weekday: weekdayNames[date.getDay()],
    day: date.getDate(),
    month: date.getMonth(),
    year: date.getFullYear()
  };
}

// Utils
function formatDate(date) {
  const yyyy = date.getFullYear();
  const mm = String(date.getMonth() + 1).padStart(2, '0');
  const dd = String(date.getDate()).padStart(2, '0');
  return `${yyyy}-${mm}-${dd}`;
}

function parseDate(dateStr) {
  const parts = dateStr.split('-').map(Number);
  return new Date(parts[0], parts[1] - 1, parts[2]);
}

function daysBetween(start, end) {
  const msPerDay = 1000 * 60 * 60 * 24;
  return Math.floor((end - start) / msPerDay);
}

function isDateBlocked(room, date) {
  if (!room.blockedDates) return false;
  const dateStr = formatDate(date);
  return room.blockedDates.some(blocked => 
    dateStr >= blocked.startDate && dateStr <= blocked.endDate
  );
}

function getDayPrice(room, date) {
  const dateStr = formatDate(date);
  // Check for specific price for this date if you have that data
  return room.basePrice || 0;
}

// Room data
const isLoading = ref(false);
const hotels = ref([]);
const rooms = ref([]);

async function fetchHotels() {
  isLoading.value = true;
  try {
    const response = await fetch('http://localhost:5000/hotels');
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    hotels.value = data;
    rooms.value = hotels.value.flatMap(hotel =>
      hotel.rooms.map(room => ({
        ...room,
        hotel: hotel.name,
        hotelId: hotel.id
      }))
    );
  } catch (error) {
    console.error('Error fetching hotels:', error);
    hotels.value = [];
    rooms.value = [];
  } finally {
    isLoading.value = false;
  }
}

// Calendar navigation
function previousWeek() {
  const newDate = new Date(calendarStart.value);
  newDate.setDate(newDate.getDate() - 7);
  calendarStart.value = newDate;
  updateCalendar();
}

function nextWeek() {
  const newDate = new Date(calendarStart.value);
  newDate.setDate(newDate.getDate() + 7);
  calendarStart.value = newDate;
  updateCalendar();
}

function goToToday() {
  calendarStart.value = new Date();
  updateCalendar();
}

function updateCalendar() {
  selectedMonth.value = calendarStart.value.getMonth();
  selectedYear.value = calendarStart.value.getFullYear();
}

// Day click handler
function handleDayClick(event, room, day, dayIndex) {
  const action = prompt("Enter 'price' to update price or 'block' to block the room:");
  
  if (action === 'price') {
    const newPrice = prompt("Enter the new price:", room.basePrice);
    if (newPrice) {
      // Here you would update the price in your data store
      console.log(`Updated price for room ${room.name} on ${formatDate(day.date)} to ${newPrice}`);
    }
  } else if (action === 'block') {
    const isBlocked = isDateBlocked(room, day.date);
    if (isBlocked) {
      if (confirm("This date is already blocked. Unblock it?")) {
        // Here you would unblock the date in your data store
        console.log(`Unblocked room ${room.name} on ${formatDate(day.date)}`);
      }
    } else {
      if (confirm("Block this room for the selected date?")) {
        // Here you would block the date in your data store
        console.log(`Blocked room ${room.name} on ${formatDate(day.date)}`);
      }
    }
  }
}

// Offset calculations
function getBookingOffset(booking) {
  try {
    if (!booking?.startDate || !booking?.endDate) return null;
    const start = parseDate(booking.startDate);
    const end = parseDate(booking.endDate);
    const calendarEnd = new Date(calendarStart.value);
    calendarEnd.setDate(calendarStart.value.getDate() + calendarLength - 1);

    if (start > calendarEnd || end < calendarStart.value) return null;

    const adjustedStart = start < calendarStart.value ? calendarStart.value : start;
    const adjustedEnd = end > calendarEnd ? calendarEnd : end;

    const offset = daysBetween(calendarStart.value, adjustedStart);
    const length = daysBetween(adjustedStart, adjustedEnd) + 1;
    return { offset, length };
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
    const calendarEnd = new Date(calendarStart.value);
    calendarEnd.setDate(calendarStart.value.getDate() + calendarLength - 1);

    if (start > calendarEnd || end < calendarStart.value) return null;

    const adjustedStart = start < calendarStart.value ? calendarStart.value : start;
    const adjustedEnd = end > calendarEnd ? calendarEnd : end;

    const offset = daysBetween(calendarStart.value, adjustedStart);
    const length = daysBetween(adjustedStart, adjustedEnd) + 1;
    return { offset, length };
  } catch (err) {
    console.error("getBlockedOffset error:", err);
    return null;
  }
}

onMounted(() => {
  fetchHotels();
});
</script>

<style scoped>
.calendar-header {
  min-width: 200px;
  justify-content: center;
}
</style>