// stores/user.js
import { defineStore } from "pinia";

export const useUserStore = defineStore("user", {
  state: () => ({
    user: null, // Store user data (e.g., { id, name, email })
  }),
  actions: {
    // Set user data after login
    setUser(userData) {
      this.user = userData;
    },
    // Clear user data on logout
    logout() {
      this.user = null;
    },
    // Fetch user data from API (optional)
    async fetchUser() {
      try {
        const response = await axios.get("http://localhost:8100/api/me", {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`, // Assuming JWT token
          },
        });
        this.setUser(response.data);
      } catch (error) {
        console.error("Error fetching user:", error);
        this.logout();
      }
    },
  },
  getters: {
    getUserId: (state) => state.user?.id || null,
    isAuthenticated: (state) => !!state.user,
  },
});