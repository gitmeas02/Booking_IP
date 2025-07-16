<template>
  <div class="container">
    <div class="form-section">
      <img src="../../assets/images/pteaskhmer.png" alt="Logo" class="logo" />
      <h2>Sign In</h2>

      <div class="social-buttons">
        <button class="social-btn">
          <Icon icon="mdi:facebook" width="24" height="24" />
        </button>
        <button class="social-btn">
          <Icon icon="mdi:google" width="24" height="24" />
        </button>
        <button class="social-btn">
          <Icon icon="mdi:linkedin" width="24" height="24" />
        </button>
      </div>

      <p class="or-text">or use your email account</p>

      <form @submit.prevent="handleSignIn" class="form">
        <div class="input-box">
          <span class="icon">
            <Icon icon="mdi:email" width="24" height="24" />
          </span>
          <input type="email" placeholder="Email" v-model="email" required />
        </div>

        <div class="input-box">
          <span class="icon">
            <Icon icon="mdi:lock" width="24" height="24" />
          </span>
          <input type="password" placeholder="Password" v-model="password" required />
        </div>

        <div>
          <p class="link">
            <a href="#" @click.prevent="$emit('switch', 'forgotPassword')">Forgot your password?</a>
          </p>
        </div>

        <button type="submit" class="submit-btn" :disabled="loading">
          <span v-if="loading" class="loading-spinner"></span>
          {{ loading ? 'Signing In...' : 'Sign In' }}
        </button>
        
        <div v-if="error" class="error-message">
          {{ error }}
        </div>
      </form>
    </div>

    <div class="image-section">
      <h2>Hello!</h2>
      <p>To keep connected with us please<br />login with your personal info</p>
      <button class="ghost-btn" @click="$emit('switch', 'signUp')">Sign Up</button>
    </div>
  </div>
</template>

<script setup>
import axiosInstance from '@/axios';
import { Icon } from '@iconify/vue'; // Import Icon component for use in template
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const error = ref('');
const loading = ref(false);
const router = useRouter();

// NOTE: Make sure your backend exposes POST /api/login or /login at VITE_API_BASE_URL
const handleSignIn = async () => {
  error.value = '';
  loading.value = true;

  try {
    const response = await axiosInstance.post('login', {
      email: email.value,
      password: password.value,
    });

    const { token, user, roles, current_role, applications } = response.data;
    // Store authentication data
    localStorage.setItem('token', token);
    localStorage.setItem('user', JSON.stringify({
      ...user,
      roles: roles,
      current_role: current_role,
      applications: applications
    }));
    // No need to manually set Authorization header, interceptor handles it
    // Navigate without page reload for better performance
    if (router.currentRoute.value.path !== '/setting') {
      router.push('/setting');
    }
  } catch (err) {
    console.error('Login failed', err);
    if (err.response?.status === 422 || err.response?.status === 401) {
      error.value = err.response?.data?.message || 'Invalid credentials.';
    } else {
      error.value = 'Login failed. Please try again.';
    }
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  const token = localStorage.getItem('token');
  if (token && router.currentRoute.value.path !== '/setting') {
    router.push('/setting');
  }
});
</script>

<style scoped>
.container {
  display: flex;
  flex: 1;
  height: 100vh;
  width: 100vw;
  overflow: hidden;
  font-family: 'Arial', sans-serif;
}

.form-section,
.image-section {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 4rem 3rem;
}

.form-section {
  background: #f6f6f6;
  align-items: center;
}

.image-section {
  background: url('../../assets/images/angkor.jpg') no-repeat center/cover;
  color: white;
  text-align: center;
}

.logo {
  width: 80px;
  margin-bottom: 1rem;
}

h2 {
  margin-bottom: 1rem;
  color: #2d2d2d;
}

.social-buttons {
  display: flex;
  gap: 1rem;
  margin: 1rem 0;
}

.social-btn {
  background: #eee;
  border: none;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  cursor: pointer;
}

.or-text {
  margin-bottom: 1rem;
  color: #555;
}

.form {
  width: 100%;
  max-width: 300px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.input-box {
  display: flex;
  align-items: center;
  background: white;
  border: 1px solid #ccc;
  padding: 0.75rem;
  border-radius: 5px;
  margin-bottom: 1rem;
  width: 100%;
}

.input-box input {
  border: none;
  outline: none;
  margin-left: 10px;
  width: 100%;
}

.link {
  align-self: flex-start;
  margin-bottom: 1rem;
  width: 100%;
}

.link a {
  font-size: 14px;
  color: #666;
  text-decoration: none;
}

.submit-btn {
  background: #2d2323;
  color: white;
  border: none;
  padding: 0.75rem 2rem;
  border-radius: 25px;
  font-size: 16px;
  cursor: pointer;
  width: 100%;
  transition: background-color 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.submit-btn:hover:not(:disabled) {
  background: #1a1a1a;
}

.submit-btn:disabled {
  background: #666;
  cursor: not-allowed;
}

.loading-spinner {
  width: 16px;
  height: 16px;
  border: 2px solid #ffffff;
  border-top: 2px solid transparent;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error-message {
  color: #e74c3c;
  font-size: 14px;
  margin-top: 1rem;
  text-align: center;
  background: #ffeaea;
  padding: 0.5rem;
  border-radius: 5px;
  border: 1px solid #e74c3c;
}

.ghost-btn {
  background: transparent;
  border: 2px solid white;
  color: white;
  padding: 0.75rem 2rem;
  border-radius: 25px;
  cursor: pointer;
  margin-top: 1rem;
}

.image-section h2{
  font-size: 32px;
  color: white;
}

/* Responsive */
@media (max-width: 768px) {
  .container {
    flex-direction: column;
    height: auto;
  }

  .form-section,
  .image-section {
    width: 100%;
    padding: 2rem 1.5rem;
  }

  .image-section {
    order: -1;
  }

  .form,
  .link,
  .submit-btn {
    max-width: 100%;
  }
}

@media (max-width: 480px) {
  .logo {
    width: 60px;
  }

  .ghost-btn,
  .submit-btn {
    font-size: 14px;
    padding: 0.5rem 1.5rem;
  }

  .input-box {
    padding: 0.5rem;
  }
}
</style>
