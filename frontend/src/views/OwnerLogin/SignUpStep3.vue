<template>
  <div class="step-container">
    <h2>Create Partner Account</h2>
    <p>
      Use a minimum of 10 characters, including uppercase letters, lowercase
      letters and numbers
    </p>

    <div class="form-group">
      <div class="form-group-password">
        <label>Password</label>
        <input
          type="password"
          id="password"
          v-model="localPassword"
          placeholder="Password*"
          @blur="validatePassword"
          :class="{ 'error-input': passwordError }"
        />
      </div>
      <div class="eye-icon" @click="togglePasswordVisibility('password')">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="22"
          height="22"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
          <circle cx="12" cy="12" r="3"></circle>
        </svg>
      </div>
    </div>
    <p v-if="passwordError" class="error-message">{{ passwordError }}</p>

    <div class="form-group">
      <div class="form-group-password">
        <label>Confirm Password</label>
        <input
          type="password"
          id="confirmPassword"
          v-model="localConfirmPassword"
          placeholder="Confirm Password*"
          @blur="validateConfirmPassword"
          :class="{ 'error-input': confirmPasswordError }"
        />
      </div>
      <div class="eye-icon" @click="togglePasswordVisibility('confirm')">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="22"
          height="22"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
          <circle cx="12" cy="12" r="3"></circle>
        </svg>
      </div>
    </div>
    <p v-if="confirmPasswordError" class="error-message">
      {{ confirmPasswordError }}
    </p>

    <button @click="validateAndSubmit">Next</button>

    <div class="copyright">
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
  emits: ["update:password", "update:confirmPassword", "submit-form"],
  data() {
    return {
      localPassword: this.password || "",
      localConfirmPassword: this.confirmPassword || "",
      passwordError: "",
      confirmPasswordError: "",
    };
  },
  watch: {
    localPassword(newVal) {
      this.$emit("update:password", newVal);
      if (this.passwordError) {
        this.validatePassword();
      }
    },
    localConfirmPassword(newVal) {
      this.$emit("update:confirmPassword", newVal);
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
      if (field === "password") {
        const passwordInput = document.getElementById("password");
        passwordInput.type =
          passwordInput.type === "password" ? "text" : "password";
      } else if (field === "confirm") {
        const confirmInput = document.getElementById("confirmPassword");
        confirmInput.type =
          confirmInput.type === "password" ? "text" : "password";
      }
    },
    validatePassword() {
      this.passwordError = "";

      if (!this.localPassword) {
        this.passwordError = "Password is required";
        return false;
      }

      if (this.localPassword.length < 10) {
        this.passwordError = "Password must be at least 10 characters long";
        return false;
      }

      const hasUppercase = /[A-Z]/.test(this.localPassword);
      const hasLowercase = /[a-z]/.test(this.localPassword);
      const hasNumber = /[0-9]/.test(this.localPassword);

      if (!hasUppercase || !hasLowercase || !hasNumber) {
        this.passwordError =
          "Password must include uppercase letters, lowercase letters, and numbers";
        return false;
      }

      return true;
    },
    validateConfirmPassword() {
      this.confirmPasswordError = "";

      if (!this.localConfirmPassword) {
        this.confirmPasswordError = "Please confirm your password";
        return false;
      }

      if (this.localPassword !== this.localConfirmPassword) {
        this.confirmPasswordError = "Passwords do not match";
        return false;
      }

      return true;
    },
    validateAndSubmit() {
      const passwordValid = this.validatePassword();
      const confirmValid = this.validateConfirmPassword();

      if (passwordValid && confirmValid) {
        const routeData = {
          ...this.$route.query,
          password: this.localPassword,
        }; // Add password to query
        this.$router.push({
          path: "/signin-owner",
          query: routeData, // Pass query parameters
        });

        // const { email, firstName, lastName, phone } = this.$route.query;
        // axios
        //   .post("http://localhost:8000/api/owners", {
        //     email,
        //     first_name: firstName,
        //     last_name: lastName,
        //     phone,
        //     password: this.localPassword,
        //   })
        //   .then((res) => {
        //     console.log("Successfully registered:", res.data);
        //     this.$router.push("/success"); // Or redirect to login
        //   })
        //   .catch((err) => {
        //     const message = err.response?.data?.message || "Failed to register";
        //     alert(message);
        //     console.error("Error:", err.response?.data || err);
        //   });
      }
    },
  },
};
</script>

<style scoped>
.step-container {
  max-width: 400px;
  margin: 4rem auto;
  padding: 2.5rem 2rem;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
  text-align: center;
  font-family: "Arial", sans-serif;
}

.step-container h2 {
  font-size: 1.8rem;
  margin-bottom: 0.5rem;
  color: #2e2e2e;
}

.step-container p {
  color: #666;
  margin-bottom: 1.5rem;
  line-height: 1.4;
}

.form-group {
  position: relative;
  margin-bottom: 0.25rem;
}

.form-group-password {
  margin-bottom: 1rem;
  text-align: left;
}

input[type="password"],
input[type="text"] {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-sizing: border-box;
  font-size: 1rem;
  margin-bottom: 1rem;
}

input.error-input {
  border-color: #e74c3c;
}

.eye-icon {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: #666;
}

button {
  display: block;
  width: 100%;
  padding: 0.85rem;
  background-color: #332c2b;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  margin-top: 0.5rem;
}

.error-message {
  color: #e74c3c;
  font-size: 0.85rem;
  margin-top: -0.5rem;
  margin-bottom: 1rem;
  text-align: left;
}

.copyright {
  margin-top: 1.5rem;
  font-size: 0.8rem;
  color: #9a9a9a;
}
</style>
