<template>
    <div class="step-container">
      <h2>Create Password</h2>
      <p>Use a minimum of 10 characters, including uppercase, lowercase, and numbers.</p>
      <input
        type="password"
        v-model="localPassword"
        placeholder="Enter Password"
      />
      <input
        type="password"
        v-model="localConfirmPassword"
        placeholder="Confirm Password"
      />
      <button @click="submit">Submit</button>
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
        localPassword: this.password,
        localConfirmPassword: this.confirmPassword,
      };
    },
    watch: {
      localPassword(newVal) {
        this.$emit('update:password', newVal);
      },
      localConfirmPassword(newVal) {
        this.$emit('update:confirmPassword', newVal);
      },
      password(newVal) {
        this.localPassword = newVal;
      },
      confirmPassword(newVal) {
        this.localConfirmPassword = newVal;
      },
    },
    methods: {
      submit() {
        this.$emit('submit-form');
      },
    },
  };
  </script>
  
  <style scoped>
  .step-container {
    text-align: center;
  }
  
  input {
    width: 100%;
    padding: 0.5rem;
    margin: 1rem 0;
  }
  
  button {
    width: 100%;
    padding: 0.75rem;
    background-color: #332c2b;
    color: white;
    border: none;
    border-radius: 5px;
  }
  </style>
  