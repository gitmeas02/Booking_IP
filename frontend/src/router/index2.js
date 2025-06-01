import OwnerPropertyCategoryPage from "@/views/OwnerPropertyCategoryPage.vue";
import OwnerPropertyDetailConfirmationPage from "@/views/OwnerPropertyDetailConfirmationPage.vue";
import OwnerPropertyDetailInvoicePage from "@/views/OwnerPropertyDetailInvoicePage.vue";
import OwnerPropertyDetailLastConfirmPage from "@/views/OwnerPropertyDetailLastConfirmPage.vue";
import OwnerPropertyDetailPage1 from "@/views/OwnerPropertyDetailPage1.vue";
import OwnerPropertyDetailPage2 from "@/views/OwnerPropertyDetailPage2.vue";
import OwnerPropertyDetailPage3 from "@/views/OwnerPropertyDetailPage3.vue";
import OwnerPropertyDetailPage4 from "@/views/OwnerPropertyDetailPage4.vue";
import OwnerPropertyDetailPartnerPage from "@/views/OwnerPropertyDetailPartnerPage.vue";
import OwnerPropertyDetailPaymentPage from "@/views/OwnerPropertyDetailPaymentPage.vue";
import OwnerPropertyDetailPhotoPage from "@/views/OwnerPropertyDetailPhotoPage.vue";
import OwnerPropertyLocationPage from "@/views/OwnerPropertyLocationPage.vue";
import Test from "@/views/Test/Test.vue";

export default[
  {
    path: '/ownerpropertycategorypage',
    name: 'OwnerPropertyCategoryPage',
    component: OwnerPropertyCategoryPage
  },
  {
    path: '/ownerpropertylocationpage',
    name: 'OwnerPropertyLocationPage',
    component: OwnerPropertyLocationPage
  },
  {
    path: '/ownerpropertydetailpage/1',
    name: 'OwnerPropertyDetailPage1',
    component: OwnerPropertyDetailPage1
  },
  {
    path: '/ownerpropertydetailpage/2',
    name: 'OwnerPropertyDetailPage2',
    component: OwnerPropertyDetailPage2
  },
  {
    path: '/ownerpropertydetailpage/3',
    name: 'OwnerPropertyDetailPage3',
    component: OwnerPropertyDetailPage3
  },
  {
    path: '/ownerpropertydetailpage/4',
    name: 'OwnerPropertyDetailPage4',
    component: OwnerPropertyDetailPage4
  },
  {
    path: '/ownerpropertydetailpage/confirmation',
    name: 'OwnerPropertyDetailPageConfirmation',
    component: OwnerPropertyDetailConfirmationPage
  },
  {
    path: '/ownerpropertydetailpage/upload',
    name: 'OwnerPropertyDetailPagePhoto',
    component: OwnerPropertyDetailPhotoPage
  },
  {
    path: '/ownerpropertydetailpage/payment',
    name: 'OwnerPropertyDetailPaymentPage',
    component: OwnerPropertyDetailPaymentPage
  },
  {
    path: '/ownerpropertydetailpage/invoice',
    name: 'OwnerPropertyDetailInvoice',
    component: OwnerPropertyDetailInvoicePage
  },
  {
    path: '/ownerpropertydetailpage/partnerverification',
    name: 'OwnerPropertyDetailPartner',
    component: OwnerPropertyDetailPartnerPage
  },
  {
    path: '/ownerpropertydetailpage/lastconfirmation',
    name: 'OwnerPropertyDetailLastConfirmation',
    component: OwnerPropertyDetailLastConfirmPage
  },
  { 
    path:'/test',
    name:"test",
    component:Test,
    meta:{
      require:true
    }
  }





]
