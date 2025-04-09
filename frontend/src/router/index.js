import DateRangePicker from '@/components/DateRangePicker.vue';
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
      path: '/success',
      name: 'Success',
      component:HistoryKeeper
    },

    {
      path:'/test',
      name:'Test',
      component: Admin
    },
    {
      path:'/date',
      name:'Date',
      component:DateRangePicker
    },
    {
      path:'/tt',
      name:'t',
      component:Test1
    },
]
const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
  });
  
  export default router;