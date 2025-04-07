import Checkout from '@/views/Checkout.vue';
import { createRouter, createWebHistory } from 'vue-router';
const routes = [
    {
      path: '/',
      name: 'Checkout',
      component: Checkout,
    },
]
const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
  });
  
  export default router;