<template>
    <div class="auth-container">
      <SignIn v-if="currentView === 'signIn'" @switch="switchView" />
      <SignUp v-if="currentView === 'signUp'" @switch="switchView" />
      <ForgotPassword v-if="currentView === 'forgotPassword'" @switch="switchView" />
    </div>
  </template>
  
  <script>
  import SignIn from '@/views/Authentication/SignIn.vue'
  import SignUp from '@/views/Authentication/SignUp.vue'
  import ForgotPassword from '@/views/Authentication/ForgotPassword.vue'
  
  export default {
    components: {
      SignIn,
      SignUp,
      ForgotPassword
    },
    data() {
      return {
        currentView: 'signIn' // default view
      }
    },
    methods: {
      switchView(view) {
        this.currentView = view
      }
    }
  }
  </script>
  
  <style scoped>
  .auth-container {
    min-height: 100vh;
    background: #f5f5f5;
  }
  </style>
  