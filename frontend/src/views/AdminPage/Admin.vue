
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
          @click="cancelSelectionWithUnblock"
          class="px-4 py-1 bg-gray-500 hover:bg-gray-600 text-white rounded transition-colors"
        >
          Cancel Selection
        </button>
        <button 
          v-if="selectionMode && selectedDates.length > 0"
          @click="confirmSelectionWithUnblock"
          :class="selectedRoom._unblockMode ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600'"
          class="px-4 py-1 text-white rounded transition-colors"
        >
          <span v-if="selectedRoom._unblockMode">Unblock Selected Dates ({{ selectedDates.length }})</span>
          <span v-else>Block Selected Dates ({{ selectedDates.length }})</span>
        </button>
        <button 
          class="px-4 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded transition-colors" 
          @click="$router.push('/update-property')"
        >
          Upload Room
        </button>
      </div>
    </div>

    <!-- Selection Mode Info -->
    <div v-if="selectionMode" 
         class="border px-4 py-3 rounded-lg"
         :class="selectedRoom._unblockMode ? 'bg-orange-100 border-orange-300 text-orange-800' : 'bg-blue-100 border-blue-300 text-blue-800'">
      <div class="flex items-center justify-between">
        <div>
          <strong v-if="selectedRoom._unblockMode">Multi-Date Unblock Mode</strong>
          <strong v-else>Multi-Date Selection Mode</strong>
          - {{ selectedRoom?.hotel }} - {{ selectedRoom?.name }}
          <div class="text-sm mt-1">
            <span v-if="selectedRoom._unblockMode">
              Click on blocked dates to select/deselect them for unblocking. Selected: {{ selectedDates.length }} dates
            </span>
            <span v-else>
              Click on dates to select/deselect them for blocking. Selected: {{ selectedDates.length }} dates
            </span>
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
                <div class="flex gap-2 mt-1">
                  <button 
                    v-if="!selectionMode"
                    @click="startMultiSelection(room)"
                    class="px-2 py-1 text-xs bg-blue-500 hover:bg-blue-600 text-white rounded transition-colors"
                  >
                    Multi-Block
                  </button>
                  <button 
                    v-if="!selectionMode"
                    @click="startMultiUnblockSelection(room)"
                    class="px-2 py-1 text-xs bg-orange-500 hover:bg-orange-600 text-white rounded transition-colors"
                  >
                    Multi-Unblock
                  </button>
                </div>
              </div>
            </div>

            <!-- Day Cells -->
            <div
              v-for="day in monthDays"
              :key="`day-${room.id}-${day}`"
              class="h-24 border-r text-xs flex flex-col items-center justify-center cursor-pointer relative transition-all duration-200"
              :class="getDayStatusClassWithUnblock(room, day)"
              :title="getDayTooltipWithUnblock(room, day)"
              @click="onDayClickWithUnblock(room, day)"
              @mouseenter="onDayHover(room, day)"
            >
              <div class="font-semibold z-22">${{ getDayPrice(room, day) }}</div>
              <div class="text-xs opacity-75 z-22">{{ getDayStatus(room, day) }}</div>
              
              <!-- Selection indicator -->
              <div 
                v-if="isDateSelected(room, day)" 
                :class="selectedRoom._unblockMode ? 'bg-orange-200 bg-opacity-50 border-2 border-orange-400' : 'bg-purple-200 bg-opacity-50 border-2 border-purple-400'"
                class="absolute inset-0 rounded z-5"
              ></div>
              
              <!-- Hover indicator for range selection -->
              <div 
                v-if="isDateInHoverRange(room, day)" 
                :class="selectedRoom._unblockMode ? 'bg-orange-100 bg-opacity-30 border border-orange-300' : 'bg-purple-100 bg-opacity-30 border border-purple-300'"
                class="absolute inset-0 rounded z-4"
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
                  @click.stop="onBlockedDateClickWithUnblock(room, blockedDate, $event)"
                  @contextmenu.prevent="onBlockedDateClickWithUnblock(room, blockedDate, $event)"
                  title="Left click to edit, Right click to unblock"
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
        <div class="w-4 h-4 bg-orange-200 border border-orange-400 rounded"></div>
        <span>Selected for unblocking</span>
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

    <!-- Multi-Date Block/Unblock Confirmation Modal -->
    <div v-if="showMultiBlockModal" class="fixed inset-0  bg-transparent bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">
          <span v-if="selectedRoom._unblockMode">Confirm Unblock Dates</span>
          <span v-else>Confirm Block Dates</span>
        </h3>
        <p class="text-gray-600 mb-4">
          <span v-if="selectedRoom._unblockMode">
            Unblock {{ selectedDates.length }} dates for <strong>{{ selectedRoom?.hotel }} - {{ selectedRoom?.name }}</strong>?
          </span>
          <span v-else>
            Block {{ selectedDates.length }} dates for <strong>{{ selectedRoom?.hotel }} - {{ selectedRoom?.name }}</strong>?
          </span>
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
            @click="selectedRoom._unblockMode ? confirmMultiUnblock() : confirmMultiBlock()"
            :class="selectedRoom._unblockMode ? 'bg-orange-500 hover:bg-orange-600' : 'bg-red-500 hover:bg-red-600'"
            class="px-4 py-2 text-white rounded transition-colors"
          >
            <span v-if="selectedRoom._unblockMode">Unblock Dates</span>
            <span v-else>Block Dates</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Single Date Unblock Confirmation Modal -->
    <div v-if="showUnblockModal" class="fixed inset-0  bg-transparent bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Confirm Unblock</h3>
        <p class="text-gray-600 mb-4">
          Unblock dates from <strong>{{ selectedBlockToRemove.startDate }}</strong> to <strong>{{ selectedBlockToRemove.endDate }}</strong> 
          for <strong>{{ selectedRoom?.hotel }} - {{ selectedRoom?.name }}</strong>?
        </p>
        <div class="flex gap-3 justify-end">
          <button 
            @click="showUnblockModal = false"
            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors"
          >
            Cancel
          </button>
          <button 
            @click="confirmSingleUnblock()"
            class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded transition-colors"
          >
            Unblock
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCalendarManager } from '@/stores/useManagerCalender';
import ControllDateRoom from '@/components/AdminComponents/ControllDateRoom.vue';

