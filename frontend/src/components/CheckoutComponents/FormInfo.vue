<script setup>
import {
  CircleDollarSign,
  CreditCard,
  QrCode,
  Download,
} from "lucide-vue-next";
import { ref, onMounted, onUnmounted, computed, watch } from "vue";
import EnhancedQRCode from "./EnhancedQRCode.vue";

// Define reactive variables for form inputs
const firstName = ref("");
const lastName = ref("");
const email = ref("");
const phoneNumber = ref("");
const specialRequests = ref("");
const selectedPayment = ref("card");
const cardNumber = ref("");
const expiryDate = ref("");
const cvv = ref("");
const agreeToTerms = ref(false);
const pets = ref(false);

// QR Code related variables
const currentQRData = ref(null);
const qrImageUrl = ref("");
const isGeneratingQR = ref(false);
const countdown = ref(120); // 2 minutes in seconds
const countdownInterval = ref(null);
const qrRefreshInterval = ref(null);
const transactionId = ref("");

// Computed properties
const countdownDisplay = computed(() => {
  const minutes = Math.floor(countdown.value / 60);
  const seconds = countdown.value % 60;
  return `${minutes}:${seconds.toString().padStart(2, "0")}`;
});

const merchantName = "Khun Hotel";
const currency = "KHR";
const amount = ref(200); // Default amount, you can make this dynamic

// QR Code generation function
const generateQRCode = async () => {
  if (isGeneratingQR.value) return;

  isGeneratingQR.value = true;

  try {
    const response = await fetch("http://localhost:8100/api/payments", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        amount: amount.value,
        booking_reference: `HOTEL_${Date.now()}`,
        merchant_name: merchantName,
      }),
    });

    const data = await response.json();

    if (data.success) {
      currentQRData.value = data;
      transactionId.value = data.transaction_id;

      // Generate QR image URL with enhanced visuals
      const qrData = encodeURIComponent(data.qr_string);
      qrImageUrl.value = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${qrData}&format=png&margin=10`;

      // Reset countdown
      countdown.value = 120;
      startCountdown();
    } else {
      console.error("Failed to generate QR code:", data.message);
    }
  } catch (error) {
    console.error("Error generating QR code:", error);
  } finally {
    isGeneratingQR.value = false;
  }
};

// Countdown timer
const startCountdown = () => {
  if (countdownInterval.value) {
    clearInterval(countdownInterval.value);
  }

  countdownInterval.value = setInterval(() => {
    countdown.value--;
    if (countdown.value <= 0) {
      generateQRCode(); // Auto-regenerate when countdown reaches 0
    }
  }, 1000);
};

// Auto-refresh QR code every 2 minutes
const startAutoRefresh = () => {
  if (qrRefreshInterval.value) {
    clearInterval(qrRefreshInterval.value);
  }

  qrRefreshInterval.value = setInterval(() => {
    generateQRCode();
  }, 120000); // 2 minutes
};

// Download QR code
const downloadQRCode = () => {
  if (!currentQRData.value) return;

  const qrData = encodeURIComponent(currentQRData.value.qr_string);
  const downloadUrl = `https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=${qrData}&format=png&margin=20`;

  const link = document.createElement("a");
  link.href = downloadUrl;
  link.download = `khqr-payment-${transactionId.value}.png`;
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

// Payment status checker
const checkPaymentStatus = async () => {
  if (!transactionId.value) return;

  try {
    const response = await fetch(
      `http://localhost:8100/api/payments/${transactionId.value}/status`,
      {
        method: "GET",
        headers: {
          Accept: "application/json",
        },
      }
    );

    const data = await response.json();

    if (data.success && data.status === "success") {
      // Payment completed - you can emit an event or update parent component
      console.log("Payment completed successfully!");
      // Stop auto-refresh when payment is completed
      if (qrRefreshInterval.value) {
        clearInterval(qrRefreshInterval.value);
      }
      if (countdownInterval.value) {
        clearInterval(countdownInterval.value);
      }
    }
  } catch (error) {
    console.error("Error checking payment status:", error);
  }
};

