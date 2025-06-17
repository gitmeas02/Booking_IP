<template>
  <div>
    <HeaderFilters 
      @manage-hotel="handleManageHotel" 
      @location-change="handleLocationChange"
      @amount-change="handleAmountChange" 
      @message-click="handleMessage" 
    />

    <CalendarSection />
    <StatisticsCards :stats="statistics" />

    <BookingTable
      :bookings="filteredBookings"
      @action-click="handleActionClick"
    />

    <PaginationControls
      :current-page="currentPage"
      :total-pages="totalPages"
      @page-change="handlePageChange"
    />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import HeaderFilters from '@/components/AdminBookingList/HeaderFilters.vue'
import CalendarSection from '@/components/AdminBookingList/CalendarSection.vue'
import StatisticsCards from '@/components/AdminBookingList/StatisticsCards.vue'
import BookingTable from '@/components/AdminBookingList/BookingTable.vue'
import PaginationControls from '@/components/AdminBookingList/PaginationControls.vue'

// Reactive state
const currentPage = ref(1)
const bookingsPerPage = 6

// Sample booking data
const bookings = ref([
  {
    id: 1,
    name: 'John Doe',
    email: 'john@gmail.com',
    avatar: 'https://via.placeholder.com/32x32/4f46e5/ffffff?text=JD',
    ownerHotelId: 'KH123',
    location: 'Kampot',
    rooms: 201,
    fee: 1233,
    paymentStatus: 'Paid',
    propertyName: 'Khun Hotel',
    from: '13 April 2025',
    to: '17 April 2025',
    status: 'Able to Book'
  },
  {
    id: 2,
    name: 'John Doe',
    email: 'john@gmail.com',
    avatar: 'https://via.placeholder.com/32x32/4f46e5/ffffff?text=JD',
    ownerHotelId: 'KH123',
    location: 'Kampot',
    rooms: 201,
    fee: 1233,
    paymentStatus: 'Unpaid',
    propertyName: 'Khun Hotel',
    from: '13 April 2025',
    to: '17 April 2025',
    status: 'Unable to Book'
  },
  {
    id: 3,
    name: 'John Doe',
    email: 'john@gmail.com',
    avatar: 'https://via.placeholder.com/32x32/4f46e5/ffffff?text=JD',
    ownerHotelId: 'KH123',
    location: 'Kampot',
    rooms: 201,
    fee: 1233,
    paymentStatus: 'Paid',
    propertyName: 'Khun Hotel',
    from: '13 April 2025',
    to: '17 April 2025',
    status: 'Decision'
  },
  {
    id: 4,
    name: 'John Doe',
    email: 'john@gmail.com',
    avatar: 'https://via.placeholder.com/32x32/4f46e5/ffffff?text=JD',
    ownerHotelId: 'KH123',
    location: 'Kampot',
    rooms: 201,
    fee: 1233,
    paymentStatus: 'Paid',
    propertyName: 'Khun Hotel',
    from: '13 April 2025',
    to: '17 April 2025',
    status: 'Request'
  },
  {
    id: 5,
    name: 'John Doe',
    email: 'john@gmail.com',
    avatar: 'https://via.placeholder.com/32x32/4f46e5/ffffff?text=JD',
    ownerHotelId: 'KH123',
    location: 'Kampot',
    rooms: 201,
    fee: 1233,
    paymentStatus: 'Paid',
    propertyName: 'Khun Hotel',
    from: '13 April 2025',
    to: '17 April 2025',
    status: 'Able Book'
  },
  {
    id: 6,
    name: 'John Doe',
    email: 'john@gmail.com',
    avatar: 'https://via.placeholder.com/32x32/4f46e5/ffffff?text=JD',
    ownerHotelId: 'KH123',
    location: 'Kampot',
    rooms: 201,
    fee: 1233,
    paymentStatus: 'Paid',
    propertyName: 'Khun Hotel',
    from: '13 April 2025',
    to: '17 April 2025',
    status: 'Able Book'
  },
  {
    id: 7,
    name: 'Jane Smith',
    email: 'jane@example.com',
    avatar: 'https://via.placeholder.com/32x32/22c55e/ffffff?text=JS',
    ownerHotelId: 'KH456',
    location: 'Siem Reap',
    rooms: 105,
    fee: 1500,
    paymentStatus: 'Unpaid',
    propertyName: 'Angkor Villa',
    from: '15 May 2025',
    to: '20 May 2025',
    status: 'Able to Book'
  }
])

// Automatically calculated total pages
const totalPages = computed(() => {
  return Math.ceil(bookings.value.length / bookingsPerPage)
})

// Slice bookings per current page
const filteredBookings = computed(() => {
  const start = (currentPage.value - 1) * bookingsPerPage
  const end = start + bookingsPerPage
  return bookings.value.slice(start, end)
})

// Statistics
const statistics = ref({
  totalCustomer: { value: 183958, change: 24, isPositive: true },
  feeToday: { value: 183, change: 20, isNegative: true },
  unpaid: { value: 183, change: 24, isNegative: true },
  paid: { value: 183, change: 24, isPositive: true }
})

// Handlers
const handleManageHotel = (value) => {
  console.log('Manage Hotel:', value)
}

const handleLocationChange = (value) => {
  console.log('Location Change:', value)
}

const handleAmountChange = (value) => {
  console.log('Amount Change:', value)
}

const handleMessage = () => {
  console.log('Message clicked')
}

const handleActionClick = (action, booking) => {
  console.log('Action:', action, 'Booking:', booking)
}

const handlePageChange = (page) => {
  currentPage.value = page
}
</script>
