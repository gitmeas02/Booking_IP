<template>
  <header
    class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-lg border-b border-gray-100"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Loading State -->
        <div v-if="isLoading" class="absolute inset-0 flex items-center justify-center bg-white/80">
          <div class="w-6 h-6 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
        </div>

        <!-- Logo Section -->
        <RouterLink to="/" class="flex items-center space-x-3 group" aria-label="Go to homepage">
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
        <nav class="flex items-center space-x-1" aria-label="Main navigation">
          <!-- Currency Selector -->
          <div class="relative group">
            <button
              class="p-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 flex items-center space-x-1"
              @click="toggleCurrencyDropdown"
              aria-label="Select currency"
            >
              <CircleDollarSign class="w-5 h-5" />
              <span class="hidden lg:inline text-sm font-medium">{{ selectedCurrency }}</span>
              <ChevronDown class="w-3 h-3 hidden lg:inline" :class="{ 'rotate-180': isCurrencyDropdownOpen }" />
            </button>
            <div
              class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap"
            >
              Currency
            </div>
            <Transition
              enter-active-class="transition-all duration-200 ease-out"
              enter-from-class="opacity-0 scale-95 -translate-y-1"
              enter-to-class="opacity-100 scale-100 translate-y-0"
              leave-active-class="transition-all duration-150 ease-in"
              leave-from-class="opacity-100 scale-100 translate-y-0"
              leave-to-class="opacity-0 scale-95 -translate-y-1"
            >
              <div
                v-if="isCurrencyDropdownOpen"
                class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
              >
                <button
                  v-for="currency in currencies"
                  :key="currency"
                  @click="selectCurrency(currency)"
                  class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors duration-150"
                >
                  {{ currency }}
                </button>
              </div>
            </Transition>
          </div>

          <!-- Help Center -->
          <div class="relative group">
            <button
              class="p-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200"
              aria-label="Help Center"
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
              aria-label="View my bookings"
            >
              <ShoppingCart class="w-5 h-5" />
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
          <div class="hidden sm:block w-px h-6 bg-gray-300 mx-2" aria-hidden="true"></div>

          <!-- Your Property Button -->
          <RouterLink :to="ownerDestination" id="owner" class="group" aria-label="Manage your property">
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
              id="account"
              class="flex items-center space-x-2 px-3 py-2 rounded-lg text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 border border-gray-200 hover:border-blue-200"
              :aria-label="isAuthenticated ? 'Go to my account' : 'Sign in'"
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
                    aria-label="User is authenticated"
                  ></div>
                </div>
              </div>
            </RouterLink>
          </div>

          <!-- Role Switcher -->
          <div v-if="isAuthenticated && roles.length > 1" class="relative">
            <button
              id="role"
              class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium flex items-center space-x-2 hover:bg-gray-200 transition-all duration-200"
              @click="toggleRoleSwitcher"
              aria-label="Switch user role"
            >
              <span class="text-sm">{{ currentRole.charAt(0).toUpperCase() + currentRole.slice(1) }}</span>
              <ChevronDown class="w-4 h-4" :class="{ 'rotate-180': isRoleSwitcherOpen }" />
            </button>
            <Transition
              enter-active-class="transition-all duration-200 ease-out"
              enter-from-class="opacity-0 scale-95 -translate-y-1"
              enter-to-class="opacity-100 scale-100 translate-y-0"
              leave-active-class="transition-all duration-150 ease-in"
              leave-from-class="opacity-100 scale-100 translate-y-0"
              leave-to-class="opacity-0 scale-95 -translate-y-1"
            >
              <div
                v-if="isRoleSwitcherOpen"
                class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 role-dropdown"
              >
                <button
                  v-for="role in roles"
                  :key="role"
                  @click="switchRole(role)"
                  :class="[
                    'w-full text-left px-4 py-2 text-sm transition-colors duration-150',
                    currentRole === role
                      ? 'bg-blue-50 text-blue-600 font-medium'
                      : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600'
                  ]"
                  :aria-label="`Switch to ${role} role`"
                >
                  <div class="flex items-center justify-between">
                    <span>{{ role.charAt(0).toUpperCase() + role.slice(1) }}</span>
                    <span v-if="currentRole === role" class="text-blue-600">✓</span>
                  </div>
                </button>
              </div>
            </Transition>
          </div>

          <!-- Mobile Menu Button -->
          <button
            @click="toggleMobileMenu"
            class="sm:hidden p-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200"
            :aria-label="isMobileMenuOpen ? 'Close mobile menu' : 'Open mobile menu'"
            aria-expanded="isMobileMenuOpen"
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
        role="region"
        aria-label="Mobile navigation menu"
      >
        <div class="px-4 py-3 space-y-3">
          <!-- Mobile Role Switcher -->
          <div v-if="isAuthenticated && roles.length > 1" class="mb-3">
            <button
              class="w-full bg-gray-100 text-gray-700 px-4 py-3 rounded-lg font-medium flex items-center justify-between"
              @click="toggleRoleSwitcher"
              aria-label="Switch user role"
            >
              <span>Role: {{ currentRole.charAt(0).toUpperCase() + currentRole.slice(1) }}</span>
              <ChevronDown class="w-4 h-4" :class="{ 'rotate-180': isRoleSwitcherOpen }" />
            </button>
            <div v-if="isRoleSwitcherOpen" class="mt-2 space-y-1 role-dropdown">
              <button
                v-for="role in roles"
                :key="role"
                @click="switchRole(role)"
                :class="[
                  'w-full text-left px-4 py-2 text-sm rounded-lg transition-colors duration-150',
                  currentRole === role
                    ? 'bg-blue-50 text-blue-600 font-medium'
                    : 'text-gray-700 hover:bg-gray-50'
                ]"
                :aria-label="`Switch to ${role} role`"
              >
                {{ role.charAt(0).toUpperCase() + role.slice(1) }}
              </button>
            </div>
          </div>

          <RouterLink :to="ownerDestination" class="block" aria-label="Manage your property">
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
              aria-label="Select currency"
            >
              <CircleDollarSign class="w-5 h-5 mb-1" />
              <span class="text-xs">Currency</span>
            </button>
            <button
              class="flex flex-col items-center p-3 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200"
              aria-label="Help Center"
            >
              <CircleHelp class="w-5 h-5 mb-1" />
              <span class="text-xs">Help</span>
            </button>
            <button
              class="flex flex-col items-center p-3 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 relative"
              aria-label="View my bookings"
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
import { RouterLink, useRoute, useRouter } from "vue-router";
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
import { onMounted, ref, computed, onUnmounted, watch } from "vue";
import axios from 'axios';