// Initialize QR generation when QR payment is selected
const handlePaymentMethodChange = () => {
  if (selectedPayment.value === "qr") {
    generateQRCode();
    startAutoRefresh();

    // Check payment status every 10 seconds
    const statusCheckInterval = setInterval(() => {
      checkPaymentStatus();
    }, 10000);

    // Store interval for cleanup
    onUnmounted(() => {
      clearInterval(statusCheckInterval);
    });
  } else {
    // Clean up intervals when switching away from QR
    if (qrRefreshInterval.value) {
      clearInterval(qrRefreshInterval.value);
    }
    if (countdownInterval.value) {
      clearInterval(countdownInterval.value);
    }
  }
};

// Watch for payment method changes
watch(selectedPayment, (newMethod, oldMethod) => {
  console.log(`Payment method changed from ${oldMethod} to ${newMethod}`);
  handlePaymentMethodChange();
});

// Cleanup on component unmount
onUnmounted(() => {
  if (qrRefreshInterval.value) {
    clearInterval(qrRefreshInterval.value);
  }
  if (countdownInterval.value) {
    clearInterval(countdownInterval.value);
  }
});

// Expose form data for parent component
defineExpose({
  firstName,
  lastName,
  email,
  phoneNumber,
  specialRequests,
  selectedPayment,
  cardNumber,
  expiryDate,
  cvv,
  agreeToTerms,
});
</script>

