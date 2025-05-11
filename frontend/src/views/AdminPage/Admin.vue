<template>
  <button @click="goToToday" class="p-2 bg-amber-200 m-1 rounded">Today</button>

  <div class="flex items-center justify-between p-4 bg-white shadow-md">
    <div class="calendar-header flex items-center gap-4">
      <ChevronLeft @click="previousWeek" class="cursor-pointer" />
      <h2 class="text-lg font-semibold">
        {{ months[selectedMonth] }} {{ selectedYear }}
      </h2>
      <ChevronRight @click="nextWeek" class="cursor-pointer" />
    </div>
  </div>

  <div class="overflow-x-auto">
    <div class="min-w-max">
      <div
        class="grid grid-cols-[200px_repeat(7,1fr)] border-b text-sm font-semibold text-center bg-gray-100"
      >
        <div class="border-r p-2">Rooms</div>
        <div v-for="(day, i) in days" :key="i" class="border-r p-2">
          <div class="flex flex-col items-center leading-tight">
            <div class="font-semibold text-gray-800">
              {{ getDayLabel(day.date).weekday }}
            </div>
            <div class="text-sm text-gray-600">
              {{ getDayLabel(day.date).day }}
            </div>
          </div>
        </div>
      </div>

      <div
        v-for="(room, index) in rooms"
        :key="index"
        class="grid grid-cols-[200px_repeat(7,1fr)] text-center border-b relative"
      >
        <div class="flex items-center gap-2 p-2 border-r bg-white">
          <img
            :src="room.image?.[0] || 'https://via.placeholder.com/40'"
            class="w-10 h-10 rounded object-cover"
          />
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
              'bg-red-100': isDateBooked(room, day.date),
              'cursor-pointer': !isDateBooked(room, day.date),
              'cursor-not-allowed': isDateBooked(room, day.date),
            }"
            @click="(e) => handleDayClick(e, room, day, i)"
          >
            {{ getDayPrice(room, day.date) }}$
          </div>

          <template v-for="(booking, i) in room.bookings" :key="'booking-' + i">
            <div
              v-if="getBookingOffset(booking)"
              class="absolute top-2 h-10 text-xs font-medium rounded-full px-2 flex items-center text-white z-10"
              :class="'bg-blue-500'"
              :style="{
                left: `${
                  getBookingOffset(booking).offset * (100 / days.length)
                }%`,
                width: `${
                  getBookingOffset(booking).length * (100 / days.length)
                }%`,
              }"
            >
              {{ booking.guest }} ({{ getBookingOffset(booking).length }}
              nights)
            </div>
          </template>

          <template
            v-for="(blockedDate, i) in room.blockedDates"
            :key="'blocked-' + i"
          >
            <div
              v-if="getBlockedOffset(blockedDate)"
              class="absolute top-2 h-10 text-xs font-medium rounded-full px-2 flex items-center text-white z-10 cursor-pointer"
              :class="'bg-gray-500'"
              :style="{
                left: `${
                  getBlockedOffset(blockedDate).offset * (100 / days.length)
                }%`,
                width: `${
                  getBlockedOffset(blockedDate).length * (100 / days.length)
                }%`,
              }"
              @click.stop="handleBlockedDateClick(room, blockedDate)"
            >
              Blocked
              <span v-if="getBlockedOffset(blockedDate).length > 1">
                ({{ getBlockedOffset(blockedDate).length }} nights)
              </span>
            </div>
          </template>
        </div>
      </div>

      <ControllDateRoom
        v-if="showPopup"
        :roomImage="selectedRoom.image?.[0] || 'https://via.placeholder.com/40'"
        :startDate="selectedStartDate"
        :endDate="selectedEndDate"
        :hotelName="'Khun Hotel'"
        :roomDetail="'King bed room 0101'"
        :displayDate="'Saturday/1/2025'"
        :initialPrice="selectedPrice"
        :initialStatus="selectedStatus"
        @cancel="showPopup = false"
        @update="handleUpdate"
      />
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from "vue";
import { ChevronRight, ChevronLeft } from "lucide-vue-next";
import ControllDateRoom from "@/components/AdminComponents/ControllDateRoom.vue";
// Month labels
const months = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
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
  const weekdayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
  return {
    weekday: weekdayNames[date.getDay()],
    day: date.getDate(),
    month: date.getMonth(),
    year: date.getFullYear(),
  };
}

