
// import HotelCard from '@/components/HotelCard.vue';
import Current_Future_Past from '@/components/Current_Future_Past.vue';
import ListBooking from '@/components/ListBooking.vue';
import ListRoom from '@/components/ListRoom.vue';
import Test1 from '@/components/Test1.vue';
import ProductDetail from '@/ProductDetails/productDetail.vue';
import Admin from '@/views/Admin.vue';
import Checkout from '@/views/Checkout.vue';
import HistoryKeeper from '@/views/HistoryKeeper.vue';
import SignUpPage from '@/views/Owner/SignUpPage.vue';
import OwnerAuthenticationPage from '@/views/OwnerAuthenticationPage.vue';
import OwnerPropertyPage from '@/views/OwnerPropertyPage.vue';
import Chatbox from '@/views/ChatBox.vue';
import { createRouter, createWebHistory } from 'vue-router';
import SignIn from '@/components/Authentication/SignIn.vue';
import SignUp from '@/components/Authentication/SignUp.vue';
import ForgotPassword from '@/components/Authentication/ForgotPassword.vue';
import SignInOwner from '@/components/Owner/SignIn.vue';
import SignUpOwner1 from '@/components/Owner/SignUpStep1.vue';
import SignUpOwner2 from '@/components/Owner/SignUpStep2.vue';
import SignUpOwner3 from '@/components/Owner/SignUpStep3.vue';
import SettingPage from '@/views/SettingPage.vue';
import SettingDetailPage from '@/views/SettingDetailPage.vue';
import AuthenticationPage from '@/views/AuthenticationPage.vue';
import OwnerPropertyCategoryPage from '@/views/OwnerPropertyCategoryPage.vue';
import OwnerPropertyLocationPage from '@/views/OwnerPropertyLocationPage.vue';
const routes = [
  {
    path: '/',
    name: 'Checkout',
    component: Checkout,
  },
  {
    path: '/admin',
    name: 'Admin',
    component: Test1
  },
  {
    path: '/test1',
    name: 'Test1',
    component: Admin
  },
  {
    path: '/success',
    name: 'Success',
    component: HistoryKeeper
  },
  {
    path: '/listroom',
    name: "Hotel Card",
    component: ListRoom,
  },
  {
    path: '/list-booking',
    name: "List Booking",
    component: ListBooking,
  },

  {
    path: '/currrent-booking',
    name: "Current Booking",
    component: Current_Future_Past,
  },
  {
    path: '/products-details',
    name: 'ProductsDetails',
    component: ProductDetail
  },
  {
    path: '/owner',
    name: 'OwnerAuthentication',
    component: OwnerAuthenticationPage
  },
  {
    path: '/signup',
    name: 'SignUp',
    component: SignUpPage
  },
  {
    path: '/ownerproperty',
    name: 'OwnerProperty',
    component: OwnerPropertyPage
  },
  {
    path: '/chat',
    name: "Chat",
    component: Chatbox,
  },
  {
    path: '/user-login',
    name: "UserLogin",
    component: SignIn,
  },
  {
    path: '/user-signup',
    name: "UserSignUp",
    component: SignUp,
  },
  {
    path: '/user-forgot-password',
    name: "UserForgotPassword",
    component: ForgotPassword,
  },
  {
    path: '/owner-signin',
    name: "Owner SignIn",
    component: SignInOwner,
  },
  {
    path: '/owner-signup',
    name: "OwnerSignUp",
    component: SignUpOwner1,
  },
  {
    path: '/owner-signup2',
    name: "OwnerSignUp2",
    component: SignUpOwner2,
  },
  {
    path: '/owner-signup3',
    name: "OwnerSignUp3",
    component: SignUpOwner3,
  },
  {
    path: '/setting-user',
    name: "SettingUser",
    component: SettingPage,
  },
  {
    path: '/setting-details',
    name: "SettingDetail",
    component: SettingDetailPage,
  },
  {
    path: '/authenticationpage',
    name: "AuthenticationPage",
    component: AuthenticationPage
  },
  {
    path: '/ownerpropertycategorypage',
    name: 'OwnerPropertyCategoryPage',
    component: OwnerPropertyCategoryPage
  },
  {
    path: '/ownerpropertylocationpage',
    name: 'OwnerPropertyLocationPage',
    component: OwnerPropertyLocationPage
  }



]
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});
export default router;
