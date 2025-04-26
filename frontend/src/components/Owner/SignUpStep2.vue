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