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

import axiosInstance from '@/axios'; // Use the shared axios instance

import index2 from "./index2";

import { createRouter, createWebHistory } from "vue-router";
const UploadProperty = () => import("@/views/AdminPage/UploadProperty.vue");
const OwnerBookingList = () => import("@/views/OwnerBookingList.vue");
const OwnerDashboard = () => import("@/views/OwnerDashboard.vue");
const AdminBookingList = () => import("@/views/AdminBookingList.vue");
const RoomManagement = () => import("@/views/Owner/RoomManagement.vue");

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

    const res = await axiosInstance.get('me');
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
      const roleRes = await axiosInstance.get(`user-roles/${userId}`);

      console.log("printing roleRes")
      console.log(roleRes)
      // const userRoles = roleRes.data.roles.map(r => r.name);
      // const hasAccess = to.meta.roles.some(role => userRoles.includes(role));
      const hasAccess = to.meta.roles.includes(roleRes.data.current_role);
      // if()
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