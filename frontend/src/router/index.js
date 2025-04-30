
// import HotelCard from '@/components/HotelCard.vue';
import ListRoom from '@/components/ListRoom.vue';
import Test1 from '@/components/Test1.vue';
import Admin from '@/views/Admin.vue';
import Checkout from '@/views/Checkout.vue';
import HistoryKeeper from '@/views/HistoryKeeper.vue';
import { createRouter, createWebHistory } from 'vue-router';
const routes = [
    {
      path: '/',
      name: 'Checkout',
      component: Checkout,
    },
    {
      path:'/admin',
      name: 'Admin',
      component:Test1
    },
    {
      path:'/test1',
      name:'Test1',
      component:Admin
    },
    {
        path: '/success',
        name: 'Success',
        component:HistoryKeeper
    },
    { 
      path: '/listroom',
      name: "Hotel Card",
      component: ListRoom,
    }
   
]
const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
  });
  export default router;
