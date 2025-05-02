<template>
    <div class="min-h-screen flex justify-center items-center p-0 m-0 font-sans bg-gray-100">
        <div class="card bg-white p-8 rounded-lg shadow-lg w-96 flex flex-col justify-between space-y-6">
            <h2 class="title text-2xl font-bold text-gray-800 mb-2">Sign In Partner Account</h2>
            <p class="subtitle text-sm text-gray-500 mb-6">Login to your account to list and manage your property</p>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div class="form-group">
                    <label class="block text-sm text-gray-700">Email address</label>
                    <input type="email" v-model="email" placeholder="example@blabla.com" required
                        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div class="form-group">
                    <label class="block text-sm text-gray-700">Password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" v-model="password"
                            placeholder="Enter your password" required
                            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer"
                            @click="togglePassword">
                            👁️
                        </span>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-3 bg-gray-800 text-white rounded-lg font-medium text-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Sign In
                </button>
            </form>

            <div class="footer text-center">
                <p class="text-sm">Don't have an account?</p>
                <router-link to="/signup" class="text-sm text-blue-500 font-semibold hover:underline">Create an
                    Account</router-link>
            </div>

            <div class="copyright text-center text-xs text-gray-500 mt-6">
                <small>All rights reserved.</small>
                <small>Copyright (2025-2025) - Pteas Khmer</small>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const email = ref('')
const password = ref('')
const showPassword = ref(false)

const togglePassword = () => {
    showPassword.value = !showPassword.value
}

const handleSubmit = () => {
    console.log('Email:', email.value)
    console.log('Password:', password.value)
    // Add your login logic here
}
</script>

<style scoped>
/* Tailwind CSS handles all styling */
</style>
