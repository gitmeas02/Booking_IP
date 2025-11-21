import axios from 'axios';

// IMPORTANT: If you change .env, restart your Vite dev server to reload environment variables.
// Uses VITE_API_BASE_URL from .env (e.g., VITE_API_BASE_URL=http://localhost:8100/api)
const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8100/api';

const axiosInstance = axios.create({
  baseURL: apiBaseUrl,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Log the API Base URL for debugging
console.log('Using API Base URL:', apiBaseUrl);
console.log('Environment variable VITE_API_BASE_URL:', import.meta.env.VITE_API_BASE_URL);

// Request interceptor to add authentication token
axiosInstance.interceptors.request.use(
  (config) => {
    // Always set token from localStorage if available
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    } else {
      delete config.headers.Authorization;
    }
    console.log(`Making request to: ${config.baseURL}/${config.url}`);
    return config;
  },
  (error) => Promise.reject(error)
);

// Response interceptor to handle authentication errors
axiosInstance.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response?.status === 401) {
      // Handle unauthorized access (e.g., token expired or invalid)
      console.warn('Unauthorized request. Token expired or invalid.');
      localStorage.removeItem('token'); // Clear invalid token
      localStorage.removeItem('user'); // Clear user data
      
      // Let the router guard handle the redirect to avoid full page reload
      // The next navigation will trigger the guard which will redirect to signin
      // Don't manually redirect here to prevent page reloads
    }
    return Promise.reject(error);
  }
);

export default axiosInstance;