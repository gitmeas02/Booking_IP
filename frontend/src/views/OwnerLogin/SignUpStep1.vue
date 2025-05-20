<template>
    <div class="max-w-md mx-auto my-16 p-8 bg-white rounded-lg shadow-xl text-center font-sans">
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Create Partner Account</h2>
        <p class="text-gray-600 mb-6">Create an account to list and manage your property.</p>

        <div class="form-group mb-4 text-left">
            <label class="block text-sm text-gray-700 mb-2">Email address</label>
            <input type="email" v-model="localEmail" placeholder="example@blabla.com"
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <button @click="goNext"
            class="w-full py-3 bg-gray-800 text-white rounded-lg font-medium text-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Continue
        </button>

        <hr class="divider" />
        <div class="footer">
            <p>
                Already have an account?
            </p>
            <router-link to="/signin-owner">Sign In</router-link>
        </div>

        <div class="copyright text-center text-xs text-gray-500 mt-6">
            <small>All rights reserved.</small>
            <small>Copyright (2025-2025) - Pteas Khmer</small>
        </div>
    </div>
</template>

<script>
export default {
    name: "SignUpStep1",
    data() {
        return {
            localEmail: "",
        };
    },
    methods: {
        goNext() {
            const routeData = this.$route.query ; // Safely copy existing query parameters
            const combinedDataStep1={
                email: this.localEmail
            }
            console.log("Combined Data:", combinedDataStep1);
            this.$router.push({ 
                path: '/signup-owner2', 
                query: combinedDataStep1
            });
          
        },
    },
};
</script>

<style scoped>
/* Tailwind CSS handles all the styling, no additional styles needed */
</style>
