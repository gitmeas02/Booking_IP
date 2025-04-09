<template>
  <div class="admin-dashboard">
    <header class="dashboard-header">
      <h1>Room Booking Management</h1>
      <p class="total-rooms">{{ filteredRooms.length }} Rooms</p>
      <button class="btn-show-bookings" @click="showBookingsModal = true">
        View All Bookings
      </button>
    </header>
    <button @click="scrollToToday" class="p-12 bg-amber-200 m-1">Today</button>
    <div class="dashboard-content">
      <!-- Calendar Navigation -->
      <section class="calendar-control-section">
        <div class="calendar-header">
          <button class="calendar-nav" @click="previousWeek">‹</button>
          <h2>{{ months[selectedMonth] }} {{ selectedYear }}</h2>
          <button class="calendar-nav" @click="nextWeek">›</button>
        </div>
        <div class="date-range">
          {{ formatDate(currentWeekStart) }} - {{ formatDate(getWeekEndDate()) }}
        </div>
      </section>

   

      <!-- Calendar Display -->
      <section class="calendar-section overflow-x-auto pl-12 pr-12">
  <table class="booking-table min-w-max table-auto w-full  border-collapse border border-gray-300">
    <thead>
      <tr>
        <th class="room-header px-4 py-2 border border-gray-300">Room</th>
        <th
          v-for="day in visibleWeekDays"
          :key="day.date"
          class="day-header px-4 py-2 border border-gray-300"
        >
          {{ day.name }}<br>{{ day.date.split('-')[2] }}
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="room in filteredRooms" :key="room.id">
        <td class="room-info flex flex-row items-center space-x-4 py-2 pl-10 border border-gray-300">
          <img :src="room.image" alt="Room Image" class="room-image w-24 h-24 object-cover rounded" />
          <div> 
            <div class="room-name font-semibold text-lg w-[300px] overflow-hidden">{{ room.name }}</div>
            <div class="room-type text-sm text-gray-600">{{ room.type }} - {{ room.number }}</div>
          </div>
        </td>

        <td
          v-for="day in visibleWeekDays"
          :key="day.date"
          :class="getStatusClass(getRoomStatus(room, day.date))"
          :data-date="day.date"
          @click="selectRoomDate(room, day.date)"
          class="px-4 py-2 cursor-pointer border border-gray-300"
        >
          <div class="status-container flex justify-center items-center flex-col">
            <div class="status-text">{{ getRoomStatus(room, day.date) }}</div>
            <div class="room-price">${{ getRoomPrice(room, day.date) || '—' }}</div>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</section>


    </div>

    <!-- Bookings Modal -->
    <div v-if="showBookingsModal" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Current Bookings</h2>
          <button class="close-modal" @click="showBookingsModal = false">×</button>
        </div>
        <div class="modal-body">
          <div class="bookings-calendar">
            <div class="calendar-header">
              <button @click="previousMonth">‹</button>
              <h3>{{ months[bookingsMonth] }} {{ bookingsYear }}</h3>
              <button @click="nextMonth">›</button>
            </div>
            <div class="calendar-grid">
              <div class="day-header" v-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" :key="day">
                {{ day }}
              </div>
              <div 
                v-for="day in calendarDays" 
                :key="day.date"
                :class="['calendar-day', { 'other-month': !day.isCurrentMonth }]"
              >
                <div class="day-number">{{ day.date.getDate() }}</div>
                <div 
                  v-for="booking in getBookingsForDay(day.date)" 
                  :key="`${booking.roomId}-${booking.startDate}`"
                  class="booking-event"
                >
                  {{ booking.roomName }} - {{ booking.guest }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import { useRoomStore } from '@/store/store.js'
import { onMounted, computed } from 'vue'
export default {
  name: 'AdminDashboard',
  data() {
    const currentDate = new Date();
    return {
      months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 
               'August', 'September', 'October', 'November', 'December'],
      selectedMonth: currentDate.getMonth(),
      selectedYear: currentDate.getFullYear(),
      currentWeekStart: this.getWeekStartDate(currentDate),
      selectedRoomId: '',
      selectedDate: this.formatDate(currentDate),
      roomPrice: 0,
      showBookingsModal: false,
      bookingsMonth: currentDate.getMonth(),
      bookingsYear: currentDate.getFullYear(),
      selectedMonth: new Date().getMonth(),
      selectedYear: new Date().getFullYear(),
      rooms: [
        {
          id: 1,
          name: 'Royal Palace cell to create visible room',
          image: './src/assets/image.png',
          type: 'Queen Bed',
          number: '469',
          basePrice: 168,
          dynamicPricing: {},
          bookings: [
            { startDate: this.formatDate(new Date(2025, 3, 10)), 
              endDate: this.formatDate(new Date(2025, 3, 13)), 
              guest: 'Emma Wilson' }
          ],
          blockedDates: [this.formatDate(new Date(2025, 3, 5)),this.formatDate(new Date(2025, 3, 14)),]
        },
        {
          id: 2,
          name: 'Ocean View',
          image: './src/assets/image.png',
          type: 'Deluxe Room',
          number: '201',
          basePrice: 220,
          dynamicPricing: {
            [this.formatDate(new Date(2025, 3, 15))]: 250
          },
          bookings: [
            { startDate: this.formatDate(new Date(2025, 3, 8)), 
              endDate: this.formatDate(new Date(2025, 3, 10)), 
              guest: 'John Smith' }
          ],
          blockedDates: []
        },
        {
          id: 3,
          name: 'Ocean View',
          image: './src/assets/image.png',
          type: 'Deluxe Room',
          number: '201',
          basePrice: 220,
          dynamicPricing: {
            [this.formatDate(new Date(2025, 3, 15))]: 250
          },
          bookings: [
            { startDate: this.formatDate(new Date(2025, 4, 8)), 
              endDate: this.formatDate(new Date(2025, 4, 10)), 
              guest: 'John Smith' }
          ],
          blockedDates: []
        }
      ]
    }
  },
  computed: {
    filteredRooms() {
      return this.rooms;
    },
    visibleWeekDays() {
      const days = [];
      for (let i = 0; i < 7; i++) {
        const date = new Date(this.currentWeekStart);
        date.setDate(date.getDate() + i);
        days.push({
          name: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][date.getDay()],
          date: this.formatDate(date)
        });
      }
      return days;
    },
    selectedRoom() {
      return this.rooms.find(room => room.id === this.selectedRoomId);
    },
    calendarDays() {
      const days = [];
      const firstDayOfMonth = new Date(this.bookingsYear, this.bookingsMonth, 1);
      const lastDayOfMonth = new Date(this.bookingsYear, this.bookingsMonth + 1, 0);
      
      // Days from previous month
      const prevMonthDays = firstDayOfMonth.getDay();
      for (let i = prevMonthDays - 1; i >= 0; i--) {
        const date = new Date(firstDayOfMonth);
        date.setDate(date.getDate() - (i + 1));
        days.push({ date, isCurrentMonth: false });
      }
      
      // Current month days
      for (let i = 1; i <= lastDayOfMonth.getDate(); i++) {
        const date = new Date(this.bookingsYear, this.bookingsMonth, i);
        days.push({ date, isCurrentMonth: true });
      }
      
      // Days from next month
      const nextMonthDays = 6 - lastDayOfMonth.getDay();
      for (let i = 1; i <= nextMonthDays; i++) {
        const date = new Date(lastDayOfMonth);
        date.setDate(date.getDate() + i);
        days.push({ date, isCurrentMonth: false });
      }
      
      return days;
    },
    allBookings() {
      return this.rooms.flatMap(room => 
        room.bookings.map(booking => ({
          ...booking,
          roomId: room.id,
          roomName: `${room.name} (${room.type})`
        }))
      );
    }
  },
  methods: {
    // Date Helpers
    getWeekStartDate(date) {
      const day = date.getDay();
      const diff = date.getDate() - day + (day === 0 ? -6 : 1);
      return new Date(date.setDate(diff));
    },
    getWeekEndDate() {
      const endDate = new Date(this.currentWeekStart);
      endDate.setDate(endDate.getDate() + 6);
      return endDate;
    },
    formatDate(date) {
      if (!(date instanceof Date)) date = new Date(date);
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    },

    // Calendar Navigation
    previousWeek() {
      const newDate = new Date(this.currentWeekStart);
      newDate.setDate(newDate.getDate() - 7);
      this.currentWeekStart = newDate;
      this.updateMonthYear();
    },
    nextWeek() {
      const newDate = new Date(this.currentWeekStart);
      newDate.setDate(newDate.getDate() + 7);
      this.currentWeekStart = newDate;
      this.updateMonthYear();
    },
    updateMonthYear() {
      this.selectedMonth = this.currentWeekStart.getMonth();
      this.selectedYear = this.currentWeekStart.getFullYear();
    },
    previousMonth() {
      if (this.bookingsMonth === 0) {
        this.bookingsMonth = 11;
        this.bookingsYear--;
      } else {
        this.bookingsMonth--;
      }
    },
    nextMonth() {
      if (this.bookingsMonth === 11) {
        this.bookingsMonth = 0;
        this.bookingsYear++;
      } else {
        this.bookingsMonth++;
      }
    },

    // Room Status Helpers
    getRoomStatus(room, date) {
      if (room.blockedDates.includes(date)) return 'Blocked';
      
      const isBooked = room.bookings.some(booking => 
        date >= booking.startDate && date <= booking.endDate
      );
      if (isBooked) {
        const booking = room.bookings.find(b => date >= b.startDate && date <= b.endDate);
        return `Booked - ${booking.guest}`;
      }
      
      return 'Available';
    },
    getRoomPrice(room, date) {
      return room.dynamicPricing[date] || room.basePrice;
    },
    getStatusClass(status) {
      if (status === 'Available') return 'available';
      if (status.startsWith('Booked')) return 'booked';
      if (status === 'Blocked') return 'blocked';
      return '';
    },
    getBookingsForDay(date) {
      const formattedDate = this.formatDate(date);
      return this.allBookings.filter(booking => 
        formattedDate >= booking.startDate && formattedDate <= booking.endDate
      );
    },

    // Selection Helpers
    selectRoomDate(room, date) {
        this.store.setSelectedRoom(room.id)
        this.store.setSelectedDate(date)
        this.loadRoomData()
        },

    loadRoomData() {
      if (!this.selectedRoomId || !this.selectedDate) return;
      const room = this.selectedRoom;
      this.roomPrice = this.getRoomPrice(room, this.selectedDate);
    },
    editPrice(room, date) {
        this.store.setSelectedRoom(room.id)
        this.store.setSelectedDate(date)
        this.store.setRoomPrice(this.getRoomPrice(room, date))

        this.$nextTick(() => {
            document.querySelector('input[type="number"]').focus()
        })
        },


    // Management Actions
    // updatePrice() {
    //   if (!this.selectedRoomId || !this.selectedDate) return;
      
    //   const room = this.selectedRoom;
    //   const price = Number(this.roomPrice);
      
    //   if (price > 0) {
    //     this.$set(room.dynamicPricing, this.selectedDate, price);
    //   } else {
    //     if (room.dynamicPricing[this.selectedDate]) {
    //       this.$delete(room.dynamicPricing, this.selectedDate);
    //     }
    //   }
    // },

    blockRoom() {
      if (!this.selectedRoomId || !this.selectedDate) return;
      
      const room = this.selectedRoom;
      const date = this.selectedDate;
      
      if (!room.blockedDates.includes(date)) {
        room.blockedDates.push(date);
      }
    },
    openRoom() {
      if (!this.selectedRoomId || !this.selectedDate) return;
      
      const room = this.selectedRoom;
      const date = this.selectedDate;
      const index = room.blockedDates.indexOf(date);
      
      if (index > -1) {
        room.blockedDates.splice(index, 1);
      }
    },
    scrollToToday() {
  const today = new Date();
  this.currentWeekStart = this.getWeekStartDate(today); // ⬅️ reset week to current
  this.updateMonthYear(); // ⬅️ update display headers

  this.$nextTick(() => {
    const todaySelector = `[data-date="${this.formatDate(today)}"]`;
    const todayElement = document.querySelector(todaySelector);

    if (todayElement) {
      todayElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });
}

  },
 
  setup() {
   
    const store = useRoomStore()

    const roomPrice = computed({
      get: () => store.roomPrice,
      set: val => store.setRoomPrice(val)
    })

    const selectedRoomId = computed({
      get: () => store.selectedRoomId,
      set: val => store.setSelectedRoom(val)
    })

    const selectedDate = computed({
      get: () => store.selectedDate,
      set: val => store.setSelectedDate(val)
    })

    return {
      store,
      roomPrice,
      selectedRoomId,
      selectedDate
    }
  },

  mounted() {
    this.loadRoomData();
    this.store.setRooms(this.rooms)
  },
}
</script>
<style scoped>
/* Base Styles */
.admin-dashboard {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  max-width: auto;
  margin: 0 auto;
  padding: 0;
  color: #333;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  width: auto;
}

