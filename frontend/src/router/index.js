// src/router/index.js

import OwnerAuthenticationPage from '@/views/OwnerAuthenticationPage.vue'
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  { path: '/', name: 'OwnerAuthentication', component: OwnerAuthenticationPage },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
