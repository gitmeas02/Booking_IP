<template>
  <header
    class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-lg border-b border-gray-100"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo Section -->
        <RouterLink to="/" class="flex items-center space-x-3 group">
          <div class="relative">
            <img
              src="../../assets/logo.png"
              alt="Hotel Booking Logo"
              class="h-10 w-auto transition-transform duration-300 group-hover:scale-105"
            />
            <div
              class="absolute inset-0 bg-blue-500 opacity-0 group-hover:opacity-10 rounded-lg transition-opacity duration-300"
            ></div>
          </div>
        </RouterLink>

        <!-- Navigation Menu -->
        <nav class="flex items-center space-x-1">
          <!-- Currency Selector -->
          <div class="relative group">
            <button
              class="p-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 flex items-center space-x-1"
            >
              <CircleDollarSign class="w-5 h-5" />
              <span class="hidden lg:inline text-sm font-medium">USD</span>
              <ChevronDown class="w-3 h-3 hidden lg:inline" />
            </button>
            <!-- Dropdown tooltip -->
            <div
              class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap"
            >
              Currency
            </div>
          </div>

          <!-- Help Center -->
          <div class="relative group">
            <button
              class="p-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200"
            >
              <CircleHelp class="w-5 h-5" />
            </button>
            <div
              class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap"
            >
              Help Center
            </div>
          </div>

          <!-- Booking Cart -->
          <div class="relative group">
            <button
              class="p-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 relative"
            >
              <ShoppingCart class="w-5 h-5" />
              <!-- Cart badge -->
              <span
                v-if="cartItemsCount > 0"
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse"
              >
                {{ cartItemsCount }}
              </span>
            </button>
            <div
              class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap"
            >
              My Bookings
            </div>
          </div>

          <!-- Divider -->
          <div class="hidden sm:block w-px h-6 bg-gray-300 mx-2"></div>

          <!-- Your Property Button -->
          <RouterLink to="/owner-property" class="group">
            <button
              class="bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center space-x-2"
            >
              <Building class="w-4 h-4" />
              <span class="hidden md:inline">Your Property</span>
              <span class="md:hidden">Property</span>
            </button>
          </RouterLink>

          <!-- User Account -->
          <div class="relative group">
            <RouterLink
              :to="destination"
              class="flex items-center space-x-2 px-3 py-2 rounded-lg text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 border border-gray-200 hover:border-blue-200"
            >
              <div class="flex items-center space-x-2">
                <span class="hidden md:inline font-medium">{{
                  isAuthenticated ? "My Account" : "Sign In"
                }}</span>
                <div class="relative">
                  <User class="w-5 h-5" />
                  <div
                    v-if="isAuthenticated"
                    class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-white rounded-full"
                  ></div>
                </div>
              </div>
            </RouterLink>
          </div>

          <!-- Mobile Menu Button -->
          <button
            @click="toggleMobileMenu"
            class="sm:hidden p-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200"
          >
            <Menu v-if="!isMobileMenuOpen" class="w-5 h-5" />
            <X v-else class="w-5 h-5" />
          </button>
        </nav>
      </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <Transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <div
        v-if="isMobileMenuOpen"
        class="sm:hidden bg-white border-t border-gray-100 shadow-lg"
      >
        <div class="px-4 py-3 space-y-3">
          <RouterLink to="/owner-property" class="block">
            <button
              class="w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white px-4 py-3 rounded-lg font-medium flex items-center justify-center space-x-2"
            >
              <Building class="w-4 h-4" />
              <span>Your Property</span>
            </button>
          </RouterLink>

          <div class="grid grid-cols-3 gap-3">
            <button
              class="flex flex-col items-center p-3 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200"
            >
              <CircleDollarSign class="w-5 h-5 mb-1" />
              <span class="text-xs">Currency</span>
            </button>
            <button
              class="flex flex-col items-center p-3 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200"
            >
              <CircleHelp class="w-5 h-5 mb-1" />
              <span class="text-xs">Help</span>
            </button>
            <button
              class="flex flex-col items-center p-3 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 relative"
            >
              <ShoppingCart class="w-5 h-5 mb-1" />
              <span class="text-xs">Bookings</span>
              <span
                v-if="cartItemsCount > 0"
                class="absolute top-1 right-3 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center"
              >
                {{ cartItemsCount }}
              </span>
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </header>
</template>

<script setup>
import { RouterLink } from "vue-router";
import {
  User,
  CircleDollarSign,
  ShoppingCart,
  CircleHelp,
  Building,
  Menu,
  X,
  ChevronDown,
} from "lucide-vue-next";
import { onMounted, ref, computed } from "vue";
// import axios from '@/axios'

const isAuthenticated = ref(false);
const isMobileMenuOpen = ref(false);
const cartItemsCount = ref(2); // Mock cart items count
const destination = computed(() =>
  isAuthenticated.value ? "/setting" : "/authentication/signin"
);

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

// Close mobile menu when clicking outside or on route change
const closeMobileMenu = () => {
  isMobileMenuOpen.value = false;
};

onMounted(async () => {
  // Add event listener to close mobile menu when clicking outside
  document.addEventListener("click", (e) => {
    if (!e.target.closest("header")) {
      closeMobileMenu();
    }
  });

  // Mock authentication check - uncomment and modify for real implementation
  // try {
  //   const res = await axios.get('/user') // backend check
  //   if (res?.data?.user) {
  //     isAuthenticated.value = true
  //   }
  // } catch (err) {
  //   console.warn('User not authenticated')
  //   isAuthenticated.value = false
  // }
});
</script>

<style scoped>
/* Additional custom styles for smooth animations */
.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%,
  100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}
</style>
