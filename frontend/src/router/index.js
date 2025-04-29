// src/router/index.js

import SignUpPage from '@/views/Owner/SignUpPage.vue'
import OwnerAuthenticationPage from '@/views/OwnerAuthenticationPage.vue'
import OwnerPropertyPage from '@/views/OwnerPropertyPage.vue'
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  { path: '/', name: 'OwnerAuthentication', component: OwnerAuthenticationPage },
  {
    path: '/signup', name: 'SignUp', component: SignUpPage // which shows SignUpStep1 initially
  },
  {
    path: '/ownerproperty', name: 'OwnerProperty', component: OwnerPropertyPage
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
