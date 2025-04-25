import ListRoomView from '../views/ListRoomView.vue';
import { createRouter, createWebHistory } from "vue-router";

const routes = [
  {
    path: "/",
    name: "ListRoomView",
    component: ListRoomView,
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