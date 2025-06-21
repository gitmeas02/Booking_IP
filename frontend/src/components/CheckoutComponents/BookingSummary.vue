<template>
  <div class="border-gray-300 border rounded-lg min-w-[300px]">
    <div
      class="bg-gradient-to-r from-blue-800 to-blue-600 text-white p-6 rounded-t-lg shadow-md"
    >
      <h3 class="text-2xl font-extrabold tracking-wide">Booking Summary</h3>
    </div>
    <div class="p-6">
      <div class="space-y-4">
        <div>
          <h3 class="font-bold text-2xl">{{ hotelName }}</h3>
          <p class="text-muted-foreground">{{ location }}</p>
          <p class="text-sm mt-1">{{ near }}</p>
        </div>

        <div class="border-t pt-4">
          <div class="flex justify-between py-1">
            <span>Check-in</span>
            <span class="font-medium">{{ formatDate(checkin) }}</span>
          </div>
          <div class="flex justify-between py-1">
            <span>Check-out</span>
            <span class="font-medium">{{ formatDate(checkout) }}</span>
          </div>
          <div class="flex justify-between py-1">
            <span>Nights</span>
            <span class="font-medium">{{ nights }}</span>
          </div>
          <div class="flex justify-between py-1">
            <span>Room Type</span>
            <span class="font-medium">{{ roomType }}</span>
          </div>
          <div class="flex justify-between py-1">
            <span>Guests</span>
            <span class="font-medium"
              ><span>{{ guest }}</span> Adults</span>
          </div>
        </div>

        <div class="border-t pt-4">
          
          <div class="flex justify-between py-1">
            <span>Room Rate</span>
            <span
              >${{ roomPrice }} × {{ quantity }} rooms ×
              {{ nights }} nights</span
            >
          </div>
          <div class="flex justify-between py-1">
            <span>Subtotal</span>
            <span>${{ roomRate }}</span>
          </div>
          <div class="flex justify-between py-1">
            <span>Taxes & Fees</span>
            <span>${{ taxes }}</span>
          </div>
        </div>

        <div class="border-t pt-4">
          <div class="flex justify-between font-bold text-lg">
            <span>Total</span>
            <span>${{ total }}</span>
          </div>
          <p class="text-sm text-muted-foreground mt-2">
            Includes all taxes and fees
          </p>
        </div>
      </div>
      <div class="text-sm space-y-2">
        <p class="font-medium">Cancellation Policy</p>
        <p>
          Free cancellation until
          <span>{{ formatDate(cancellation) }}</span> After that, the first
          night will be charged.
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from "vue";

export default {
  props: {
    hotelName: String,
    location: String,
    near: String,
    checkin: Date,
    checkout: Date,
    roomType: String,
    guest: Number,
    nights: Number,
    roomRate: {
      type: Number,
      default: 0.0,
    },
    roomPrice: {
      type: Number,
      default: 0.0,
    },
    quantity: {
    type: Number,
    default: 1,
  },
    taxes: Number,
    cancellation: Date,
  },
  setup(props) {
    const formatDate = (date) =>
      date
        ? new Date(date).toLocaleDateString("en-US", {
            month: "long",
            day: "numeric",
            year: "numeric",
          })
        : "";

    const total = computed(() => props.roomRate + props.taxes);

    return {
      formatDate,
      total,
    };
  },
};
</script>
