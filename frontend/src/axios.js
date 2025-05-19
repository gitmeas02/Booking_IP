// src/axios.js
import axios from 'axios'

const instance = axios.create({
  baseURL: 'http://localhost:8100', // Laravel backend
  withCredentials: true             // REQUIRED for Sanctum
})

export default instance
