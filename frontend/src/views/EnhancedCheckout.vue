<template>
  <div class="enhanced-checkout min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-4">
          <div class="flex items-center">
            <button 
              @click="goBack"
              class="mr-4 p-2 text-gray-400 hover:text-gray-600"
            >
              <arrow-left-icon class="w-5 h-5" />
            </button>
            <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
          </div>
          
          <!-- Progress Steps -->
          <div class="hidden md:flex items-center space-x-4">
            <div class="flex items-center">
              <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                1
              </div>
              <span class="ml-2 text-sm text-gray-600">Cart</span>
            </div>
            <div class="w-8 h-px bg-gray-300"></div>
            <div class="flex items-center">
              <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                2
              </div>
              <span class="ml-2 text-sm text-gray-600">Payment</span>
            </div>
            <div class="w-8 h-px bg-gray-300"></div>
            <div class="flex items-center">
              <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                3
              </div>
              <span class="ml-2 text-sm text-gray-400">Confirmation</span>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Details -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Order Details</h2>
            
            <!-- Order Items -->
            <div class="space-y-4 mb-6">
              <div 
                v-for="item in orderItems" 
                :key="item.id"
                class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg"
              >
                <img 
                  :src="item.product.image || getDefaultImage()"
                  :alt="item.product.name"
                  class="w-16 h-16 object-cover rounded-md"
                />
                <div class="flex-1">
                  <h4 class="font-medium text-gray-900">{{ item.product.name }}</h4>
                  <p class="text-sm text-gray-600">{{ item.product.description }}</p>
                  <div class="flex items-center mt-2">
                    <span class="text-sm text-gray-500">Quantity: {{ item.quantity }}</span>
                    <span class="mx-2 text-gray-300">|</span>
                    <span class="text-sm text-gray-500">${{ item.product.price }} each</span>
                  </div>
                </div>
                <div class="text-right">
                  <p class="font-semibold text-gray-900">${{ (item.product.price * item.quantity).toFixed(2) }}</p>
                </div>
              </div>
            </div>

            <!-- Customer Information Form -->
            <div class="border-t pt-6">
              <h3 class="text-md font-medium text-gray-900 mb-4">Customer Information</h3>
              <form-info 
                ref="formInfoRef"
                :initial-amount="orderSummary.total"
                @payment-success="handlePaymentSuccess"
              />
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
            
            <div class="space-y-3 mb-6">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal ({{ totalItems }} items):</span>
                <span class="font-medium">${{ orderSummary.subtotal.toFixed(2) }}</span>
              </div>
              
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Tax (10%):</span>
                <span class="font-medium">${{ orderSummary.tax.toFixed(2) }}</span>
              </div>
              
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Service Fee:</span>
                <span class="font-medium">${{ orderSummary.serviceFee.toFixed(2) }}</span>
              </div>
              
              <div class="border-t pt-3">
                <div class="flex justify-between text-lg font-semibold">
                  <span>Total:</span>
                  <span class="text-blue-600">${{ orderSummary.total.toFixed(2) }}</span>
                </div>
              </div>
            </div>

            <!-- Payment Method Selection -->
            <div class="mb-6">
              <h3 class="text-md font-medium text-gray-900 mb-3">Payment Method</h3>
              <div class="space-y-2">
                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                  <input 
                    type="radio" 
                    name="paymentMethod" 
                    value="card"
                    v-model="selectedPaymentMethod"
                    class="text-blue-600 focus:ring-blue-500"
                  />
                  <credit-card-icon class="w-5 h-5 ml-3 text-gray-400" />
                  <span class="ml-2 text-sm font-medium">Credit Card</span>
                </label>
                
                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                  <input 
                    type="radio" 
                    name="paymentMethod" 
                    value="qr"
                    v-model="selectedPaymentMethod"
                    class="text-blue-600 focus:ring-blue-500"
                  />
                  <qr-code-icon class="w-5 h-5 ml-3 text-gray-400" />
                  <span class="ml-2 text-sm font-medium">QR Code Payment</span>
                </label>
                
                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                  <input 
                    type="radio" 
                    name="paymentMethod" 
                    value="cash"
                    v-model="selectedPaymentMethod"
                    class="text-blue-600 focus:ring-blue-500"
                  />
                  <banknote-icon class="w-5 h-5 ml-3 text-gray-400" />
                  <span class="ml-2 text-sm font-medium">Pay at Property</span>
                </label>
              </div>
            </div>

            <!-- Place Order Button -->
            <button
              @click="placeOrder"
              :disabled="!canPlaceOrder || isProcessingOrder"
              class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors duration-200 flex items-center justify-center space-x-2"
            >
              <span v-if="isProcessingOrder">Processing...</span>
              <span v-else>Place Order</span>
            </button>

            <!-- Security Notice -->
            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
              <div class="flex items-center">
                <shield-check-icon class="w-5 h-5 text-green-500 mr-2" />
                <span class="text-sm text-gray-600">Secure payment processing</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div 
      v-if="showSuccessModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <div class="text-center">
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <check-circle-icon class="w-10 h-10 text-green-600" />
          </div>
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Order Confirmed!</h2>
          <p class="text-gray-600 mb-4">Your order has been successfully placed.</p>
          <p class="text-sm text-gray-500 mb-6">Order ID: {{ orderConfirmation.orderId }}</p>
          
          <div class="space-y-3">
            <button 
              @click="goToOrderHistory"
              class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200"
            >
              View Order History
            </button>
            <button 
              @click="continueShopping"
              class="w-full bg-gray-200 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-300 transition-colors duration-200"
            >
              Continue Shopping
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { 
  ArrowLeftIcon, 
  CreditCardIcon, 
  QrCodeIcon, 
  BanknoteIcon,
  ShieldCheckIcon,
  CheckCircleIcon
} from 'lucide-vue-next'
import FormInfo from '@/components/CheckoutComponents/FormInfo.vue'

