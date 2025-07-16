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
import { useRouter } from "vue-router";

const router = useRouter();

// Define props
const props = defineProps({
  amount: {
    type: Number,
    default: 100
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
  // Don't generate if already generating or if payment is completed
  if (isGeneratingQR.value || showSuccessModal.value) return;
  
  try {
    isGeneratingQR.value = true;
    console.log("🔄 Generating QR code...");
    
    // Create a basic payment if no current QR data exists
    if (!currentQRData.value) {
      const response = await fetch('http://localhost:8100/api/payments', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          amount: amount.value,
          merchant_name: merchantName,
          booking_reference: 'MANUAL_QR_' + Date.now()
        })
      });
      
      const result = await response.json();
      
      if (result.success) {
        console.log("✅ QR code generated successfully");
        handlePaymentCreated(result);
      } else {
        console.error('❌ Failed to generate QR code:', result.message);
      }
    } else {
      // Just refresh the QR image display - don't create new payment
      console.log("🔄 Refreshing QR code display...");
      const qrData = encodeURIComponent(currentQRData.value.qr_string);
      qrImageUrl.value = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${qrData}&format=png&margin=10&_t=${Date.now()}`;
    }
  } catch (error) {
    console.error('❌ Error generating QR code:', error);
  } finally {
    isGeneratingQR.value = false;
  }
};

// Auto-refresh QR code every 2 minutes
const startAutoRefresh = () => {
  if (qrRefreshInterval.value) {
    clearInterval(qrRefreshInterval.value);
  }

  console.log("🔄 Starting QR code auto-refresh (every 2 minutes)");
  
  qrRefreshInterval.value = setInterval(async () => {
    if (currentQRData.value && selectedPayment.value === 'qr' && !showSuccessModal.value) {
      console.log("🔄 Auto-refreshing QR code...");
      
      try {
        const response = await fetch('http://localhost:8100/api/payments', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            amount: amount.value,
            merchant_name: merchantName,
            booking_reference: 'AUTO_REFRESH_' + Date.now()
          })
        });
        
        const result = await response.json();
        
        if (result.success) {
          // Update QR data but keep the same transaction monitoring
          const oldTransactionId = transactionId.value;
          currentQRData.value = result;
          
          // Update QR image with new data
          const qrData = encodeURIComponent(result.qr_string);
          const timestamp = Date.now();
          qrImageUrl.value = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${qrData}&format=png&margin=15&cache=${timestamp}`;
          
          console.log("✅ QR code auto-refreshed successfully");
        } else {
          console.error("❌ QR code auto-refresh failed:", result.message);
        }
      } catch (error) {
        console.error("❌ Error during QR code auto-refresh:", error);
      }
    }
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
  console.log("🔧 handlePaymentCreated called with data:", paymentData);
  
  // Reset generating state
  isGeneratingQR.value = false;
  
  // Switch to QR payment method automatically
  selectedPayment.value = "qr";
  
  currentQRData.value = paymentData;
  transactionId.value = paymentData.transaction_id;

  // Generate QR image URL with better caching
  if (paymentData.qr_string) {
    const qrData = encodeURIComponent(paymentData.qr_string);
    const timestamp = Date.now();
    qrImageUrl.value = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${qrData}&format=png&margin=15&cache=${timestamp}`;
    
    console.log("✅ QR Code generated:", {
      qr_string: paymentData.qr_string,
      qr_image_url: qrImageUrl.value,
      transaction_id: paymentData.transaction_id
    });
    
    // Start auto-refresh for QR code
    startAutoRefresh();
    
    // Start payment status checking
    startPaymentStatusChecking();
  } else {
    console.error("❌ No QR string found in payment data:", paymentData);
  }

  console.log("✅ Payment created with booking data:", paymentData);
};

// Start payment status checking with optimized intervals
const startPaymentStatusChecking = () => {
  if (statusCheckInterval.value) {
    clearInterval(statusCheckInterval.value);
  }

  // Don't check immediately - wait for actual payment
  console.log("🔍 Starting payment status monitoring...");
  
  // Check every 5 seconds for first 2 minutes, then every 10 seconds
  let checkCount = 0;
  statusCheckInterval.value = setInterval(() => {
    checkCount++;
    checkPaymentStatus();
    
    // Switch to slower checking after 2 minutes (24 checks at 5 seconds each)
    if (checkCount >= 24) {
      clearInterval(statusCheckInterval.value);
      statusCheckInterval.value = setInterval(() => {
        checkPaymentStatus();
      }, 10000); // 10 seconds
    }
  }, 5000); // Initial 5 seconds
};

// Payment status checker with enhanced logging and better error handling
const checkPaymentStatus = async () => {
  if (!transactionId.value) {
    console.log("⚠️ No transaction ID available for status check");
    return;
  }

  try {
    console.log(`🔍 Checking payment status for transaction: ${transactionId.value}`);

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
      console.error(`❌ HTTP Error: ${response.status} ${response.statusText}`);
      return;
    }

    const data = await response.json();
    console.log("📊 Payment status response:", data);

    if (data.success) {
      if (data.status === "success" && data.booking_created) {
        // Payment completed successfully and booking created
        console.log("🎉 Payment completed successfully and booking created!");

        // Stop checking first to prevent duplicate modals
        stopAllIntervals();

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

        // Show success modal only if not already shown
        if (!showSuccessModal.value) {
          showSuccessModal.value = true;
          console.log(`✅ Payment and booking completed using ${methodUsed} method`);
        }
      } else if (data.status === "failed") {
        console.log("❌ Payment failed");
        stopAllIntervals();
      } else if (data.status === "expired") {
        console.log("⏰ Payment expired");
        stopAllIntervals();
      } else {
        console.log("⏳ Payment still pending...");
      }
    } else {
      console.log("⚠️ Payment status check failed:", data.message);
    }
  } catch (error) {
    console.error("❌ Error checking payment status:", error);
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

// Navigate to booking history
const viewOrderHistory = () => {
  closeSuccessModal();
  router.push('/booking-history');
};

// Navigate to home page
const redirectToHome = () => {
  closeSuccessModal();
  router.push('/');
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
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
      @click.self="closeSuccessModal"
    >
      <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4 relative success-modal shadow-2xl">
        <!-- Close button -->
        <button
          @click="closeSuccessModal"
          class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
        >
          <X class="h-6 w-6" />
        </button>

        <!-- Success icon with animation -->
        <div class="text-center mb-6">
          <div
            class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-4 animate-bounce"
          >
            <CheckCircle class="h-12 w-12 text-green-600" />
          </div>
          <h2 class="text-3xl font-bold text-gray-900 mb-2">
            🎉 Payment Successful!
          </h2>
          <p class="text-gray-600 text-lg">Thank you for booking with Khun Hotel!</p>
          <p class="text-green-600 font-semibold">Your room has been reserved successfully!</p>
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
            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium btn-hover"
          >
            View Booking History
          </button>
          <button
            @click="redirectToHome"
            class="w-full bg-white text-gray-900 py-3 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors font-medium btn-hover"
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
          class="flex flex-col items-center space-y-6"
        >
          <!-- QR Code Container -->
          <div
            class="relative bg-gradient-to-b from-blue-50 to-white p-8 border-2 border-blue-200 rounded-xl shadow-xl"
          >
            <div
              v-if="isGeneratingQR"
              class="w-64 h-64 flex items-center justify-center"
            >
              <div class="text-center">
                <div
                  class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600 mx-auto mb-4"
                ></div>
                <p class="text-blue-600 font-semibold">Generating QR Code...</p>
              </div>
            </div>

            <div v-else-if="qrImageUrl" class="text-center">
              <!-- QR Code Image -->
              <img
                :src="qrImageUrl"
                alt="KHQR Payment Code"
                class="w-64 h-64 mx-auto border-2 border-gray-200 rounded-xl shadow-md"
                loading="lazy"
              />

              <!-- Currency and Amount Display -->
              <div class="mt-4 p-3 bg-blue-100 rounded-lg">
                <div class="text-2xl font-bold text-blue-900">
                  {{ amount.toLocaleString() }} {{ currency }}
                </div>
                <div class="text-sm text-blue-700">Cambodian Riel</div>
                <div class="text-xs text-blue-600 mt-1">
                  🔄 Auto-refreshes every 2 minutes
                </div>
              </div>
            </div>

            <div
              v-else
              class="w-64 h-64 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-xl"
            >
              <div class="text-center text-gray-500">
                <QrCode class="h-16 w-16 mx-auto mb-4" />
                <p>QR Code will appear here</p>
                <p class="text-sm">Complete booking to generate</p>
              </div>
            </div>
          </div>

          <!-- Merchant Name -->
          <div class="text-center">
            <h4 class="text-xl font-bold text-gray-800">
              {{ merchantName }}
            </h4>
            <p class="text-blue-600 font-semibold">Hotel Booking Payment</p>
          </div>

          <!-- Payment Instructions -->
          <div class="text-center bg-blue-50 p-4 rounded-lg max-w-md">
            <h5 class="font-semibold text-blue-900 mb-2">Payment Instructions:</h5>
            <ol class="text-sm text-blue-700 space-y-1 text-left">
              <li>1. Open your mobile banking app (ABA, Wing, etc.)</li>
              <li>2. Select "Scan QR Code" or "Pay with QR"</li>
              <li>3. Scan the QR code above</li>
              <li>4. Confirm the payment amount</li>
              <li>5. Complete the transaction</li>
            </ol>
          </div>

          <!-- Action Buttons -->
          <div class="flex gap-4">
            <button
              @click="generateQRCode"
              :disabled="isGeneratingQR"
              class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 flex items-center gap-2 transition-colors"
            >
              <QrCode class="h-5 w-5" />
              {{ isGeneratingQR ? "Generating..." : "Refresh QR Code" }}
            </button>

            <button
              @click="downloadQRCode"
              :disabled="!currentQRData"
              class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 flex items-center gap-2 transition-colors"
            >
              <Download class="h-5 w-5" />
              Save QR Code
            </button>
          </div>

          <!-- Transaction Info -->
          <div
            v-if="currentQRData"
            class="text-center text-sm text-gray-600 bg-gray-50 p-4 rounded-lg max-w-md"
          >
            <p class="font-semibold mb-2">Transaction Details:</p>
            <p><strong>ID:</strong> {{ transactionId }}</p>
            <p><strong>Amount:</strong> {{ amount.toLocaleString() }} {{ currency }}</p>
            <p><strong>Status:</strong> <span class="text-orange-600">Waiting for payment...</span></p>
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
/* Enhanced animations for better UX */
.animate-spin {
  animation: spin 1s linear infinite;
}

.animate-bounce {
  animation: bounce 1s ease-in-out infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

@keyframes bounce {
  0%, 20%, 53%, 80%, 100% {
    animation-timing-function: cubic-bezier(0.215, 0.610, 0.355, 1.000);
    transform: translate3d(0,0,0);
  }
  40%, 43% {
    animation-timing-function: cubic-bezier(0.755, 0.050, 0.855, 0.060);
    transform: translate3d(0, -30px, 0);
  }
  70% {
    animation-timing-function: cubic-bezier(0.755, 0.050, 0.855, 0.060);
    transform: translate3d(0, -15px, 0);
  }
  90% {
    transform: translate3d(0,-4px,0);
  }
}

/* Smooth transitions */
.transition-colors {
  transition: background-color 0.3s ease, color 0.3s ease;
}

/* Modal animations */
.modal-enter-active, .modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from, .modal-leave-to {
  opacity: 0;
  transform: scale(0.9);
}

/* Success modal specific styles */
.success-modal {
  animation: successSlideIn 0.5s ease-out;
}

@keyframes successSlideIn {
  from {
    opacity: 0;
    transform: translateY(-50px) scale(0.9);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* QR Code container animation */
.qr-container {
  animation: qrFadeIn 0.5s ease-out;
}

@keyframes qrFadeIn {
  from {
    opacity: 0;
    transform: scale(0.8);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

/* Button hover effects */
.btn-hover:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Loading dots animation */
@keyframes loadingDots {
  0%, 20% {
    color: rgba(0, 0, 0, 0.4);
    text-shadow: 0.25em 0 0 rgba(0, 0, 0, 0.2),
                 0.5em 0 0 rgba(0, 0, 0, 0.2);
  }
  40% {
    color: rgba(0, 0, 0, 1);
    text-shadow: 0.25em 0 0 rgba(0, 0, 0, 0.4),
                 0.5em 0 0 rgba(0, 0, 0, 0.2);
  }
  60% {
    text-shadow: 0.25em 0 0 rgba(0, 0, 0, 1),
                 0.5em 0 0 rgba(0, 0, 0, 0.4);
  }
  80%, 100% {
    text-shadow: 0.25em 0 0 rgba(0, 0, 0, 1),
                 0.5em 0 0 rgba(0, 0, 0, 1);
  }
}
</style>