// Initialize the calendar manager composable
const {
  months,
  selectedMonth,
  selectedYear,
  rooms,
  loading,
  error,
  showPopup,
  selectedRoom,
  selectedStartDate,
  selectedEndDate,
  selectedPrice,
  selectedStatus,
  showUnblockModal,
  selectedBlockToRemove,
  selectionMode,
  selectedDates,
  hoverDate,
  selectionStartDate,
  selectionEndDate,
  showMultiBlockModal,
  shiftPressed,
  monthDays,
  firstDay,
  lastDay,
  availableDaysCount,
  bookedDaysCount,
  formatDate,
  parseDate,
  formatDisplayDate,
  formatWeekday,
  daysBetween,
  getNextDay,
  handleImageError,
  previousMonth,
  nextMonth,
  goToToday,
  fetchRooms,
  isDateBooked,
  isDateBlocked,
  getDayPrice,
  getDayStatus,
  getDayStatusClassWithUnblock,
  startMultiSelection,
  startMultiUnblockSelection,
  cancelSelectionWithUnblock,
  isDateSelected,
  isDateInHoverRange,
  toggleDateSelection,
  toggleDateSelectionUnblock,
  selectDateRange,
  confirmSelectionWithUnblock,
  confirmMultiBlock,
  confirmMultiUnblock,
  getBookingOffset,
  getBlockedOffset,
  getDayTooltipWithUnblock,
  onDayClickWithUnblock,
  onDayHover,
  onBlockedDateClickWithUnblock,
  closePopup,
  handleUpdate,
  unblockSingleDate,
  confirmSingleUnblock,
  handleKeyDown,
  handleKeyUp,
} = useCalendarManager();
</script>

<style scoped>
.booking-bar {
  transition: all 0.2s ease;
}
.booking-bar:hover {
  filter: brightness(110%);
  transform: scale(1.02);
}
.booked-pulse {
  animation: pulse 2s infinite;
}
@keyframes pulse {
  0% { opacity: 1; }
  50% { opacity: 0.7; }
  100% { opacity: 1; }
}
</style>