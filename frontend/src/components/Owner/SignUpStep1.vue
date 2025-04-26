<template>
    <div class="step-container">
        <h2>Create Partner Account</h2>
        <p>Create an account to list and manage your property.</p>
        <input type="email" v-model="localEmail" placeholder="example@blabla.com" />
        <button @click="goNext">Continue</button>
        <p class="signin-link">
            Already have an account?
            <router-link to="/signin">Sign In</router-link>
        </p>
    </div>
</template>

<script>
export default {
    name: "SignUpStep1",
    props: {
        email: String,
    },
    emits: ['update:email', 'next-step'],
    data() {
        return {
            localEmail: this.email,
        };
    },
    watch: {
        localEmail(newVal) {
            this.$emit('update:email', newVal);
        },
        email(newVal) {
            this.localEmail = newVal;
        }
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

.signin-link {
    margin-top: 1rem;
}
</style>