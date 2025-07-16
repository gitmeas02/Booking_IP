<template>
  <div class="flex flex-col h-screen w-64 p-4 box-border border-r border-black overflow-hidden">
    <!-- Top content -->
    <div class="flex flex-col gap-2 flex-grow">
      <div id="agbalumo" class="flex items-center justify-center text-2xl font-bold mb-4 cursor-default">
        Admin Dashboard
      </div>
      <div id="agbalumo" class="mb-2">
        Analysis Dashboard
      </div>

      <!-- Booking -->
      <RouterLink to="/ownerdashboard" :class="[
        'flex items-center justify-between cursor-pointer w-full text-left text-xl hover:bg-gray-100',
        isBooking ? 'bg-[#572222] text-white' : ''
      ]">
        <div :class="['flex items-center gap-2', isBooking ? 'text-white' : 'text-gray-500']">
          <Book />
          <span :class="isBooking ? 'text-white' : 'text-gray-800'">Booking</span>
        </div>
        <ChevronRight :class="isBooking ? 'text-white' : 'text-gray-800'" />
      </RouterLink>

      <!-- Room -->
      <RouterLink to="/upload-property" :class="['flex items-center justify-between cursor-pointer w-full text-left text-xl hover:bg-gray-100',
        isRoom ? 'bg-[#572222] text-white' : ''
      ]">
        <div :class="['flex items-center gap-2', isRoom ? 'text-white' : 'text-gray-500']">
          <House />
          <span :class="isRoom ? 'text-white' : 'text-gray-800'">Room</span>
        </div>
        <ChevronRight :class="isRoom ? 'text-white' : 'text-gray-800'" />
      </RouterLink>

      <!-- Room Management -->
      <RouterLink to="/room-management" :class="['flex items-center justify-between cursor-pointer w-full text-left text-xl hover:bg-gray-100',
        isRoomManagement ? 'bg-[#572222] text-white' : ''
      ]">
        <div :class="['flex items-center gap-2', isRoomManagement ? 'text-white' : 'text-gray-500']">
          <Settings />
          <span :class="isRoomManagement ? 'text-white' : 'text-gray-800'">Manage Rooms</span>
        </div>
        <ChevronRight :class="isRoomManagement ? 'text-white' : 'text-gray-800'" />
      </RouterLink>

      <!-- Message -->
      <RouterLink to="/chat" :class="['flex items-center justify-between cursor-pointer w-full text-left text-xl hover:bg-gray-100',
        isChat ? 'bg-[#572222] text-white' : ''
      ]">
        <div :class="['flex items-center gap-2', isChat ? 'text-white' : 'text-gray-500']">
          <MessageSquare />
          <span :class="isChat ? 'text-white' : 'text-gray-800'">Message</span>
        </div>
        <ChevronRight :class="isChat ? 'text-white' : 'text-gray-800'" />
      </RouterLink>

      <!-- Calendar -->
      <RouterLink to="/calendar" :class="['flex items-center justify-between cursor-pointer w-full text-left text-xl hover:bg-gray-100',
        isCalendar ? 'bg-[#572222] text-white' : ''
      ]">
        <div :class="['flex items-center gap-2', isCalendar ? 'text-white' : 'text-gray-500']">
          <CalendarDays />
          <span :class="isCalendar ? 'text-white' : 'text-gray-800'">Calendar</span>
        </div>
        <ChevronRight :class="isCalendar ? 'text-white' : 'text-gray-800'" />
      </RouterLink>

      <!-- Payment -->
      <RouterLink to="/payment" :class="['flex items-center justify-between cursor-pointer w-full text-left text-xl hover:bg-gray-100',
        isPayment ? 'bg-[#572222] text-white' : ''
      ]">
        <div :class="['flex items-center gap-2', isPayment ? 'text-white' : 'text-gray-500']">
          <CircleDollarSign />
          <span :class="isPayment ? 'text-white' : 'text-gray-800'">Payment</span>
        </div>
        <ChevronRight :class="isPayment ? 'text-white' : 'text-gray-800'" />
      </RouterLink>
    </div>

    <!-- Bottom links -->
    <div class="flex flex-col gap-2 pt-4">
      <!-- Support -->
      <RouterLink to="/support" :class="['flex items-center justify-between cursor-pointer w-full text-left text-xl hover:bg-gray-100',
        isSupport ? 'bg-[#572222] text-white' : ''
      ]">
        <div :class="['flex items-center gap-2', isSupport ? 'text-white' : 'text-gray-500']">
          <Headset />
          <span :class="isSupport ? 'text-white' : 'text-gray-800'">Support</span>
        </div>
        <ChevronRight :class="isSupport ? 'text-white' : 'text-gray-800'" />
      </RouterLink>

      <!-- Settings -->
      <RouterLink to="/settings" :class="['flex items-center justify-between cursor-pointer w-full text-left text-xl hover:bg-gray-100',
        isSettings ? 'bg-[#572222] text-white' : ''
      ]">
        <div :class="['flex items-center gap-2', isSettings ? 'text-white' : 'text-gray-500']">
          <Settings />
          <span :class="isSettings ? 'text-white' : 'text-gray-800'">Settings</span>
        </div>
        <ChevronRight :class="isSettings ? 'text-white' : 'text-gray-800'" />
      </RouterLink>
    </div>
  </div>
</template>

<script setup>
import { Book, CalendarDays, ChevronRight, CircleDollarSign, Headset, House, MessageSquare, Settings } from 'lucide-vue-next';
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const isBooking = computed(() => route.path === '/');
const isRoom = computed(() => route.path === '/upload-property');
const isRoomManagement = computed(() => route.path === '/room-management');
const isChat = computed(() => route.path === '/chat');
const isCalendar = computed(() => route.path === '/calendar');
const isPayment = computed(() => route.path === '/payment');
const isSupport = computed(() => route.path === '/support');
const isSettings = computed(() => route.path === '/settings');
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Agbalumo&display=swap');

#agbalumo {
  font-family: "Agbalumo", system-ui;
  font-weight: 400;
  font-style: normal;
}
/* Prevent sidebar from scrolling and always fit the screen */
.flex-col {
  overflow: hidden !important;
}
</style>