<script setup>
import {
    CheckCircle,
    CircleDollarSign,
    CreditCard,
    Download,
    QrCode,
    X,
} from "lucide-vue-next";
import { computed, onUnmounted, ref, watch } from "vue";
import EnhancedQRCode from "./EnhancedQRCode.vue";

// Define props
const props = defineProps({
  amount: {
    type: Number,
  }
});

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
const qrRefreshInterval = ref(null);
const transactionId = ref("");
const statusCheckInterval = ref(null);

// Payment success modal variables
const showSuccessModal = ref(false);
const paymentDetails = ref(null);

const merchantName = "Khun Hotel";
const currency = "KHR";
const amount = computed(() => props.amount); // Use computed to get reactive amount from props

// Function to format Bakong hash (first 8 digits)
const formatBakongHash = (hash) => {
  if (!hash) return "N/A";
  return hash.substring(0, 8);
};

// Function to format date
const formatDate = (dateString) => {
  if (!dateString) return "N/A";
  return new Date(dateString).toLocaleString();
};

const generateQRCode = async () => {
  // This will be called by parent component after payment is created
  console.log("QR code generation handled by parent component");
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

// Handle payment creation from parent component
const handlePaymentCreated = (paymentData) => {
  currentQRData.value = paymentData;
  transactionId.value = paymentData.transaction_id;

  // Generate QR image URL
  const qrData = encodeURIComponent(paymentData.qr_string);
  qrImageUrl.value = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${qrData}&format=png&margin=10`;

  console.log("✅ Payment created with booking data:", paymentData);

  // Start checking payment status
  startPaymentStatusChecking();
};

// Start payment status checking
const startPaymentStatusChecking = () => {
  if (statusCheckInterval.value) {
    clearInterval(statusCheckInterval.value);
  }

  // Check immediately and then every 3 seconds
  checkPaymentStatus();
  statusCheckInterval.value = setInterval(() => {
    checkPaymentStatus();
  }, 3000);
};

// Payment status checker with enhanced logging and better error handling
const checkPaymentStatus = async () => {
  if (!transactionId.value) return;

  try {
    console.log(
      `Checking payment status for transaction: ${transactionId.value}`
    );

    const response = await fetch(
      `http://localhost:8100/api/payments/${transactionId.value}/status-with-booking`,
      {
        method: "GET",
        headers: {
          Accept: "application/json",
        },
      }
    );

    if (!response.ok) {
      console.error(`HTTP Error: ${response.status} ${response.statusText}`);
      return;
    }

    const data = await response.json();
    console.log("Payment status response:", data);

    if (data.success) {
      if (data.status === "success" && data.booking_created) {
        // Payment completed successfully and booking created
        console.log("🎉 Payment completed successfully and booking created!");

        // Prepare payment details for the success modal
        const transaction = data.transaction;
        const apiResponse = data.api_response;
        const methodUsed = data.method_used || "unknown";

        paymentDetails.value = {
          transactionId: transaction?.transaction_id || transactionId.value,
          bakongHash: formatBakongHash(transaction?.qr_md5),
          bank: "Bakong (NBC)",
          fromAccount:
            apiResponse?.fromAccount ||
            apiResponse?.senderAccount ||
            apiResponse?.payerName ||
            "Customer Account",
          amount: `${transaction?.amount || amount.value} ${
            transaction?.currency || currency
          }`,
          seller: transaction?.merchant_name || merchantName,
          transactionDate: formatDate(
            transaction?.updated_at || new Date().toISOString()
          ),
          paymentMethod: `KHQR Payment (${methodUsed})`,
          methodUsed: methodUsed,
          bookingReference: transaction?.booking_reference || "N/A",
        };

        // Show success modal
        showSuccessModal.value = true;

        // Stop all intervals when payment is completed
        stopAllIntervals();

        // Log successful payment detection
        console.log(`✅ Payment and booking completed using ${methodUsed} method`);
      } else if (data.status === "failed") {
        // Payment failed
        console.log("❌ Payment failed");
        // You can show an error modal here if needed
        stopAllIntervals();
      } else if (data.status === "expired") {
        // Payment expired
        console.log("⏰ Payment expired");
        stopAllIntervals();
      } else {
        // Still pending
        console.log("⏳ Payment still pending...");
      }
    } else {
      console.log("Payment status check failed:", data.message);
    }
  } catch (error) {
    console.error("Error checking payment status:", error);
  }
};

// Helper function to stop all intervals
const stopAllIntervals = () => {
  if (statusCheckInterval.value) {
    clearInterval(statusCheckInterval.value);
    statusCheckInterval.value = null;
  }
  if (qrRefreshInterval.value) {
    clearInterval(qrRefreshInterval.value);
    qrRefreshInterval.value = null;
  }
  console.log("🛑 All payment checking intervals stopped");
};

// Close success modal
const closeSuccessModal = () => {
  showSuccessModal.value = false;
  paymentDetails.value = null;
};