// Utils
function formatDate(date) {
  const yyyy = date.getFullYear();
  const mm = String(date.getMonth() + 1).padStart(2, "0");
  const dd = String(date.getDate()).padStart(2, "0");
  return `${yyyy}-${mm}-${dd}`;
}

function parseDate(dateStr) {
  const parts = dateStr.split("-").map(Number);
  return new Date(parts[0], parts[1] - 1, parts[2]);
}

function daysBetween(start, end) {
  const msPerDay = 1000 * 60 * 60 * 24;
  return Math.floor((end - start) / msPerDay);
}

function isDateBooked(room, date) {
  if (!room.bookings) return false;
  const dateStr = formatDate(date);
  return room.bookings.some(
    (booking) => dateStr >= booking.startDate && dateStr < booking.endDate
  );
}

function isDateBlocked(room, date) {
  if (!room.blockedDates) return false;
  const dateStr = formatDate(date);
  return room.blockedDates.some(
    (blocked) => dateStr >= blocked.startDate && dateStr < blocked.endDate
  );
}
function getDayPrice(room, date) {
  // const dateStr = formatDate(date);

  // Loop over each price range to check for overlap
  if (room.price && Array.isArray(room.price)) {
    for (const range of room.price) {
      const start = parseDate(range.startDate);
      const end = parseDate(range.endDate);

      if (date >= start && date <= end) {
        return range.amount;
      }
    }
  }

  return room.basePrice || range.amount;
}

// Room data
const isLoading = ref(false);
const hotels = ref([]);
const rooms = ref([]);

import axios from 'axios';

async function fetchHotels() {
  isLoading.value = true;
  try {
    const response = await axios.get("api/hotels");
    const data = response.data;
    hotels.value = data;
    rooms.value = hotels.value.flatMap((hotel) =>
      hotel.rooms.map((room) => ({
        ...room,
        hotel: hotel.name,
        hotelId: hotel.id,
      }))
    );
  } catch (error) {
    console.error("Error fetching hotels:", error);
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
const showPopup = ref(false);
const selectedStartDate = ref("");
const selectedEndDate = ref("");
const selectedStatus = ref("");
const selectedPrice = ref(0);
const selectedRoom = ref(null);
function handleDayClick(event, room, day, dayIndex) {
  // Don't proceed if the date is booked
  if (isDateBooked(room, day.date)) {
    return;
  }

  // Check if we're clicking on a blocked date overlay
  const isClickingBlockedOverlay = event.target.closest(".bg-gray-500");
  if (isClickingBlockedOverlay) {
    return; // Let the blocked date handler take care of it
  }

  // Set start date to clicked day
  selectedStartDate.value = formatDate(day.date);

  // Set end date to next day (1 night stay)
  const nextDay = new Date(day.date);
  nextDay.setDate(day.date.getDate() + 1);
  selectedEndDate.value = formatDate(nextDay);

  selectedStatus.value = isDateBlocked(room, day.date)
    ? "blocked"
    : "available";
  selectedPrice.value = getDayPrice(room, day.date);
  showPopup.value = true;
  selectedRoom.value = room;
}
function handleBlockedDateClick(room, blockedDate) {
  selectedStartDate.value = blockedDate.startDate;
  selectedEndDate.value = blockedDate.endDate;
  selectedStatus.value = "blocked";
  selectedPrice.value = getDayPrice(room, parseDate(blockedDate.startDate));
  showPopup.value = true;
  selectedRoom.value = room;
}
function closePopup() {
  popupVisible.value = false;
}

function savePopupData(data) {
  console.log("Saving new data:", data);
  // TODO: Update room's price array or send to backend
  closePopup();
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

    const adjustedStart =
      start < calendarStart.value ? calendarStart.value : start;
    const adjustedEnd = end > calendarEnd ? calendarEnd : end;

    const offset = daysBetween(calendarStart.value, adjustedStart - 1) + 1;
    const length = daysBetween(adjustedStart, adjustedEnd - 1) + 1;
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

    const adjustedStart =
      start < calendarStart.value ? calendarStart.value : start;
    const adjustedEnd = end > calendarEnd ? calendarEnd : end;

    const offset = daysBetween(calendarStart.value, adjustedStart - 1) + 1;
    const length = daysBetween(adjustedStart, adjustedEnd - 1) + 1;
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
