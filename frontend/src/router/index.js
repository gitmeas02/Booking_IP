import ListRoomView from '@/views/ListBookingView.vue';
import ChatBox from '@/views/ChatBox.vue';
import ListBookingView from '@/views/ListBookingView.vue';
import { createRouter, createWebHistory } from "vue-router";

const routes = [
  {
    path: "/",
    name: "ListRoomView",
    component: ListRoomView,
  },
  {
    path: "/chatbox",
    name: "ChatBox",
    component: ChatBox,
  },
  {
    path: "/listbooking",
    name: "ListBookingView",
    component: ListBookingView,
  },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Error handling
// router.onError((error) => {
//   console.error('Navigation error:', error);
//   router.push('/');
// });

export default router;