// Create Axios instance with VITE_API_BASE_URL from .env
const axiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Add interceptor for authentication token
axiosInstance.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Add response interceptor for error handling
axiosInstance.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      showError('Session expired. Please sign in again.');
      router.push('/authentication/signin');
    }
    return Promise.reject(error);
  }
);

const router = useRouter();
const route = useRoute();
const isAuthenticated = ref(false);
const isMobileMenuOpen = ref(false);
const cartItemsCount = ref(0);
const roles = ref([]);
const currentRole = ref('');
const isRoleSwitcherOpen = ref(false);
const isCurrencyDropdownOpen = ref(false);
const selectedCurrency = ref('USD');
const currencies = ref(['USD', 'EUR', 'GBP', 'JPY']);
const isLoading = ref(true);

// Computed property for account destination
const destination = computed(() =>
  isAuthenticated.value ? "/setting" : "/authentication/signin"
);

// Computed property for owner property link
const ownerDestination = computed(() => {
  if (!isAuthenticated.value) return "/authentication/signin";
  return currentRole.value === 'owner' ? "/owner" : "/owner-property";
});

// Show error notification (replace with a toast library like Vue Toastification)
const showError = (message) => {
  console.error(message); // Replace with toast notification in production
  // Example: VueToastification.error(message);
};

// Toggle mobile menu
const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
  if (!isMobileMenuOpen.value) isRoleSwitcherOpen.value = false;
};

