import { createRouter, createWebHistory } from 'vue-router';

// Components
import Current_Future_Past from '@/components/Current_Future_Past.vue';
import ListBooking from '@/components/ListBooking.vue';
import ListRoom from '@/components/ListRoom.vue';
import Test1 from '@/components/Test1.vue';
import ProductDetail from '@/ProductDetails/productDetail.vue';
import SignIn from '@/components/Authentication/SignIn.vue';
import SignUp from '@/components/Authentication/SignUp.vue';
import ForgotPassword from '@/components/Authentication/ForgotPassword.vue';
import SignInOwner from '@/components/Owner/SignIn.vue';
import SignUpOwner1 from '@/components/Owner/SignUpStep1.vue';
import SignUpOwner2 from '@/components/Owner/SignUpStep2.vue';
import SignUpOwner3 from '@/components/Owner/SignUpStep3.vue';
import Header from '@/components/LandingPage/HeadingBar.vue'; // Not used, consider removing if not used
import Homepage from '@/components/LandingPage/Homepage.vue';

// Views
import Admin from '@/views/Admin.vue';
import Checkout from '@/views/Checkout.vue';
import HistoryKeeper from '@/views/HistoryKeeper.vue';
import SignUpPage from '@/views/Owner/SignUpPage.vue';
import OwnerAuthenticationPage from '@/views/OwnerAuthenticationPage.vue';
import OwnerPropertyPage from '@/views/OwnerPropertyPage.vue';
import Chatbox from '@/views/ChatBox.vue';
import SettingPage from '@/views/SettingPage.vue';
import SettingDetailPage from '@/views/SettingDetailPage.vue';
import AuthenticationPage from '@/views/AuthenticationPage.vue';
import OwnerPropertyCategoryPage from '@/views/OwnerPropertyCategoryPage.vue';
import OwnerPropertyLocationPage from '@/views/OwnerPropertyLocationPage.vue';
import OwnerPropertyDetailPage1 from '@/views/OwnerPropertyDetailPage1.vue';
import OwnerPropertyDetailPage2 from '@/views/OwnerPropertyDetailPage2.vue';
import OwnerPropertyDetailPage3 from '@/views/OwnerPropertyDetailPage3.vue';
import OwnerPropertyDetailPage4 from '@/views/OwnerPropertyDetailPage4.vue';
import OwnerPropertyDetailConfirmationPage from '@/views/OwnerPropertyDetailConfirmationPage.vue';
import OwnerPropertyDetailPhotoPage from '@/views/OwnerPropertyDetailPhotoPage.vue';
import OwnerPropertyDetailPaymentPage from '@/views/OwnerPropertyDetailPaymentPage.vue';
import OwnerPropertyDetailInvoicePage from '@/views/OwnerPropertyDetailInvoicePage.vue';
import OwnerPropertyDetailPartnerPage from '@/views/OwnerPropertyDetailPartnerPage.vue';
import OwnerPropertyDetailLastConfirmPage from '@/views/OwnerPropertyDetailLastConfirmPage.vue';

const routes = [
  { path: '/', name: 'Homepage', component: Homepage },
  { path: '/checkout', name: 'Checkout', component: Checkout },
  { path: '/admin', name: 'Admin', component: Admin },
  { path: '/test1', name: 'Test1', component: Test1 },
  { path: '/success', name: 'Success', component: HistoryKeeper },
  { path: '/listroom', name: 'ListRoom', component: ListRoom },
  { path: '/list-booking', name: 'ListBooking', component: ListBooking },
  { path: '/current-booking', name: 'CurrentBooking', component: Current_Future_Past },
  { path: '/products-details', name: 'ProductDetail', component: ProductDetail },
  { path: '/owner', name: 'OwnerAuthentication', component: OwnerAuthenticationPage },
  { path: '/signup', name: 'SignUp', component: SignUpPage },
  { path: '/ownerproperty', name: 'OwnerProperty', component: OwnerPropertyPage },
  { path: '/chat', name: 'Chat', component: Chatbox },
  { path: '/user-login', name: 'UserLogin', component: SignIn },
  { path: '/user-signup', name: 'UserSignUp', component: SignUp },
  { path: '/user-forgot-password', name: 'UserForgotPassword', component: ForgotPassword },
  { path: '/owner-signin', name: 'OwnerSignIn', component: SignInOwner },
  { path: '/owner-signup', name: 'OwnerSignUp', component: SignUpOwner1 },
  { path: '/owner-signup2', name: 'OwnerSignUp2', component: SignUpOwner2 },
  { path: '/owner-signup3', name: 'OwnerSignUp3', component: SignUpOwner3 },
  { path: '/setting-user', name: 'SettingUser', component: SettingPage },
  { path: '/setting-details', name: 'SettingDetail', component: SettingDetailPage },
  { path: '/authenticationpage', name: 'AuthenticationPage', component: AuthenticationPage },
  { path: '/ownerpropertycategorypage', name: 'OwnerPropertyCategoryPage', component: OwnerPropertyCategoryPage },
  { path: '/ownerpropertylocationpage', name: 'OwnerPropertyLocationPage', component: OwnerPropertyLocationPage },
  { path: '/ownerpropertydetailpage/1', name: 'OwnerPropertyDetailPage1', component: OwnerPropertyDetailPage1 },
  { path: '/ownerpropertydetailpage/2', name: 'OwnerPropertyDetailPage2', component: OwnerPropertyDetailPage2 },
  { path: '/ownerpropertydetailpage/3', name: 'OwnerPropertyDetailPage3', component: OwnerPropertyDetailPage3 },
  { path: '/ownerpropertydetailpage/4', name: 'OwnerPropertyDetailPage4', component: OwnerPropertyDetailPage4 },
  { path: '/ownerpropertydetailpage/confirmation', name: 'OwnerPropertyDetailConfirmationPage', component: OwnerPropertyDetailConfirmationPage },
  { path: '/ownerpropertydetailpage/upload', name: 'OwnerPropertyDetailPhotoPage', component: OwnerPropertyDetailPhotoPage },
  { path: '/ownerpropertydetailpage/payment', name: 'OwnerPropertyDetailPaymentPage', component: OwnerPropertyDetailPaymentPage },
  { path: '/ownerpropertydetailpage/invoice', name: 'OwnerPropertyDetailInvoicePage', component: OwnerPropertyDetailInvoicePage },
  { path: '/ownerpropertydetailpage/partnerverification', name: 'OwnerPropertyDetailPartnerPage', component: OwnerPropertyDetailPartnerPage },
  { path: '/ownerpropertydetailpage/lastconfirmation', name: 'OwnerPropertyDetailLastConfirmPage', component: OwnerPropertyDetailLastConfirmPage },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
