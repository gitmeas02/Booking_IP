<template>
  <div class="product-card bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
    <!-- Product Image -->
    <div class="relative h-48 overflow-hidden">
      <img
        :src="product.image || getDefaultImage()"
        :alt="product.name"
        class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
      />
      <!-- Price Badge -->
      <div class="absolute top-4 right-4 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
        ${{ product.price }}
      </div>
      <!-- Availability Badge -->
      <div 
        class="absolute top-4 left-4 px-3 py-1 rounded-full text-sm font-medium"
        :class="product.available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
      >
        {{ product.available ? 'Available' : 'Sold Out' }}
      </div>
    </div>

    <!-- Product Info -->
    <div class="p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ product.name }}</h3>
      <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ product.description }}</p>
      
      <!-- Product Features -->
      <div class="flex flex-wrap gap-2 mb-4">
        <span 
          v-for="feature in product.features" 
          :key="feature"
          class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs"
        >
          {{ feature }}
        </span>
      </div>

      <!-- Rating and Reviews -->
      <div class="flex items-center mb-4">
        <div class="flex items-center">
          <star-icon 
            v-for="star in 5" 
            :key="star"
            class="w-4 h-4"
            :class="star <= product.rating ? 'text-yellow-400' : 'text-gray-300'"
            fill="currentColor"
          />
        </div>
        <span class="ml-2 text-sm text-gray-600">{{ product.rating }} ({{ product.reviews }} reviews)</span>
      </div>

      <!-- Quantity Selector -->
      <div class="flex items-center justify-between mb-4">
        <span class="text-sm text-gray-700">Quantity:</span>
        <div class="flex items-center space-x-2">
          <button 
            @click="decreaseQuantity"
            :disabled="quantity <= 1"
            class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 disabled:opacity-50"
          >
            <minus-icon class="w-4 h-4" />
          </button>
          <span class="w-8 text-center font-medium">{{ quantity }}</span>
          <button 
            @click="increaseQuantity"
            :disabled="quantity >= product.maxQuantity"
            class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 disabled:opacity-50"
          >
            <plus-icon class="w-4 h-4" />
          </button>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex space-x-3">
        <button
          @click="addToCart"
          :disabled="!product.available || isAddingToCart"
          class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors duration-200 flex items-center justify-center space-x-2"
        >
          <shopping-cart-icon class="w-5 h-5" />
          <span>{{ isAddingToCart ? 'Adding...' : 'Add to Cart' }}</span>
        </button>
        
        <button
          @click="buyNow"
          :disabled="!product.available"
          class="flex-1 bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors duration-200 flex items-center justify-center space-x-2"
        >
          <credit-card-icon class="w-5 h-5" />
          <span>Buy Now</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { 
  ShoppingCartIcon, 
  CreditCardIcon, 
  PlusIcon, 
  MinusIcon,
  StarIcon
} from 'lucide-vue-next'

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['add-to-cart', 'buy-now'])

const quantity = ref(1)
const isAddingToCart = ref(false)

const totalPrice = computed(() => {
  return (props.product.price * quantity.value).toFixed(2)
})

const increaseQuantity = () => {
  if (quantity.value < props.product.maxQuantity) {
    quantity.value++
  }
}

const decreaseQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--
  }
}

const addToCart = async () => {
  isAddingToCart.value = true
  try {
    emit('add-to-cart', {
      product: props.product,
      quantity: quantity.value,
      totalPrice: totalPrice.value
    })
  } finally {
    isAddingToCart.value = false
  }
}

const buyNow = () => {
  emit('buy-now', {
    product: props.product,
    quantity: quantity.value,
    totalPrice: totalPrice.value
  })
}

const getDefaultImage = () => {
  return 'https://via.placeholder.com/400x300?text=No+Image'
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>