// Close mobile menu
const closeMobileMenu = () => {
  isMobileMenuOpen.value = false;
  isRoleSwitcherOpen.value = false;
  isCurrencyDropdownOpen.value = false;
};

// Toggle role switcher dropdown
const toggleRoleSwitcher = () => {
  isRoleSwitcherOpen.value = !isRoleSwitcherOpen.value;
};

// Toggle currency dropdown
const toggleCurrencyDropdown = () => {
  isCurrencyDropdownOpen.value = !isCurrencyDropdownOpen.value;
};

// Select currency
// const selectCurrency = async (currency) => {
//   selectedCurrency.value = currency;
//   isCurrencyDropdownOpen.value = false;
//   try {
//     await axiosInstance.post('/set-currency', { currency });
//   } catch (err) {
//     console.error('Failed to set currency:', err);
//     showError('Failed to update currency. Please try again.');
//   }
// };

// Fetch cart count
// const fetchCartCount = async () => {
//   try {
//     const res = await axiosInstance.get('/cart/count');
//     cartItemsCount.value = res.data.count || 0;
//   } catch (err) {
//     console.error('Failed to fetch cart count:', err);
//     showError('Failed to load cart data. Please try again.');
//   }
// };

// Switch role and handle redirection
const switchRole = async (role) => {
  if (role === currentRole.value) {
    isRoleSwitcherOpen.value = false;
    return;
  }

  try {
    isLoading.value = true;
    const response = await axiosInstance.post('/switch-role', { role });
    
    if (response.data && response.data.success) {
      currentRole.value = role;
     
      isRoleSwitcherOpen.value = false;
      // Role-specific redirects are handled in router.beforeEach
      await fetchUserData(); // Refresh user data after role switch
      if(role==='owner'){
        router.push('/owner');
      }else{
        router.push('/')
      }
    } else {
      throw new Error('Role switch failed');
    }
  } catch (err) {
    console.error('Failed to switch role:', err);
    if (err.response?.status === 403) {
      showError('Unauthorized to switch to this role.');
    } else {
      showError('Error switching role. Please try again.');
    }
  } finally {
    isLoading.value = false;
  }
};

// Fetch user authentication and roles
const fetchUserData = async () => {
  try {
    isLoading.value = true;
    const res = await axiosInstance.get('/me');
    if (res?.data?.user) {
      isAuthenticated.value = true;
      const roleRes = await axiosInstance.get('/user-roles/' + res.data.user.id);
      if (roleRes?.data?.success) {
        roles.value = roleRes.data.roles.map(r => r.name);
        currentRole.value = roleRes.data.current_role || roles.value[0] || 'user';
      }
    } else {
      isAuthenticated.value = false;
      roles.value = [];
      currentRole.value = '';
    }
  } catch (err) {
    console.warn('User not authenticated or error fetching roles:', err);
    isAuthenticated.value = false;
    roles.value = [];
    currentRole.value = '';
    if (err.response?.status !== 401) {
      showError('Failed to load user data. Please try again.');
    }
  } finally {
    isLoading.value = false;
  }
};

// Handle clicks outside of dropdowns
const handleClickOutside = (e) => {
  if (!e.target.closest("header")) {
    closeMobileMenu();
  } else if (!e.target.closest("#role") && !e.target.closest(".role-dropdown")) {
    isRoleSwitcherOpen.value = false;
  } else if (!e.target.closest("#currency") && !e.target.closest(".currency-dropdown")) {
    isCurrencyDropdownOpen.value = false;
  }
};
watch(route, (newRoute) => {
  console.log('Current Route:', {
    path: newRoute.path,
    name: newRoute.name,
    meta: newRoute.meta,
    params: newRoute.params,
    query: newRoute.query
  });
});
onMounted(async () => {
  await fetchUserData();
  await fetchCartCount();
  document.addEventListener("click", handleClickOutside);
  console.log('Initial Route:', {
    path: route.path,
    name: route.name,
    meta: route.meta,
    params: route.params,
    query: route.query
  });
});

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
});
</script>

<style scoped>
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

.rotate-180 {
  transform: rotate(180deg);
}

.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>