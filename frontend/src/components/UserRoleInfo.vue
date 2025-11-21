<!-- 
  Example component demonstrating how to use the auth utilities
  You can use these patterns in your components
-->
<template>
  <div class="user-info">
    <h3>Current User Info</h3>
    <p><strong>Name:</strong> {{ user?.name }}</p>
    <p><strong>Email:</strong> {{ user?.email }}</p>
    <p><strong>Current Role:</strong> {{ currentRole }}</p>
    <p><strong>All Roles:</strong> {{ roles.join(', ') }}</p>
    
    <div v-if="roles.length > 1" class="role-switcher">
      <h4>Switch Role</h4>
      <button 
        v-for="role in roles" 
        :key="role"
        @click="switchRole(role)"
        :disabled="role === currentRole"
        :class="{ active: role === currentRole }"
      >
        {{ role }}
      </button>
    </div>

    <div class="role-checks">
      <p v-if="hasRole('admin')">✅ You are an admin</p>
      <p v-if="hasRole('owner')">✅ You are an owner</p>
      <p v-if="hasRole('user')">✅ You are a user</p>
    </div>
  </div>
</template>

<script setup>
import axiosInstance from '@/axios';
import {
    getCurrentRole,
    getCurrentUser,
    getUserRoles,
    hasRole,
    updateUserData
} from '@/utils/auth';
import { onMounted, ref } from 'vue';

const user = ref(null);
const roles = ref([]);
const currentRole = ref('');

const loadUserData = () => {
  user.value = getCurrentUser();
  roles.value = getUserRoles();
  currentRole.value = getCurrentRole();
};

const switchRole = async (roleName) => {
  try {
    const response = await axiosInstance.post('/switch-role', {
      role: roleName
    });

    if (response.data.success) {
      // Update localStorage with new user data
      updateUserData(response.data.user);
      
      // Reload local data
      loadUserData();
      
      alert(`Successfully switched to ${roleName} role`);
      
      // Optionally reload the page to update all components
      // window.location.reload();
    }
  } catch (error) {
    console.error('Failed to switch role:', error);
    alert(error.response?.data?.message || 'Failed to switch role');
  }
};

onMounted(() => {
  loadUserData();
});
</script>

<style scoped>
.user-info {
  padding: 2rem;
  background: #f5f5f5;
  border-radius: 8px;
}

.role-switcher {
  margin: 1rem 0;
}

.role-switcher button {
  margin: 0.5rem;
  padding: 0.5rem 1rem;
  border: 1px solid #ccc;
  background: white;
  cursor: pointer;
  border-radius: 4px;
}

.role-switcher button.active {
  background: #2d2323;
  color: white;
}

.role-switcher button:disabled {
  cursor: not-allowed;
}

.role-checks {
  margin-top: 1rem;
  padding: 1rem;
  background: white;
  border-radius: 4px;
}
</style>
