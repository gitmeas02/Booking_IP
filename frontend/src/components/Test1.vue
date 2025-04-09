<template>
    <!-- Scroll to current calendar date -->
    <button @click="goToToday" class="p-2 bg-amber-200 m-1 rounded">Today</button>
  
    <div class="flex items-center justify-between p-4 bg-white shadow-md">
      <div class="calendar-header flex items-center gap-4">
        <ChevronLeft @click="previousWeek" class="cursor-pointer"/>
        <!-- Display month and year -->
        <h2 class="text-lg font-semibold">{{ months[selectedMonth] }} {{ selectedYear }}</h2>
        <ChevronRight @click="nextWeek" class="cursor-pointer"/>
      </div>
    </div>
  
    <div class="overflow-x-auto">
      <div class="min-w-max">
        <!-- Calendar Header -->
        <div class="grid grid-cols-[200px_repeat(7,1fr)] border-b text-sm font-semibold text-center bg-gray-100">
          <div class="border-r p-2">Rooms</div>
          <div v-for="(day, i) in days" :key="i" class="border-r p-2 whitespace-pre">
            {{ formatHeader(day.date) }}
          </div>
        </div>
  
        <!-- Calendar Rows -->
        <div v-for="(room, index) in rooms" :key="index" class="grid grid-cols-[200px_repeat(7,1fr)] text-center border-b relative">
          <!-- Room Info -->
          <div class="flex items-center gap-2 p-2 border-r bg-white">
            <img :src="room.image" class="w-10 h-10 rounded object-cover" />
            <div>
              <div class="font-bold">{{ room.hotel }}</div>
              <div class="text-xs text-gray-500">{{ room.name }}</div>
            </div>
          </div>
  
          <!-- Booking Grid -->
          <div class="relative col-span-7 grid grid-cols-7 h-16">
            <!-- Price Cells -->
            <div v-for="(day, i) in days" :key="i" 
                 class="border-r text-xs text-gray-700 flex items-center justify-center relative z-0">
              {{ day.price }}$
            </div>
  
            <!-- Booking Bars -->
            <template v-for="(booking, i) in room.bookings" :key="'booking-'+i">
              <div v-if="booking && getBookingOffset(booking)"
                class="absolute top-2 h-10 text-xs font-medium rounded-full px-2 flex items-center text-white z-10"
                :class="'bg-blue-500'"
                :style="{
                  left: `${getBookingOffset(booking).offset * (100 / days.length)}%`,
                  width: `${getBookingOffset(booking).length * (100 / days.length)}%`
                }">
                {{ booking.guest }} 
                ({{ getBookingOffset(booking).length }} nights)
              </div>
            </template>
  
            <!-- Blocked Date Bars -->
            <template v-for="(blockedDate, i) in room.blockedDates" :key="'blocked-'+i">
              <div v-if="getBlockedOffset(blockedDate)"
                class="absolute top-2 h-10 text-xs font-medium rounded-full px-2 flex items-center text-white z-10"
                :class="'bg-gray-500'"
                :style="{
                  left: `${getBlockedOffset(blockedDate).offset * (100 / days.length)}%`,
                  width: `${getBlockedOffset(blockedDate).length * (100 / days.length)}%`
                }">
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
  import { ref } from 'vue'
  import { ChevronRight, ChevronLeft } from 'lucide-vue-next'
  
  // Month labels
  const months = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ]
  
  // Utils for Date Formatting
  function formatDate(date) {
    const yyyy = date.getFullYear()
    const mm = String(date.getMonth() + 1).padStart(2, '0')
    const dd = String(date.getDate()).padStart(2, '0')
    return `${yyyy}-${mm}-${dd}`
  }
  
  function formatHeader(date) {
    const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]
    return `${dayNames[date.getDay()]}\n${date.getDate()}`
  }
  
  function parseDate(dateStr) {
    return new Date(dateStr + "T00:00:00")
  }
  
  function daysBetween(start, end) {
    const msPerDay = 1000 * 60 * 60 * 24
    return Math.floor((end - start) / msPerDay)
  }
  
  // Calendar control
  const calendarStart = ref(new Date())
  const calendarLength = 7
  const selectedMonth = ref(calendarStart.value.getMonth())
  const selectedYear = ref(calendarStart.value.getFullYear())
  
  // Initialize the days for the current week
  const days = ref(generateWeek(calendarStart.value))
  
  // Generate a week of days
  function generateWeek(startDate) {
    const week = []
    for (let i = 0; i < calendarLength; i++) {
      const date = new Date(startDate)
      date.setDate(startDate.getDate() + i)
      week.push({ date, price: 205 })
    }
    return week
  }
  
  // Hotel/Room/Booking Data
  const hotels = [
    {
      id: 1,
      name: 'Royal Palace',
      location: 'Paris, France',
      rooms: [
        {
          id: 101,
          name: 'Royal Room 1',
          type: 'Queen Bed',
          hotelId: 1,
          number: '469',
          basePrice: 168,
          taxes: 20,
          image: './src/assets/image.png',
          bookings: [
            {
              startDate: formatDate(new Date(2025, 3, 16)),
              endDate: formatDate(new Date(2025, 3, 17)),
              guest: 'Emma Wilson',
            },
            {
              startDate: formatDate(new Date(2025, 3, 17)),
              endDate: formatDate(new Date(2025, 3, 18)),
              guest: 'Emma Wilson',
            },
            {
              startDate: formatDate(new Date(2025, 3, 19)),
              endDate: formatDate(new Date(2025, 3, 21)),
              guest: 'Emma Wilson',
            },
            {
              startDate: formatDate(new Date(2025, 3, 23)),
              endDate: formatDate(new Date(2025, 3, 26)),
              guest: 'Emma Wilson',
            }
          ],
          blockedDates: [
            {
              startDateBlock: formatDate(new Date(2025, 3, 12)),
              endDateBlock: formatDate(new Date(2025, 3, 15)),
            },
            {
              startDateBlock: formatDate(new Date(2025, 3, 9)),
              endDateBlock: formatDate(new Date(2025, 3, 10)),
            },
            {
                startDateBlock: formatDate(new Date(2025, 3, 10)),
              endDateBlock: formatDate(new Date(2025, 3, 11)),
            },{
                startDateBlock: formatDate(new Date(2025, 3, 1)),
              endDateBlock: formatDate(new Date(2025, 3, 4)),
            
            }
          ]
        }
      ]
    }
  ]
  
  const rooms = hotels.flatMap(hotel =>
    hotel.rooms.map(room => ({
      ...room,
      hotel: hotel.name
    }))
  )
  
  // Navigation functions
  function previousWeek() {
    const newDate = new Date(calendarStart.value)
    newDate.setDate(newDate.getDate() - 7)
    calendarStart.value = newDate
    updateCalendar()
  }
  
  function nextWeek() {
    const newDate = new Date(calendarStart.value)
    newDate.setDate(newDate.getDate() + 7)
    calendarStart.value = newDate
    updateCalendar()
  }
  
  function updateCalendar() {
    days.value = generateWeek(calendarStart.value)
    selectedMonth.value = calendarStart.value.getMonth()
    selectedYear.value = calendarStart.value.getFullYear()
  }
  
  function goToToday() {
    const today = new Date()
    calendarStart.value = today
    updateCalendar()
  }
  
  // Booking layout calculation
  function getBookingOffset(booking) {
    try {
      if (!booking?.startDate || !booking?.endDate) return null
  
      const start = parseDate(booking.startDate)
      const end = parseDate(booking.endDate)
      const calendarEnd = new Date(calendarStart.value)
      calendarEnd.setDate(calendarStart.value.getDate() + calendarLength - 1)
  
      if (start > calendarEnd || end < calendarStart.value) return null
  
      const adjustedStart = start < calendarStart.value ? calendarStart.value : start
      const adjustedEnd = end > calendarEnd ? calendarEnd : end
  
      const offset = daysBetween(calendarStart.value, adjustedStart-1)+1
      const length = daysBetween(adjustedStart, adjustedEnd)+1
  
      return { offset, length }
    } catch (err) {
      console.error("getBookingOffset error:", err)
      return null
    }
  }
  
  // Blocked dates layout calculation
  function getBlockedOffset(blockedDate) {
    try {
      if (!blockedDate?.startDateBlock || !blockedDate?.endDateBlock) return null
  
      const start = parseDate(blockedDate.startDateBlock)
      const end = parseDate(blockedDate.endDateBlock)
      const calendarEnd = new Date(calendarStart.value)
      calendarEnd.setDate(calendarStart.value.getDate() + calendarLength - 1)
  
      if (start > calendarEnd || end < calendarStart.value) return null
  
      const adjustedStart = start < calendarStart.value ? calendarStart.value : start
      const adjustedEnd = end > calendarEnd ? calendarEnd : end
  
      const offset = daysBetween(calendarStart.value, adjustedStart-1)+1
      const length = daysBetween(adjustedStart, adjustedEnd)+1
  
      return { offset, length }
    } catch (err) {
      console.error("getBlockedOffset error:", err)
      return null
    }
  }
  </script>
  
  <style scoped>
  .calendar-header {
    min-width: 200px;
    justify-content: center;
  }
  </style>
  