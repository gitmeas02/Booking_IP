<template>
  <div class="shopping-cart bg-white rounded-lg shadow-lg max-w-md mx-auto">
    <!-- Cart Header -->
    <div class="p-6 border-b border-gray-200">
      <h2 class="text-xl font-semibold text-gray-900 flex items-center">
        <shopping-cart-icon class="w-6 h-6 mr-2" />
        Shopping Cart
        <span class="ml-2 bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded-full">
          {{ totalItems }}
        </span>
      </h2>
    </div>

    <!-- Cart Items -->
    <div class="max-h-96 overflow-y-auto">
      <div v-if="cartItems.length === 0" class="p-6 text-center text-gray-500">
        <shopping-cart-icon class="w-12 h-12 mx-auto mb-4 text-gray-300" />
        <p>Your cart is empty</p>
        <p class="text-sm">Add some products to get started</p>
      </div>

      <div v-else class="p-6 space-y-4">
        <div 
          v-for="item in cartItems" 
          :key="item.id"
          class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg"
        >
          <!-- Product Image -->
          <img 
            :src="item.product.image || getDefaultImage()"
            :alt="item.product.name"
            class="w-16 h-16 object-cover rounded-md"
          />
          
          <!-- Product Info -->
          <div class="flex-1">
            <h4 class="font-medium text-gray-900">{{ item.product.name }}</h4>
            <p class="text-sm text-gray-600">${{ item.product.price }} each</p>
            
            <!-- Quantity Controls -->
            <div class="flex items-center space-x-2 mt-2">
              <button 
                @click="decreaseQuantity(item.id)"
                :disabled="item.quantity <= 1"
                class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 disabled:opacity-50"
              >
                <minus-icon class="w-3 h-3" />
              </button>
              <span class="w-8 text-center text-sm font-medium">{{ item.quantity }}</span>
              <button 
                @click="increaseQuantity(item.id)"
                :disabled="item.quantity >= item.product.maxQuantity"
                class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 disabled:opacity-50"
              >
                <plus-icon class="w-3 h-3" />
              </button>
            </div>
          </div>

          <!-- Price and Remove -->
          <div class="text-right">
            <p class="font-semibold text-gray-900">${{ (item.product.price * item.quantity).toFixed(2) }}</p>
            <button 
              @click="removeFromCart(item.id)"
              class="text-red-500 hover:text-red-700 mt-1"
            >
              <trash-icon class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Cart Summary -->
    <div v-if="cartItems.length > 0" class="p-6 border-t border-gray-200">
      <div class="space-y-2 mb-4">
        <div class="flex justify-between text-sm text-gray-600">
          <span>Subtotal:</span>
          <span>${{ subtotal.toFixed(2) }}</span>
        </div>
        <div class="flex justify-between text-sm text-gray-600">
          <span>Tax (10%):</span>
          <span>${{ tax.toFixed(2) }}</span>
        </div>
        <div class="flex justify-between text-lg font-semibold text-gray-900 pt-2 border-t">
          <span>Total:</span>
          <span>${{ total.toFixed(2) }}</span>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="space-y-3">
        <button 
          @click="proceedToCheckout"
          class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center space-x-2"
        >
          <credit-card-icon class="w-5 h-5" />
          <span>Proceed to Checkout</span>
        </button>
        
        <button 
          @click="clearCart"
          class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors duration-200"
        >
          Clear Cart
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { 
  ShoppingCartIcon, 
  CreditCardIcon, 
  PlusIcon, 
  MinusIcon,
  TrashIcon
} from 'lucide-vue-next'

const props = defineProps({
  cartItems: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['update-quantity', 'remove-item', 'clear-cart', 'proceed-to-checkout'])

const totalItems = computed(() => {
  return props.cartItems.reduce((total, item) => total + item.quantity, 0)
})

const subtotal = computed(() => {
  return props.cartItems.reduce((total, item) => total + (item.product.price * item.quantity), 0)
})

const tax = computed(() => {
  return subtotal.value * 0.1
})

const total = computed(() => {
  return subtotal.value + tax.value
})

const increaseQuantity = (itemId) => {
  emit('update-quantity', { itemId, change: 1 })
}

const decreaseQuantity = (itemId) => {
  emit('update-quantity', { itemId, change: -1 })
}

const removeFromCart = (itemId) => {
  emit('remove-item', itemId)
}

const clearCart = () => {
  emit('clear-cart')
}

const proceedToCheckout = () => {
  emit('proceed-to-checkout', {
    items: props.cartItems,
    subtotal: subtotal.value,
    tax: tax.value,
    total: total.value
  })
}

const getDefaultImage = () => {
  return 'https://via.placeholder.com/400x300?text=No+Image'
}
</script>

<style scoped>
/* Custom scrollbar for cart items */
.overflow-y-auto::-webkit-scrollbar {
  width: 4px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 2px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>