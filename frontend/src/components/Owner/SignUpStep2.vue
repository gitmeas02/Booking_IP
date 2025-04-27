<template>
    <div class="step-container">
        <h2>Enter Your Details</h2>
        <p>Your full name and phone number are needed to ensure the security of your account.</p>
        <input type="text" v-model="localFirstName" placeholder="First Name" />
        <input type="text" v-model="localLastName" placeholder="Last Name" />
        <input type="text" v-model="localPhone" placeholder="Phone Number" />
        <button @click="goNext">Next</button>
    </div>
</template>

<script>
export default {
    name: "SignUpStep2",
    props: {
        firstName: String,
        lastName: String,
        phone: String,
    },
    emits: ['update:firstName', 'update:lastName', 'update:phone', 'next-step'],
    data() {
        return {
            localFirstName: this.firstName,
            localLastName: this.lastName,
            localPhone: this.phone,
        };
    },
    watch: {
        localFirstName(newVal) {
            this.$emit('update:firstName', newVal);
        },
        localLastName(newVal) {
            this.$emit('update:lastName', newVal);
        },
        localPhone(newVal) {
            this.$emit('update:phone', newVal);
        },
        firstName(newVal) {
            this.localFirstName = newVal;
        },
        lastName(newVal) {
            this.localLastName = newVal;
        },
        phone(newVal) {
            this.localPhone = newVal;
        },
    },
    methods: {
        goNext() {
            this.$emit('next-step');
        },
    },
};
</script>

<style scoped>
.step-container {
  max-width: 400px;
  margin: 4rem auto;                  /* center on page with top/bottom spacing */
  padding: 2.5rem 2rem;               /* roomy padding */
  background: #fff;                   /* white card */
  border-radius: 12px;                /* smooth corners */
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
  text-align: center;
  font-family: 'Arial', sans-serif;
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

/* full-width inputs with consistent spacing */
input[type="text"] {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-sizing: border-box;
  font-size: 1rem;
  margin-bottom: 1.5rem;
}

/* primary “Next” button */
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
}
</style>