const route = useRoute()
const router = useRouter()

// Reactive data
const orderItems = ref([])
const selectedPaymentMethod = ref('card')
const isProcessingOrder = ref(false)
const showSuccessModal = ref(false)
const orderConfirmation = ref({})
const formInfoRef = ref(null)

// Computed properties
const totalItems = computed(() => {
  return orderItems.value.reduce((total, item) => total + item.quantity, 0)
})

const orderSummary = computed(() => {
  const subtotal = orderItems.value.reduce((total, item) => total + (item.product.price * item.quantity), 0)
  const tax = subtotal * 0.1
  const serviceFee = subtotal * 0.05
  const total = subtotal + tax + serviceFee
  
  return {
    subtotal,
    tax,
    serviceFee,
    total
  }
})

const canPlaceOrder = computed(() => {
  return orderItems.value.length > 0 && selectedPaymentMethod.value && !isProcessingOrder.value
})

// Methods
const loadOrderData = () => {
  // Load order data from route query or localStorage
  const cartData = route.query.cartData
  if (cartData) {
    try {
      const parsedData = JSON.parse(cartData)
      orderItems.value = parsedData.items || []
    } catch (error) {
      console.error('Error parsing cart data:', error)
    }
  }
  
  // If no cart data, redirect to product catalog
  if (orderItems.value.length === 0) {
    router.push('/products')
  }
}

