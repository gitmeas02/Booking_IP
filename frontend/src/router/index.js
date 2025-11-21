// Lazy load components for better performance
const Homepage = () => import("@/views/Homepage.vue");
const ListRoomPage = () => import("@/views/ListRoomPage.vue");
const HistoryKeeper = () => import("@/views/HistoryKeeper.vue");
const SettingDetailPage = () => import("@/views/SettingDetailPage.vue");
const SettingPage = () => import("@/views/SettingPage.vue");
const CheckoutPage = () => import("@/views/CheckoutPage.vue");
const ProductDetailPage = () => import("@/views/productDetailPage.vue");

const ForgotPassword = () => import("@/views/Authentication/ForgotPassword.vue");
const SignIn = () => import("@/views/Authentication/SignIn.vue");
const SignUp = () => import("@/views/Authentication/SignUp.vue");
const AuthenticationPage = () => import("@/views/Authentication/AuthenticationPage.vue");

const Admin = () => import("@/views/AdminPage/Admin.vue");

const Chatbox = () => import("@/views/ChatBox.vue");

import index2 from "./index2";

import { createRouter, createWebHistory } from "vue-router";
const UploadProperty = () => import("@/views/AdminPage/UploadProperty.vue");
const OwnerBookingList = () => import("@/views/OwnerBookingList.vue");
const OwnerDashboard = () => import("@/views/OwnerDashboard.vue");
const AdminBookingList = () => import("@/views/AdminBookingList.vue");
const RoomManagement = () => import("@/views/Owner/RoomManagement.vue");

import axios from "axios";
const EditRooms = () => import("@/views/AdminPage/EditRooms.vue");

const routes = [
  ...index2,
  {
    path: "/",
    name: "Homepage",
    component: Homepage,
  },
  {
    path: "/current-past-booked",
    name: "CurrentPastBooking",
    component: HistoryKeeper,
    meta: {
      requiresAuth: true,
      roles: ['user', 'owner'] 
    }
  },
  {
    path: "/checkout/:id",
    name: "checkout",
    component: CheckoutPage,
    meta: {
      requiresAuth: true,
      roles: ['user', 'owner'] 
    }
  },
  {
    path: "/listroom",
    name: "Hotel Card",
    component: ListRoomPage,
  },
  {
    path: "/product/:id",
    name: "ProductsDetails",
    props: true,
    component: ProductDetailPage,
  },
  {
    path: "/ownerbookinglist",
    name: "ownerBookingList",
    component: OwnerBookingList,
  },
  {
    path: "/ownerdashboard",
    name: "ownerdashboard",
    component: OwnerDashboard,
  },
  {
    path: "/room-management",
    name: "roomManagement",
    component: RoomManagement,
    meta: {
      requiresAuth: true,
      roles: ['owner']
    }
  },
  {
    path: "/adminbookinglist",
    name: "adminbookinglist",
    component: AdminBookingList,
  },
  {
    path: "/chat",
    name: "Chat",
    component: Chatbox,
    meta: {
      requiresAuth: true,
      roles: ['user', 'owner'] 
    }
  },

  {
  path: "/edit-property",
  name: "editProperty",
  component: EditRooms,
  meta: {
    requiresAuth: true,
    roles: ['owner']
  }
},

  {
  path: "/update-property",
  name: "updateProperty",
  component: UploadProperty,
  meta: {
    requiresAuth: true,
    roles: ['owner']
  }
},
  {
    path: "/setting",
    name: "SettingUser",
    component: SettingPage,
    meta: {
      requiresAuth: true,
      roles: ['user', 'owner'] 
    }
  },
  {
    path: "/setting-details",
    name: "SettingDetail",
    component: SettingDetailPage,
    meta: {
      requiresAuth: true,
      roles: ['user', 'owner']
    }
  },
  {
    path: "/authentication",
    name: "Authentication",
    component: AuthenticationPage,
    children: [
      {
        path: "signin",
        name: "SignIn",
        component: SignIn,
      },
      {
        path: "signup",
        name: "SignUp",
        component: SignUp,
      },
      {
        path: "forgot-password",
        name: "ForgotPassword",
        component: ForgotPassword,
      },
    ],
  },
  {
    path: "/owner",
    name: "Owner",
    component: Admin,
    meta: {
      requiresAuth: true,
      roles: ['owner'] 
    }
  },
  // Catch-all route - redirects to home
  {
    path: '/:pathMatch(.*)*',
    redirect: '/'
  },
];


// Create Axios instance with base URL
const axiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  withCredentials:true
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

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

// Navigation guards - OPTIMIZED VERSION
router.beforeEach(async (to, from, next) => {
  try {
    // Skip auth check for authentication routes to avoid infinite loops
    if (to.path.startsWith('/authentication')) {
      return next();
    }

    // Check if user data exists in localStorage (from login)
    const storedUser = localStorage.getItem('user');
    const token = localStorage.getItem('token');

    if (!token) {
      // No token, redirect to signin if route requires auth
      if (to.meta.requiresAuth) {
        return next('/authentication/signin');
      }
      return next();
    }

    let userData;
    
    // Try to use cached user data first
    if (storedUser) {
      try {
        userData = JSON.parse(storedUser);
      } catch (e) {
        console.error('Failed to parse stored user data:', e);
      }
    }

    // Only fetch from API if no cached data exists
    // Trust fresh login data to avoid delays
    if (!userData) {
      try {
        const res = await axiosInstance.get('/me');
        userData = res?.data?.user;
        
        // Update localStorage with fresh data
        if (userData) {
          localStorage.setItem('user', JSON.stringify(userData));
        }
      } catch (apiError) {
        // If API fails but we're not on a protected route, continue
        console.error('Failed to fetch user data:', apiError);
      }
    }

    const isAuthenticated = !!userData;

    // If user is authenticated and trying to access signin, redirect to settings
    if (to.path === '/authentication/signin' && isAuthenticated) {
      return next('/setting');
    }

    // Check if route requires authentication
    if (to.meta.requiresAuth && !isAuthenticated) {
      return next('/authentication/signin');
    }

    // Check role-based access using embedded role data
    if (isAuthenticated && to.meta.roles) {
      const currentRole = userData.current_role;
      const hasAccess = to.meta.roles.includes(currentRole);
      
      if (!hasAccess) {
        return next('/');
      }
    }

    next();
  } catch (error) {
    console.warn('Auth check failed:', error);
    
    // Clear invalid token/user data on auth failure
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    }
    
    // If route requires auth but we can't verify, redirect to signin
    if (to.meta.requiresAuth) {
      return next('/authentication/signin');
    }
    
    // For public routes, allow access even if auth check fails
    next();
  }
});

// Error handling
router.onError((error) => {
  console.error('Navigation error:', error);
  router.push('/');
});

export default router;