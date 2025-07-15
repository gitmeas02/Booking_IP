<template>
  <div class="product-catalog min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-40">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-gray-900">Product Catalog</h1>
            <span class="ml-4 text-sm text-gray-500">{{ filteredProducts.length }} products</span>
          </div>
          
          <!-- Cart Toggle -->
          <button 
            @click="toggleCart"
            class="relative p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200"
          >
            <shopping-cart-icon class="w-6 h-6" />
            <span 
              v-if="cartItemCount > 0"
              class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"
            >
              {{ cartItemCount }}
            </span>
          </button>
        </div>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Main Content -->
        <div class="flex-1">
          <!-- Filters -->
          <div class="mb-8 bg-white rounded-lg shadow-sm p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <!-- Search -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input 
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search products..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              
              <!-- Category Filter -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select 
                  v-model="selectedCategory"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">All Categories</option>
                  <option v-for="category in categories" :key="category" :value="category">
                    {{ category }}
                  </option>
                </select>
              </div>
              
              <!-- Price Range -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Max Price</label>
                <input 
                  v-model="maxPrice"
                  type="number"
                  placeholder="Any"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>
          </div>

          <!-- Products Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <product-card 
              v-for="product in filteredProducts" 
              :key="product.id"
              :product="product"
              @add-to-cart="addToCart"
              @buy-now="buyNow"
            />
          </div>

          <!-- Loading State -->
          <div v-if="isLoading" class="text-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading products...</p>
          </div>

          <!-- Empty State -->
          <div v-else-if="filteredProducts.length === 0" class="text-center py-12">
            <package-icon class="w-16 h-16 text-gray-300 mx-auto mb-4" />
            <p class="text-gray-600">No products found matching your criteria.</p>
          </div>
        </div>

        <!-- Sidebar Cart -->
        <div 
          v-if="showCart"
          class="lg:w-96 fixed lg:relative top-0 right-0 h-full lg:h-auto bg-white lg:bg-transparent shadow-xl lg:shadow-none z-50 lg:z-auto"
        >
          <div class="lg:sticky lg:top-20">
            <shopping-cart 
              :cart-items="cartItems"
              @update-quantity="updateQuantity"
              @remove-item="removeFromCart"
              @clear-cart="clearCart"
              @proceed-to-checkout="proceedToCheckout"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Cart Overlay (mobile) -->
    <div 
      v-if="showCart"
      class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
      @click="toggleCart"
    ></div>

    <!-- Success Toast -->
    <div 
      v-if="showSuccessToast"
      class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300"
    >
      <div class="flex items-center space-x-2">
        <check-circle-icon class="w-5 h-5" />
        <span>{{ toastMessage }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { ShoppingCartIcon, PackageIcon, CheckCircleIcon } from 'lucide-vue-next'
import ProductCard from '@/components/ProductSelection/ProductCard.vue'
import ShoppingCart from '@/components/ProductSelection/ShoppingCart.vue'

const router = useRouter()

// Reactive data
const products = ref([])
const cartItems = ref([])
const searchQuery = ref('')
const selectedCategory = ref('')
const maxPrice = ref('')
const showCart = ref(false)
const isLoading = ref(false)
const showSuccessToast = ref(false)
const toastMessage = ref('')

// Sample product data (in a real app, this would come from an API)
const sampleProducts = [
  {
    id: 1,
    name: 'Deluxe Hotel Room',
    description: 'Spacious room with city view, perfect for business travelers',
    price: 120.00,
    image: 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=400',
    category: 'Rooms',
    rating: 4.5,
    reviews: 128,
    features: ['WiFi', 'AC', 'City View', 'Room Service'],
    available: true,
    maxQuantity: 5
  },
  {
    id: 2,
    name: 'Premium Suite',
    description: 'Luxury suite with premium amenities and ocean view',
    price: 250.00,
    image: 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=400',
    category: 'Suites',
    rating: 4.8,
    reviews: 89,
    features: ['WiFi', 'AC', 'Ocean View', 'Balcony', 'Kitchenette'],
    available: true,
    maxQuantity: 3
  },
  {
    id: 3,
    name: 'Standard Room',
    description: 'Comfortable room with all basic amenities',
    price: 80.00,
    image: 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=400',
    category: 'Rooms',
    rating: 4.2,
    reviews: 267,
    features: ['WiFi', 'AC', 'TV'],
    available: true,
    maxQuantity: 10
  },
  {
    id: 4,
    name: 'Spa Package',
    description: 'Relaxing spa treatment package with full body massage',
    price: 150.00,
    image: 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=400',
    category: 'Services',
    rating: 4.7,
    reviews: 45,
    features: ['Massage', 'Facial', 'Aromatherapy', 'Relaxation'],
    available: true,
    maxQuantity: 2
  },
  {
    id: 5,
    name: 'Executive Suite',
    description: 'Premium executive suite with business facilities',
    price: 320.00,
    image: 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=400',
    category: 'Suites',
    rating: 4.9,
    reviews: 156,
    features: ['WiFi', 'AC', 'Business Center', 'Lounge Access'],
    available: false,
    maxQuantity: 1
  },
  {
    id: 6,
    name: 'Dining Experience',
    description: 'Fine dining experience with chef special menu',
    price: 95.00,
    image: 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400',
    category: 'Dining',
    rating: 4.6,
    reviews: 234,
    features: ['3-Course Menu', 'Wine Pairing', 'Chef Special'],
    available: true,
    maxQuantity: 8
  }
]

// Computed properties
const categories = computed(() => {
  const cats = [...new Set(products.value.map(p => p.category))]
  return cats.sort()
})

const filteredProducts = computed(() => {
  return products.value.filter(product => {
    const matchesSearch = product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         product.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesCategory = !selectedCategory.value || product.category === selectedCategory.value
    const matchesPrice = !maxPrice.value || product.price <= parseFloat(maxPrice.value)
    
    return matchesSearch && matchesCategory && matchesPrice
  })
})

const cartItemCount = computed(() => {
  return cartItems.value.reduce((total, item) => total + item.quantity, 0)
})

// Methods
const loadProducts = async () => {
  isLoading.value = true
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000))
    products.value = sampleProducts
  } catch (error) {
    console.error('Error loading products:', error)
  } finally {
    isLoading.value = false
  }
}

