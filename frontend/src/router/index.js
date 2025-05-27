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
import OwnerPropertyPage from "@/views/OwnerPropertyPage.vue";

import Chatbox from "@/views/ChatBox.vue";

import index2 from "./index2";

import { createRouter, createWebHistory } from "vue-router";
import UploadProperty from "@/views/AdminPage/UploadProperty.vue";
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
      requiresAuth: true ,
      roles: ['user'] 
    }
  },
  {
    path: "/checkout",
    name: "Success",
    component: CheckoutPage,
    meta:{requiresAuth:true,
      roles: ['user'] 
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
    props: true ,
    component: ProductDetailPage,
  },
  {
    path: "/chat",
    name: "Chat",
    component: Chatbox,
  },
  {
    path: "/upload-property",
    name: "uploadProperty",
    component: UploadProperty,
  },
  {
    path: "/setting",
    name: "SettingUser",
    component: SettingPage,
    meta: {
      requiresAuth: true ,
      roles: ['user']
    }
  },
  {
    path: "/setting-details",
    name: "SettingDetail",
    component: SettingDetailPage,
    meta: {
      requiresAuth: true ,
      requiresRole: 'user' // Only accessible by user role
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
    path: "/owner", // Only accessible by owner role
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
  {
    path: "/owner-property",
    name: "OwnerProperty",
    component: OwnerPropertyPage,
    meta:{
      requiresAuth:true,roles: ['user','owner']
    }
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});
// Navigation guards
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const isAuthenticated = !!token

  if(token){
    if(to.path.startsWith('/authentication')){
      return next('/setting');
    }
    // If route requires authentication
    if (to.meta.requiresAuth) {
      return next(); // authenticated, allow access
    }
    } else {
    // If not logged in and trying to access a protected route
    if (to.meta.requiresAuth) {
      return next('/authentication/signin');
    }
  }

  next(); // default allow
});



// Error handling
router.onError((error) => {
  console.error('Navigation error:', error);
  router.push('/');
});

export default router;


// if (to.meta.requiresAuth && !token) {
//   // If route requires authentication but user is not logged in
//   return next('/login'); // Redirect to login page
// }
// if (to.meta.requiresRole && to.meta.requiresRole !== userRole) {
//   // If the user doesn't have the required role for the route
//   if (userRole === 'user') {
//     return next('/setting'); // Redirect user to setting page
//   }
//   if (userRole === 'owner') {
//     return next('/owner-dashboard'); // Redirect owner to dashboard
//   }
//   return next('/property-register'); // If no role or other roles, redirect to property register page
// }

// next(); // Allow navigation to route
// });