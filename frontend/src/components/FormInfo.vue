<script setup>
import { ref } from "vue";

// Define reactive variables for form inputs
const firstName = ref("");
const lastName = ref("");
const email = ref("");
const phoneNumber = ref("");
const specialRequests = ref("");
const selectedPayment = ref("");
const cardNumber = ref("");
const expiryDate = ref("");
const cvv = ref("");
const agreeToTerms = ref(false);
import { QrCode } from 'lucide-vue-next';
import { CreditCard } from 'lucide-vue-next';
// import { Landmark } from 'lucide-vue-next';
import { CircleDollarSign } from 'lucide-vue-next';

</script>

<template>
<div class=" p-6 border border-gray-300 rounded-lg bg-white min-w-[300px]">
  <h2 class="text-2xl font-semibold mb-6">Guest Information</h2>

  <div class="space-y-6 ">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- First Name -->
      <div>
        <label class="block text-sm font-semibold mb-2">First Name</label>
        <input type="text" placeholder="John" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <!-- Last Name -->
      <div>
        <label class="block text-sm font-semibold mb-2">Last Name</label>
        <input type="text" placeholder="Doe" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-semibold mb-2">Email</label>
        <input type="email" placeholder="john.doe@example.com" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <!-- Phone Number -->
      <div>
        <label class="block text-sm font-semibold mb-2">Phone Number</label>
        <input type="tel" placeholder="+855 123 456 789" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>
    </div>

    <!-- Special Requests -->
    <div>
      <label class="block text-sm font-semibold mb-2">Special Requests</label>
      <textarea placeholder="Any special requests?" rows="3" class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
    </div>
   <div class="flex flex-row items-start space-x-3 justify-start space-y-0 mt-6">
    <input type="radio" name="" id="" style="accent-color: oklch(28.2% 0.091 267.935); color: white; width: 20px; height: 20px;">
    <p class="space-y-1 leading-none">
      Travel with pets</p>
   </div>

    <div class="mt-8">
    <h3 class="text-lg font-medium mb-4">Payment Method</h3>

    <!-- Tabs -->
    <div class="mb-6 grid grid-cols-3 bg-gray-400 rounded-1xl text-1xl font-bold text-blue-900 min-w-[400px]">
      <button
        class="flex items-center gap-2 p-2 justify-center"
        :class="{ 'bg-gray-200': selectedPayment === 'card' }"
        @click="selectedPayment = 'card'"
      >
        <CreditCard class="h-6 w-6" /> Card
      </button>
      <button
        class="flex items-center gap-2 p-2 justify-center text-1xl font-bold "
        :class="{ 'bg-gray-200': selectedPayment === 'cash' }"
        @click="selectedPayment = 'cash'"
      >
        <CircleDollarSign class="h-6 w-6" /> Cash
      </button>
      <button
        class="flex items-center gap-2 p-2 justify-center text-1xl font-bold "
        :class="{ 'bg-gray-200': selectedPayment === 'qr' }"
        @click="selectedPayment = 'qr'"
      >
        <QrCode class="h-6 w-6" /> QR Code
      </button>
    </div>

    <!-- Card Content -->
    <div v-if="selectedPayment === 'card'" class="space-y-4">
      <div>
        <label class="block font-medium mb-1">Card Number</label>
        <input type="text" class="w-full border p-2 rounded-md" placeholder="4242 4242 4242 4242" />
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block font-medium mb-1">Expiry Date</label>
          <input type="text" class="w-full border p-2 rounded-md" placeholder="MM/YY" />
        </div>
        <div>
          <label class="block font-medium mb-1">CVV</label>
          <input type="text" class="w-full border p-2 rounded-md" placeholder="123" />
        </div>
      </div>
    </div>

    <!-- Bank Content -->
    <div v-if="selectedPayment === 'cash'" class="space-y-4">
      <p class="text-gray-500">Make a direct bank transfer to:</p>
      <div class="p-4 bg-gray-100 rounded-md">
        <p class="font-bold text-lg">
          Makara will pay at property Khun Hotel
        </p>
        <!-- <p>Account Name: Khun Hoth Hotel</p>
        <p>Account Number: 123456789</p>
        <p>SWIFT Code: KHHOTCAM</p> -->
      </div>
    </div>

    <!-- QR Content -->
    <div v-if="selectedPayment === 'qr'" class="flex flex-col items-center space-y-4">
      <div class="w-48 h-48 bg-white p-2 border">
        <div
          class="w-full h-full bg-contain bg-no-repeat bg-center"
          style="background-image: url('https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=KhunHothHotelPayment')"
        ></div>
      </div>
      <p class="text-center text-gray-500">Scan the QR code with your mobile banking app to pay.</p>
    </div>
  </div>
   <!-- Terms and Conditions -->
   <div class="flex flex-row items-start space-x-3 justify-center space-y-0 mt-6">
      <input 
      type="checkbox" 
      v-model="agreeToTerms"
      class="checkbox"
      style="accent-color: oklch(28.2% 0.091 267.935); color: white; width: 20px; height: 20px;" 
      />
      <div class="space-y-1 leading-none">
      I agree to the hotel's terms and conditions, including the cancellation
      policy
      </div>
    </div>
   <div class="mr-16 ml-16 mt-8">
    <button type="submit" class="w-full btn bg-blue-950 p-2 text-white ">Complete Booking</button>
   </div>
  </div>
</div>

</template>