const addToCart = (productData) => {
  const existingItem = cartItems.value.find(item => item.product.id === productData.product.id)
  
  if (existingItem) {
    existingItem.quantity += productData.quantity
  } else {
    cartItems.value.push({
      id: Date.now(),
      product: productData.product,
      quantity: productData.quantity
    })
  }
  
  showToast(`${productData.product.name} added to cart!`)
}

const buyNow = (productData) => {
  // Add to cart first
  addToCart(productData)
  
  // Proceed to checkout immediately
  proceedToCheckout({
    items: cartItems.value,
    subtotal: cartItems.value.reduce((total, item) => total + (item.product.price * item.quantity), 0),
    tax: cartItems.value.reduce((total, item) => total + (item.product.price * item.quantity), 0) * 0.1,
    total: cartItems.value.reduce((total, item) => total + (item.product.price * item.quantity), 0) * 1.1
  })
}

const updateQuantity = ({ itemId, change }) => {
  const item = cartItems.value.find(item => item.id === itemId)
  if (item) {
    item.quantity += change
    if (item.quantity <= 0) {
      removeFromCart(itemId)
    }
  }
}

const removeFromCart = (itemId) => {
  const index = cartItems.value.findIndex(item => item.id === itemId)
  if (index > -1) {
    cartItems.value.splice(index, 1)
  }
}

const clearCart = () => {
  cartItems.value = []
  showToast('Cart cleared!')
}

const toggleCart = () => {
  showCart.value = !showCart.value
}

const proceedToCheckout = (checkoutData) => {
  // Navigate to enhanced checkout page with cart data
  router.push({
    name: 'EnhancedCheckout',
    query: {
      // Convert cart data to query parameters for the new checkout system
      cartData: JSON.stringify(checkoutData)
    }
  })
}

const showToast = (message) => {
  toastMessage.value = message
  showSuccessToast.value = true
  setTimeout(() => {
    showSuccessToast.value = false
  }, 3000)
}

// Lifecycle
onMounted(() => {
  loadProducts()
})
</script>

<style scoped>
/* Add any custom styles here */
</style>