.dashboard-header {
  padding-bottom: 15px;
  border-bottom: 1px solid #e0e0e0;
  margin-bottom: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 15px;
}

.dashboard-header h1 {
  font-size: 24px;
  margin-bottom: 5px;
  color: #2c3e50;
}

.total-rooms {
  color: #7f8c8d;
  font-size: 14px;
}

/* Calendar Controls */
.calendar-control-section {
  margin-bottom: 20px;
  background-color: white;
  /* padding: 15px; */
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.calendar-header {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 20px;
  margin-bottom: 10px;
}

.calendar-nav {
  background: none;
  border: 1px solid #ddd;
  padding: 5px 15px;
  font-size: 16px;
  cursor: pointer;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.calendar-nav:hover {
  background-color: #f0f0f0;
}

.date-range {
  text-align: center;
  color: #666;
  font-weight: 500;
}

/* Management Controls */
.management-controls {
  background: #f5f5f5;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 20px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.control-panel h3 {
  margin-top: 0;
  margin-bottom: 20px;
  color: #333;
  font-size: 18px;
  padding-bottom: 10px;
  border-bottom: 1px solid #ddd;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #444;
}

.form-group select,
.form-group input[type="date"],
.form-group input[type="number"] {
  padding: 10px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  width: 100%;
  max-width: 300px;
  font-size: 14px;
  background-color: white;
}

.button-group {
  display: flex;
  gap: 10px;
  margin-top: 10px;
}

button {
  padding: 10px 15px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s;
}

button:hover:not(:disabled) {
  opacity: 0.9;
  transform: translateY(-1px);
}

button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-block {
  background-color: #ef5350;
  color: white;
}

.btn-open {
  background-color: #66bb6a;
  color: white;
}

.btn-show-bookings {
  padding: 10px 20px;
  background-color: #4a6fa5;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
}

.btn-show-bookings:hover {
  background-color: #3a5a8a;
}

.btn-edit-price {
  margin-top: 8px;
  padding: 5px 8px;
  font-size: 12px;
  background-color: #ffd54f;
  border: none;
  border-radius: 3px;
  cursor: pointer;
  width: 100%;
}

.btn-edit-price:hover {
  background-color: #ffc107;
}

/* Calendar Table */
/* .calendar-section {
  background-color: white;
  border-radius: 8px;
  padding: 15px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  overflow-x: auto;
}

.booking-table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
  min-width: 800px;
}

.booking-table th, 
.booking-table td {
  border: 1px solid #e0e0e0;
  text-align: center;
  vertical-align: middle;
}

.room-header, 
.day-header, 
.date-header {
  background-color: #f5f7fa;
  font-weight: 600;
  color: #2c3e50;
}

.day-header {
  min-width: 100px;
}

.room-info {
  text-align: left;
  min-width: 180px;
  background-color: #f5f7fa;
}

.room-name {
  font-weight: bold;
  color: #2c3e50;
  margin-bottom: 4px;
}

.room-type {
  font-size: 12px;
  color: #7f8c8d;
}

.status-container {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 5px;
}

.status-text {
  margin-bottom: 8px;
  font-size: 12px;
  line-height: 1.3;
}

.booked-text {
  color: #d32f2f;
  font-weight: 500;
}

.room-price {
  font-weight: bold;
  font-size: 14px;
  color: #2e7d32;
} */

/* Status Classes */
.available {
  background-color: #e8f5e9;
  cursor: pointer;
  transition: background-color 0.2s;
}

.available:hover {
  background-color: #c8e6c9;
}

.booked {
  background-color: #ffebee;
  cursor: not-allowed;
}

.blocked {
  background-color: #eceff1;
  cursor: pointer;
}

.blocked:hover {
  background-color: #cfd8dc;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  border-radius: 8px;
  width: 90%;
  max-width: 1000px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid #eee;
  position: sticky;
  top: 0;
  background-color: white;
  z-index: 10;
}

.modal-header h2 {
  margin: 0;
  color: #2c3e50;
}

.close-modal {
  background: none;
  border: none;
  font-size: 28px;
  cursor: pointer;
  color: #7f8c8d;
  padding: 0 10px;
}

.close-modal:hover {
  color: #2c3e50;
}

.modal-body {
  padding: 20px;
}

/* Bookings Calendar */
.bookings-calendar {
  width: 100%;
}

.calendar-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 8px;
}

.day-header {
  text-align: center;
  font-weight: bold;
  padding: 12px 8px;
  background-color: #f5f7fa;
  color: #2c3e50;
}

.calendar-day {
  min-height: 100px;
  border: 1px solid #e0e0e0;
  padding: 8px;
  background-color: white;
  position: relative;
}

.calendar-day.other-month {
  background-color: #f9f9f9;
  color: #aaa;
}

.day-number {
  font-weight: bold;
  margin-bottom: 8px;
  color: inherit;
}

.booking-event {
  background-color: #e3f2fd;
  border-radius: 4px;
  padding: 4px 6px;
  font-size: 12px;
  margin-bottom: 4px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  color: #1565c0;
  border-left: 3px solid #1976d2;
}

/* Responsive Design */
@media (max-width: 992px) {
  .dashboard-header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .form-group select,
  .form-group input[type="date"],
  .form-group input[type="number"] {
    max-width: 100%;
  }
}

@media (max-width: 768px) {
  .modal-content {
    width: 95%;
    padding: 10px;
  }
  
  .calendar-day {
    min-height: 80px;
    padding: 4px;
  }
  
  .booking-event {
    font-size: 10px;
    padding: 2px 4px;
  }
  
  .button-group {
    flex-direction: column;
  }
  
  .button-group button {
    width: 100%;
  }
}

@media (max-width: 576px) {
  .admin-dashboard {
    padding: 10px;
  }
  
  .calendar-nav {
    padding: 5px 10px;
  }
  
  .calendar-day {
    min-height: 60px;
  }
  
  .day-number {
    font-size: 12px;
  }
  
  .booking-event {
    display: none;
  }
}

</style>