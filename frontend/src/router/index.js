import Homepage from "@/views/Homepage.vue";
import ListRoomPage from "@/views/ListRoomPage.vue";
import HistoryKeeper from "@/views/HistoryKeeper.vue";
import SettingDetailPage from "@/views/SettingDetailPage.vue";
import SettingPage from "@/views/SettingPage.vue";
import CheckoutPage from "@/views/CheckoutPage.vue";
import ProductDetailPage from "@/views/productDetailPage.vue";

import ForgotPassword from "@/views/Authentication/ForgotPassword.vue";
import SignIn from "@/views/Authentication/SignIn.vue";
import SignUp from "@/views/Authentication/SignUp.vue";
import AuthenticationPage from "@/views/Authentication/AuthenticationPage.vue";

import Admin from "@/views/AdminPage/Admin.vue";

import Chatbox from "@/views/ChatBox.vue";

import index2 from "./index2";

import { createRouter, createWebHistory } from "vue-router";
import UploadProperty from "@/views/AdminPage/UploadProperty.vue";
import axios from "axios";

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
    path: "/products-details/:id",
    name: "ProductsDetails",
    props: true,
    component: ProductDetailPage,
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
    path: "/upload-property",
    name: "uploadProperty",
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

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

// Navigation guards - FIXED VERSION
router.beforeEach(async (to, from, next) => {
  try {
    // Skip auth check for authentication routes to avoid infinite loops
    if (to.path.startsWith('/authentication')) {
      return next();
    }

    const res = await axiosInstance.get('/me');
    const isAuthenticated = !!res?.data?.user;
    const userId = res?.data?.user?.id;

    // If user is authenticated and trying to access signin, redirect to settings
    if (to.path === '/authentication/signin' && isAuthenticated) {
      return next('/setting');
    }

    // Check if route requires authentication
    if (to.meta.requiresAuth && !isAuthenticated) {
      return next('/authentication/signin');
    }

    // Check role-based access
    if (isAuthenticated && to.meta.roles) {
      const roleRes = await axiosInstance.get(`/user-roles/${userId}`);
      const userRoles = roleRes.data.roles.map(r => r.name);
      const hasAccess = to.meta.roles.some(role => userRoles.includes(role));
      
      if (!hasAccess) {
        return next('/');
      }
    }

    next();
  } catch (error) {
    console.warn('Auth check failed:', error);
    
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