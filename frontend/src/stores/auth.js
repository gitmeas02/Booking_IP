// /stores/auth.js
import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    roles: []
  }),
  actions: {
    async fetchUser() {
      try {
        const res = await axios.get('http://localhost:8100/api/user', {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        })
        this.user = res.data.user
        this.roles = res.data.roles
      } catch (error) {
        console.error('Failed to fetch user', error)
      }
    }
  }
})
