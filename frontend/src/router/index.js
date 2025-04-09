import Footer from '@/components/Footer.vue';
import Header from '@/components/Header.vue';
import Checkout from '@/views/Checkout.vue';
import HistoryKeeper from '@/views/HistoryKeeper.vue';
import { createRouter, createWebHistory } from 'vue-router';
const routes = [
    // {
    //   path: '/',
    //   name: 'Checkout',
    //   component: Checkout,
    // },
    {
      path: '/success',
      name: 'Success',
      component:HistoryKeeper,
    },
    {
      path: '/',
      name: 'Header',
      component:Header,
    },
    {
      path: '/footer',
      name: 'Footer',
      component:Footer,
    }
]
const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
  });
  
  export default router;