// Test function to manually trigger success modal (for debugging)
const testSuccessModal = () => {
  paymentDetails.value = {
    transactionId: "TXN_TEST123",
    bakongHash: "ABC12345",
    bank: "Bakong (NBC)",
    fromAccount: "Test Account",
    amount: "100 KHR",
    seller: "Khun Hotel",
    transactionDate: formatDate(new Date().toISOString()),
    paymentMethod: "KHQR Payment",
    methodUsed: "test",
    bookingReference: "BOOKING_123",
  };
  showSuccessModal.value = true;
};

// Initialize QR generation when QR payment is selected - removed auto-refresh
const handlePaymentMethodChange = () => {
  if (selectedPayment.value === "qr") {
    // QR generation will be handled by parent component
    console.log("QR payment method selected");
  }
};

// Watch for payment method changes
watch(selectedPayment, (newMethod, oldMethod) => {
  handlePaymentMethodChange();
});

// Cleanup on component unmount
onUnmounted(() => {
  stopAllIntervals();
});

// Expose form data for parent component
defineExpose({
  agreeToTerms,
  handlePaymentCreated,
  getFormData: () => ({
    firstName: firstName.value,
    lastName: lastName.value,
    email: email.value,
    phoneNumber: phoneNumber.value,
    specialRequests: specialRequests.value,
    selectedPayment: selectedPayment.value,
    cardNumber: cardNumber.value,
    expiryDate: expiryDate.value,
    cvv: cvv.value,
    agreeToTerms: agreeToTerms.value,
    pets: pets.value,
  }),
});
</script>

<template>
  <div class="p-6 border border-gray-300 rounded-lg bg-white min-w-[300px]">
    <!-- Payment Success Modal -->
    <div
      v-if="showSuccessModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-transparent backdrop-blur-sm"
      @click.self="closeSuccessModal"
    >
      <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 relative">
        <!-- Close button -->
        <button
          @click="closeSuccessModal"
          class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
        >
          <X class="h-6 w-6" />
        </button>

        <!-- Success icon -->
        <div class="text-center mb-6">
          <div
            class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4"
          >
            <CheckCircle class="h-10 w-10 text-green-600" />
          </div>
          <h2 class="text-2xl font-bold text-gray-900 mb-2">
            Payment Successful
          </h2>
          <p class="text-gray-600">Thank you for Booking at Ptes Khmer.</p>
          <p class="text-gray-600">Your Booking is being processed!</p>
        </div>

        <!-- Payment details -->
        <div v-if="paymentDetails" class="space-y-4">
          <div class="flex justify-between py-2 border-b border-gray-200">
            <span class="text-gray-600">Amount Paid:</span>
            <span class="font-semibold text-green-600">{{
              paymentDetails.amount
            }}</span>
          </div>

          <div class="flex justify-between py-2 border-b border-gray-200">
            <span class="text-gray-600">Payment Method:</span>
            <span class="font-medium">{{ paymentDetails.paymentMethod }}</span>
          </div>

          <div class="flex justify-between py-2 border-b border-gray-200">
            <span class="text-gray-600">Transaction ID:</span>
            <span class="font-medium font-mono text-sm">{{
              paymentDetails.transactionId
            }}</span>
          </div>

          <div class="flex justify-between py-2 border-b border-gray-200">
            <span class="text-gray-600">Bakong Hash #:</span>
            <span class="font-medium font-mono text-sm">{{
              paymentDetails.bakongHash
            }}</span>
          </div>

          <div class="flex justify-between py-2 border-b border-gray-200">
            <span class="text-gray-600">Bank:</span>
            <span class="font-medium">{{ paymentDetails.bank }}</span>
          </div>

          <div class="flex justify-between py-2 border-b border-gray-200">
            <span class="text-gray-600">From Account:</span>
            <span class="font-medium">{{ paymentDetails.fromAccount }}</span>
          </div>

          <div class="flex justify-between py-2 border-b border-gray-200">
            <span class="text-gray-600">Seller:</span>
            <span class="font-medium">{{ paymentDetails.seller }}</span>
          </div>

          <div class="flex justify-between py-2">
            <span class="text-gray-600">Date & Time:</span>
            <span class="font-medium">{{
              paymentDetails.transactionDate
            }}</span>
          </div>
        </div>

        <!-- Action buttons -->
        <div class="mt-8 space-y-3">
          <button
            @click="viewOrderHistory"
            class="w-full bg-gray-900 text-white py-3 px-4 rounded-lg hover:bg-gray-800 transition-colors font-medium"
          >
            View Order History
          </button>
          <button
            @click="redirectToHome"
            class="w-full bg-white text-gray-900 py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors font-medium"
          >
            Back to Home
          </button>
        </div>
      </div>
    </div>

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
           <EnhancedQRCode
          v-if="currentQRData"
          :qrString="currentQRData.qr_string"
          :amount="amount"
          :currency="currency"
          :merchantName="merchantName"
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
          </div>

          <!-- Instructions -->
          <div class="text-center text-sm text-gray-500 max-w-md">
            <p>
              Scan the QR code with your mobile banking app (ABA, Wing, etc.) to
              complete the payment.
            </p>
            <p class="mt-2 font-medium">
              This QR code remains valid until payment is completed.
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
