<template>
  <div class="max-w-md mx-auto my-16 p-8 bg-white rounded-lg shadow-xl text-center font-sans">
    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Create Partner Account</h2>
    <p class="text-gray-600 mb-6">Use a minimum of 10 characters, including uppercase letters, lowercase letters and
      numbers</p>

    <div class="form-group mb-4 text-left">
      <label class="block text-sm text-gray-700 mb-2">Password</label>
      <div class="relative">
        <input type="password" id="password" v-model="localPassword" placeholder="Password*" @blur="validatePassword"
          :class="{ 'border-red-500': passwordError }"
          class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 cursor-pointer"
          @click="togglePasswordVisibility('password')">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
          </svg>
        </div>
      </div>
      <p v-if="passwordError" class="text-red-500 text-xs mt-1">{{ passwordError }}</p>
    </div>

    <div class="form-group mb-4 text-left">
      <label class="block text-sm text-gray-700 mb-2">Confirm Password</label>
      <div class="relative">
        <input type="password" id="confirmPassword" v-model="localConfirmPassword" placeholder="Confirm Password*"
          @blur="validateConfirmPassword" :class="{ 'border-red-500': confirmPasswordError }"
          class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 cursor-pointer"
          @click="togglePasswordVisibility('confirm')">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
          </svg>
        </div>
      </div>
      <p v-if="confirmPasswordError" class="text-red-500 text-xs mt-1">{{ confirmPasswordError }}</p>
    </div>

    <button @click="validateAndSubmit"
      class="w-full py-3 bg-gray-800 text-white rounded-lg font-medium text-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
      Next
    </button>

    <div class="copyright text-center text-xs text-gray-500 mt-6">
      <small>All rights reserved.</small>
      <small>Copyright (2025-2025) - Pteas Khmer</small>
    </div>
  </div>
</template>

<script>
export default {
  name: "SignUpStep3",
  props: {
    password: String,
    confirmPassword: String,
  },
  emits: ['update:password', 'update:confirmPassword', 'submit-form'],
  data() {
    return {
      localPassword: this.password || '',
      localConfirmPassword: this.confirmPassword || '',
      passwordError: '',
      confirmPasswordError: '',
    };
  },
  watch: {
    localPassword(newVal) {
      this.$emit('update:password', newVal);
      if (this.passwordError) {
        this.validatePassword();
      }
    },
    localConfirmPassword(newVal) {
      this.$emit('update:confirmPassword', newVal);
      if (this.confirmPasswordError) {
        this.validateConfirmPassword();
      }
    },
    password(newVal) {
      this.localPassword = newVal;
    },
    confirmPassword(newVal) {
      this.localConfirmPassword = newVal;
    },
  },
  methods: {
    togglePasswordVisibility(field) {
      if (field === 'password') {
        const passwordInput = document.getElementById('password');
        passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
      } else if (field === 'confirm') {
        const confirmInput = document.getElementById('confirmPassword');
        confirmInput.type = confirmInput.type === 'password' ? 'text' : 'password';
      }
    },
    validatePassword() {
      this.passwordError = '';

      if (!this.localPassword) {
        this.passwordError = 'Password is required';
        return false;
      }

      if (this.localPassword.length < 10) {
        this.passwordError = 'Password must be at least 10 characters long';
        return false;
      }

      const hasUppercase = /[A-Z]/.test(this.localPassword);
      const hasLowercase = /[a-z]/.test(this.localPassword);
      const hasNumber = /[0-9]/.test(this.localPassword);

      if (!hasUppercase || !hasLowercase || !hasNumber) {
        this.passwordError = 'Password must include uppercase letters, lowercase letters, and numbers';
        return false;
      }

      return true;
    },
    validateConfirmPassword() {
      this.confirmPasswordError = '';

      if (!this.localConfirmPassword) {
        this.confirmPasswordError = 'Please confirm your password';
        return false;
      }

      if (this.localPassword !== this.localConfirmPassword) {
        this.confirmPasswordError = 'Passwords do not match';
        return false;
      }

      return true;
    },
    validateAndSubmit() {
      const passwordValid = this.validatePassword();
      const confirmValid = this.validateConfirmPassword();

      if (passwordValid && confirmValid) {
        this.$emit('submit-form');
      }
    }
  },
};
</script>

<style scoped>
/* Tailwind CSS handles all the styling, no additional styles needed */
</style>
