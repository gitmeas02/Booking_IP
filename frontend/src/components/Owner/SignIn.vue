<template>
    <div class="container">
        <div class="card">
            <h2 class="title">Sign In Partner Account</h2>
            <p class="subtitle">Login to your account to list and manage your property</p>

            <form @submit.prevent="handleSubmit">
                <div class="form-group">
                    <label>Email address</label>
                    <input type="email" v-model="email" placeholder="example@blabla.com" required />
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="password-input">
                        <input :type="showPassword ? 'text' : 'password'" v-model="password"
                            placeholder="Enter your password" required />
                        <span class="toggle" @click="togglePassword">👁️</span>
                    </div>
                </div>

                <button type="submit" class="btn-signin">Sign In</button>
            </form>

            <div class="footer">
                <p>Don't have an account?</p>
                <button class="btn-create">Create an Account</button>
            </div>

            <div class="copyright">
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
.container {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f5f5f5;
}

.card {
    background: #fff;
    padding: 2rem;
    border-radius: 8px;
    width: 400px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.title {
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #2e2e2e;
}

.subtitle {
    font-size: 0.9rem;
    color: #7a7a7a;
    margin-bottom: 1.5rem;
}

.form-group {
    margin-bottom: 1rem;
    text-align: left;
}

.form-group label {
    display: block;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

input[type="email"],
input[type="password"],
input[type="text"] {
    width: 100%;
    padding: 0.7rem;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
}

.password-input {
    position: relative;
}

.password-input .toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
}

.btn-signin {
    background-color: #2e2b2b;
    color: #fff;
    padding: 0.8rem;
    width: 100%;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    margin-top: 1rem;
    cursor: pointer;
}

.btn-create {
    margin-top: 1rem;
    background: none;
    border: 1px solid #2e2b2b;
    padding: 0.7rem 1rem;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
}

.footer {
    margin-top: 1rem;
}

.footer p {
    margin: 0.5rem 0;
}

.copyright {
    margin-top: 1.5rem;
    font-size: 0.8rem;
    color: #9a9a9a;
}
</style>