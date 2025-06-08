
import Test from "@/views/Test/Test.vue";
import OwnerPropertyTypePage from "@/views/OwnerApply/OwnerPropertyTypePage.vue";
import OwnerPropertyName from "@/views/OwnerApply/OwnerPropertyName.vue";
import OwnerPropertyAmenity from "@/views/OwnerApply/OwnerPropertyAmenity.vue";
import OwnerPropertyServicePage from "@/views/OwnerApply/OwnerPropertyServicePage.vue";
import OwnerPropertyHotelRulePage from "@/views/OwnerApply/OwnerPropertyHotelRulePage.vue";
import OwnerPropertyDetailConfirmationPage from "@/views/OwnerApply/OwnerPropertyDetailConfirmationPage.vue";
import OwnerPropertyPhotosPage from "@/views/OwnerApply/OwnerPropertyPhotosPage.vue";
import OwnerPropertyAcceptPaymentPage from "@/views/OwnerApply/OwnerPropertyAcceptPaymentPage.vue";
import OwnerPropertyPersonalDetailsPage from "@/views/OwnerApply/OwnerPropertyPersonalDetailsPage.vue";
import OwnerPropertyLocationPage from "@/views/OwnerApply/OwnerPropertyLocationPage.vue";
import OwnerPropertyDetailLastConfirmPage from "@/views/OwnerApply/OwnerPropertyDetailLastConfirmPage.vue";

export default[
   {
      path: "/owner-property",
      name: "OwnerPropertyPage1", // page1
      component: OwnerPropertyTypePage,
      meta:{
        requiresAuth:true,roles: ['user','owner']
      }
    },
  {
    path: '/ownerproperty-location',
    name: 'OwnerPropertyPage2', //page2
    component: OwnerPropertyLocationPage
  },
  {
    path: '/ownerproperty-name/', //page3
    name: 'OwnerPropertyPage3',
    component: OwnerPropertyName
  },
  {
    path: '/ownerproperty-amenities',
    name: 'OwnerPropertyPage4',
    component: OwnerPropertyAmenity //page4
  },
  {
    path: '/ownerproperty-services',
    name: 'OwnerPropertyPage5',
    component: OwnerPropertyServicePage// page5
  },
  {
    path: '/ownerproperty-house-rule',
    name: 'OwnerPropertyPage6', //page6
    component: OwnerPropertyHotelRulePage
  },
  {
    path: '/ownerproperty-confirmation', // show 3 step property details from page 1 to page6, images,final setp
    name: 'OwnerPropertyPage7', //page7
    component: OwnerPropertyDetailConfirmationPage
  },
  {
    path: '/ownerproperty-uplaods-images',
    name: 'OwnerPropertyPage8',
    component: OwnerPropertyPhotosPage //page8
  },
  {
    path: '/ownerproperty-accept-payment',
    name: 'OwnerPropertyPage9', //page9
    component: OwnerPropertyAcceptPaymentPage
  },
  {
    path: '/ownerproperty/partnerverification',
    name: 'OwnerPropertyPage10', //page10
    component: OwnerPropertyPersonalDetailsPage
  },
  {
    path: '/owner/confirmation', //page11
    name: 'OwnerPropertyPage11',
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