<template>
  <div class="p-6 border border-gray-300 rounded-lg bg-white min-w-[300px]">
    <h2 class="text-2xl font-semibold mb-6">Guest Information</h2>

    <div class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- First Name -->
        <div>
          <label class="block text-sm font-semibold mb-2">First Name</label>
          <input
            type="text"
            placeholder="John"
            v-model="firstName"
            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <!-- Last Name -->
        <div>
          <label class="block text-sm font-semibold mb-2">Last Name</label>
          <input
            type="text"
            placeholder="Doe"
            v-model="lastName"
            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-semibold mb-2">Email</label>
          <input
            type="email"
            placeholder="john.doe@example.com"
            v-model="email"
            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <!-- Phone Number -->
        <div>
          <label class="block text-sm font-semibold mb-2">Phone Number</label>
          <input
            type="tel"
            placeholder="+855 123 456 789"
            v-model="phoneNumber"
            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
      </div>

      <!-- Special Requests -->
      <div>
        <label class="block text-sm font-semibold mb-2">Special Requests</label>
        <textarea
          v-model="specialRequests"
          placeholder="Any special requests?"
          rows="3"
          class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
        ></textarea>
      </div>
      <div
        class="flex flex-row items-start space-x-3 justify-start space-y-0 mt-6"
      >
        <input
          type="radio"
          v-model="pets"
          name=""
          id=""
          style="
            accent-color: oklch(28.2% 0.091 267.935);
            color: white;
            width: 20px;
            height: 20px;
          "
        />
        <p class="space-y-1 leading-none">Travel with pets</p>
      </div>

      <div class="mt-8">
        <h3 class="text-lg font-medium mb-4">Payment Method</h3>

        <!-- Tabs -->
        <div
          class="mb-6 grid grid-cols-3 bg-gray-400 rounded-1xl text-1xl font-bold text-blue-900 min-w-[400px]"
        >
          <button
            class="flex items-center gap-2 p-2 justify-center"
            :class="{ 'bg-gray-200': selectedPayment === 'card' }"
            @click="
              selectedPayment = 'card';
              handlePaymentMethodChange();
            "
          >
            <CreditCard class="h-6 w-6" /> Card
          </button>
          <button
            class="flex items-center gap-2 p-2 justify-center text-1xl font-bold"
            :class="{ 'bg-gray-200': selectedPayment === 'cash' }"
            @click="
              selectedPayment = 'cash';
              handlePaymentMethodChange();
            "
          >
            <CircleDollarSign class="h-6 w-6" /> Cash
          </button>
          <button
            class="flex items-center gap-2 p-2 justify-center text-1xl font-bold"
            :class="{ 'bg-gray-200': selectedPayment === 'qr' }"
            @click="
              selectedPayment = 'qr';
              handlePaymentMethodChange();
            "
          >
            <QrCode class="h-6 w-6" /> QR Code
          </button>
        </div>

        <!-- Card Content -->
        <div v-if="selectedPayment === 'card'" class="space-y-4">
          <div>
            <label class="block font-medium mb-1">Card Number</label>
            <input
              type="text"
              v-model="cardNumber"
              class="w-full border p-2 rounded-md"
              placeholder="4242 4242 4242 4242"
            />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block font-medium mb-1">Expiry Date</label>
              <input
                type="text"
                v-model="expiryDate"
                class="w-full border p-2 rounded-md"
                placeholder="MM/YY"
              />
            </div>
            <div>
              <label class="block font-medium mb-1">CVV</label>
              <input
                type="text"
                v-model="cvv"
                class="w-full border p-2 rounded-md"
                placeholder="123"
              />
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
          </div>
        </div>

        <!-- Enhanced QR Content -->
        <div
          v-if="selectedPayment === 'qr'"
          class="flex flex-col items-center space-y-4"
        >
          <!-- Countdown Timer -->
          <div v-if="currentQRData" class="text-center">
            <div
              class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-semibold"
            >
              Next QR refresh in: {{ countdownDisplay }}
            </div>
          </div>

          <!-- QR Code Container -->
          <div
            class="relative bg-white p-6 border-2 border-gray-300 rounded-lg shadow-lg"
          >
            <div
              v-if="isGeneratingQR"
              class="w-48 h-48 flex items-center justify-center"
            >
              <div
                class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"
              ></div>
            </div>

            <div v-else-if="qrImageUrl" class="text-center">
              <!-- QR Code Image -->
              <img
                :src="qrImageUrl"
                alt="KHQR Payment Code"
                class="w-48 h-48 mx-auto border border-gray-200 rounded-lg"
              />

              <!-- Currency and Amount Display -->
              <div class="mt-3 p-2 bg-gray-50 rounded-lg">
                <div class="text-lg font-bold text-blue-900">
                  {{ amount.toLocaleString() }} {{ currency }}
                </div>
                <div class="text-sm text-gray-600">៛ Cambodian Riel</div>
              </div>
            </div>

            <div
              v-else
              class="w-48 h-48 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg"
            >
              <div class="text-center text-gray-500">
                <QrCode class="h-12 w-12 mx-auto mb-2" />
                <p>Click to generate QR code</p>
              </div>
            </div>
          </div>

          <!-- Merchant Name -->
          <div class="text-center">
            <h4 class="text-lg font-semibold text-gray-800">
              {{ merchantName }}
            </h4>
            <p class="text-sm text-gray-600">Pteas Khmer Hotel Booking</p>
          </div>

          <!-- Action Buttons -->
          <div class="flex gap-3">
            <button
              @click="generateQRCode"
              :disabled="isGeneratingQR"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 flex items-center gap-2"
            >
              <QrCode class="h-4 w-4" />
              {{ isGeneratingQR ? "Generating..." : "Generate New QR" }}
            </button>

            <button
              @click="downloadQRCode"
              :disabled="!currentQRData"
              class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 flex items-center gap-2"
            >
              <Download class="h-4 w-4" />
              Download QR
            </button>
          </div>

          <!-- Transaction Info -->
          <div
            v-if="currentQRData"
            class="text-center text-sm text-gray-600 bg-gray-50 p-3 rounded-lg"
          >
            <p><strong>Transaction ID:</strong> {{ transactionId }}</p>
            <p>
              <strong>Expires:</strong>
              {{ new Date(currentQRData.expires_at).toLocaleString() }}
            </p>
          </div>

          <!-- Instructions -->
          <div class="text-center text-sm text-gray-500 max-w-md">
            <p>
              Scan the QR code with your mobile banking app (ABA, Wing, etc.) to
              complete the payment.
            </p>
            <p class="mt-2 font-medium">
              The QR code will refresh automatically every 2 minutes.
            </p>
          </div>
        </div>
      </div>
      <!-- Terms and Conditions -->
      <div
        class="flex flex-row items-start space-x-3 justify-center space-y-0 mt-6"
      >
        <input
          type="checkbox"
          v-model="agreeToTerms"
          class="checkbox"
          style="
            accent-color: oklch(28.2% 0.091 267.935);
            color: white;
            width: 20px;
            height: 20px;
          "
        />
        <div class="space-y-1 leading-none">
          I agree to the hotel's terms and conditions, including the
          cancellation policy
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Add some animations for better UX */
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Pulse animation for countdown */
.bg-blue-100 {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%,
  100% {
    opacity: 1;
  }
  50% {
    opacity: 0.8;
  }
}
</style>
