import axios from 'axios';

// Create an Axios instance with default configuration
const axiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Request interceptor to add authentication token
axiosInstance.interceptors.request.use(
  (config) => {
    // Retrieve token from localStorage
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor to handle authentication errors
axiosInstance.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    if (error.response?.status === 401) {
      // Handle unauthorized access (e.g., token expired or invalid)
      console.warn('Unauthorized request. Redirecting to login...');
      localStorage.removeItem('token'); // Clear invalid token
      // Redirect to login page (adjust route as needed)
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default axiosInstance;