const placeOrder = async () => {
  if (!canPlaceOrder.value) return
  
  isProcessingOrder.value = true
  
  try {
    // Get form data
    const formData = formInfoRef.value?.getFormData()
    
    if (!formData) {
      throw new Error('Please fill in all required fields')
    }
    
    // Create order data
    const orderData = {
      items: orderItems.value,
      customer: {
        firstName: formData.firstName,
        lastName: formData.lastName,
        email: formData.email,
        phone: formData.phoneNumber
      },
      payment: {
        method: selectedPaymentMethod.value,
        amount: orderSummary.value.total
      },
      summary: orderSummary.value,
      specialRequests: formData.specialRequests
    }
    
    // Process payment based on method
    if (selectedPaymentMethod.value === 'card') {
      await processCardPayment(orderData)
    } else if (selectedPaymentMethod.value === 'qr') {
      await processQRPayment(orderData)
    } else if (selectedPaymentMethod.value === 'cash') {
      await processCashPayment(orderData)
    }
    
    // Show success modal
    orderConfirmation.value = {
      orderId: generateOrderId(),
      total: orderSummary.value.total,
      items: orderItems.value
    }
    
    showSuccessModal.value = true
    
  } catch (error) {
    console.error('Error placing order:', error)
    alert('Error placing order: ' + error.message)
  } finally {
    isProcessingOrder.value = false
  }
}

const processCardPayment = async (orderData) => {
  // Use the new product payment API
  try {
    const response = await fetch('http://localhost:8100/api/product-payments', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        items: orderData.items.map(item => ({
          product_id: item.product.id,
          product_name: item.product.name,
          quantity: item.quantity,
          price: item.product.price
        })),
        customer: orderData.customer,
        payment_method: 'card',
        total_amount: orderData.payment.amount,
        special_requests: orderData.specialRequests
      })
    })
    
    const result = await response.json()
    
    if (!result.success) {
      throw new Error(result.message || 'Payment failed')
    }
    
    console.log('Card Payment processed:', result)
    return result
    
  } catch (error) {
    throw new Error('Card payment failed: ' + error.message)
  }
}

const processQRPayment = async (orderData) => {
  // Use the new product payment API
  try {
    const response = await fetch('http://localhost:8100/api/product-payments', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        items: orderData.items.map(item => ({
          product_id: item.product.id,
          product_name: item.product.name,
          quantity: item.quantity,
          price: item.product.price
        })),
        customer: orderData.customer,
        payment_method: 'qr',
        total_amount: orderData.payment.amount,
        special_requests: orderData.specialRequests
      })
    })
    
    const result = await response.json()
    
    if (!result.success) {
      throw new Error(result.message || 'Payment failed')
    }
    
    console.log('QR Payment created:', result)
    return result
    
  } catch (error) {
    throw new Error('QR payment failed: ' + error.message)
  }
}

const processCashPayment = async (orderData) => {
  // Use the new product payment API
  try {
    const response = await fetch('http://localhost:8100/api/product-payments', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        items: orderData.items.map(item => ({
          product_id: item.product.id,
          product_name: item.product.name,
          quantity: item.quantity,
          price: item.product.price
        })),
        customer: orderData.customer,
        payment_method: 'cash',
        total_amount: orderData.payment.amount,
        special_requests: orderData.specialRequests
      })
    })
    
    const result = await response.json()
    
    if (!result.success) {
      throw new Error(result.message || 'Payment failed')
    }
    
    console.log('Cash Payment processed:', result)
    return result
    
  } catch (error) {
    throw new Error('Cash payment failed: ' + error.message)
  }
}

const generateOrderId = () => {
  return 'ORD-' + Date.now().toString(36).toUpperCase()
}

const handlePaymentSuccess = (paymentData) => {
  console.log('Payment successful:', paymentData)
  // Handle successful payment from FormInfo component
}

const goBack = () => {
  router.go(-1)
}

const goToOrderHistory = () => {
  showSuccessModal.value = false
  router.push('/orders')
}

const continueShopping = () => {
  showSuccessModal.value = false
  router.push('/products')
}

const getDefaultImage = () => {
  return 'https://via.placeholder.com/400x300?text=No+Image'
}

// Lifecycle
onMounted(() => {
  loadOrderData()
})
</script>

<style scoped>
/* Add any custom styles here */
</style>