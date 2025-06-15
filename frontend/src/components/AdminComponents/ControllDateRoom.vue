<template>
  <div class="absolute top-[50%] right-0 -translate-y-[40%] bg-white p-4 rounded shadow-lg w-[350px] max-w-full text-sm font-serif border z-22">
    <form
      class=""
      @submit.prevent="handleUpdate"
    >
      <!-- Date Range -->
      <div class="mb-4">
        <h2 class="text-[15px] mb-1 font-semibold">Advance Select</h2>
        <div class="grid grid-cols-2 gap-2 border p-2">
          <div class="text-center">
            <p class="text-[12px]">start</p>
            <input
              type="date"
              v-model="localStartDate"
              class="font-medium border p-1 w-full text-center text-sm"
              :min="today"
            />
          </div>
          <div class="text-center">
            <p class="text-[12px]">end date</p>
            <input
              type="date"
              v-model="localEndDate"
              class="font-medium border p-1 w-full text-center text-sm"
              :min="minEndDate"
            />
          </div>
        </div>
      </div>

      <!-- Hotel Info -->
      <div class="flex items-center gap-2 mb-3">
        <img
          :src="roomImage"
          alt="Hotel"
          class="rounded w-16 h-12 object-cover"
        />
        <div>
          <p class="font-semibold text-[16px]">{{ hotelName }}</p>
          <p class="text-gray-400 text-[13px]">{{ roomDetail }}</p>
        </div>
      </div>

      <p class="text-center text-[13px] mb-1">{{ displayDate }}</p>

      <hr class="mb-2" />

      <!-- Status Select -->
      <div class="text-center text-[14px] mb-2">Open or Close Booking</div>
      <div class="flex flex-col mb-3 gap-2 px-1">
        <label class="flex items-center gap-2">
          <input type="radio" value="blocked" v-model="status" />
          Block
        </label>
        <label class="flex items-center gap-2">
          <input type="radio" value="available" v-model="status" />
          Available
        </label>
      </div>

      <hr class="mb-3" />

      <!-- Price -->
      <div class="mb-3">
        <label class="block mb-1">Price</label>
        <div class="flex">
          <span class="bg-gray-300 text-gray-800 px-3 py-1 border border-r-0">
            USD
          </span>
          <input
            type="number"
            v-model="price"
            class="w-full border px-2 py-1"
          />
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex justify-between items-center mb-3">
        <button
          type="button"
          @click="$emit('cancel')"
          class="px-4 py-1 border border-gray-400 text-gray-700 rounded"
        >
          Cancel
        </button>
        <button
          type="submit"
          class="px-4 py-1 bg-[#531c1c] text-white rounded"
        >
          Update
        </button>
      </div>

      <hr class="mb-2" />

      <!-- Note -->
      <div class="text-xs">
        <p class="font-semibold">(blocking/closing date)</p>
        <div class="border mt-1 p-2 text-[12px] bg-gray-100">
          The start date (blocking/closing date) <br />
          must be today or in the future
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  startDate: String,
  endDate: String,
  hotelName: String,
  roomDetail: String,
  roomImage: {
    type: String,
    default: 'https://via.placeholder.com/60',
  },
  displayDate: String,
  initialPrice: {
    type: Number,
    default: 0,
  },
  initialStatus: {
    type: String,
    default: 'blocked',
  },
  isBooked: Boolean,
});

const emit = defineEmits(['cancel', 'update']);

const status = ref(props.initialStatus);
const price = ref(props.initialPrice);
const localStartDate = ref(props.startDate);
const localEndDate = ref(props.endDate);

// Ensure end date is at least one day after start date
const minEndDate = computed(() => {
  const start = new Date(localStartDate.value);
  start.setDate(start.getDate() + 1);
  return start.toISOString().split('T')[0];
});

// Ensure start date is today or later
const today = new Date().toISOString().split('T')[0];

function handleUpdate() {
  // Validate that endDate is after startDate
  if (new Date(localEndDate.value) <= new Date(localStartDate.value)) {
    alert('End date must be after start date');
    return;
  }
  emit('update', {
    status: status.value,
    price: price.value,
    startDate: localStartDate.value,
    endDate: localEndDate.value,
  });
}
</script>
