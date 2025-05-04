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
import SignInOwner from "@/views/OwnerLogin/SignIn.vue";
import SignUpStep1 from "@/views/OwnerLogin/SignUpStep1.vue";
import SignUpStep2 from "@/views/OwnerLogin/SignUpStep2.vue";
import SignUpStep3 from "@/views/OwnerLogin/SignUpStep3.vue";


import Chatbox from "@/views/ChatBox.vue";


import { createRouter, createWebHistory } from "vue-router";
const routes = [
  {
    path: "/",
    name: "Homepage",
    component: Homepage,
  },
  {
    path: "/current-past-booked",
    name: "CurrentPastBooking",
    component: HistoryKeeper,
  },
  {
    path: "/checkout",
    name: "Success",
    component: CheckoutPage,
  },
  {
    path: "/listroom",
    name: "Hotel Card",
    component: ListRoomPage,
  },
  {
    path: "/products-details",
    name: "ProductsDetails",
    component: ProductDetailPage,
  },
  {
    path: "/chat",
    name: "Chat",
    component: Chatbox,
  },
  {
    path: "/setting",
    name: "SettingUser",
    component: SettingPage,
  },
  {
    path: "/setting-details",
    name: "SettingDetail",
    component: SettingDetailPage,
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
    path: "/admin",
    name: "Admin",
    component: Admin,
  },
  {
    path: "/signin-owner",
    name: "SignIn Owner",
    component: SignInOwner,
  },
  {
    path: "/signup-owner1",
    name: "SignUpStep1",
    component: SignUpStep1,
  },
  {
    path: "/signup-owner2",
    name: "SignUpStep2",
    component: SignUpStep2,
  },
  {
    path: "/signup-owner3",
    name: "SignUpStep3",
    component: SignUpStep3,
  },
  {
    path: "/owner-property",
    name: "OwnerProperty",
    component: OwnerPropertyPage,
  },
];
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});
export default router;
