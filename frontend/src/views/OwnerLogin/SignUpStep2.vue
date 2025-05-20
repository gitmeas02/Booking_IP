<template>
    <div class="max-w-md mx-auto my-16 p-8 bg-white rounded-lg shadow-xl text-center font-sans">
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Enter Your Details</h2>
        <p class="text-gray-600 mb-6">Your full name and phone number are needed to ensure the security of your account.
        </p>

        <div class="form-group mb-4 text-left">
            <label class="block text-sm text-gray-700 mb-2">First Name</label>
            <input type="text" v-model="localFirstName" placeholder="First Name"
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div class="form-group mb-4 text-left">
            <label class="block text-sm text-gray-700 mb-2">Last Name</label>
            <input type="text" v-model="localLastName" placeholder="Last Name"
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div class="form-group mb-4 text-left">
            <label class="block text-sm text-gray-700 mb-2">Phone Number</label>
            <input type="text" v-model="localPhone" placeholder="Phone Number"
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <button @click="goNext"
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
    name: "SignUpStep2",
    data() {
        return {
            localFirstName: "",
            localLastName: "",
            localPhone: "",
        };
    },
    methods: {
        goNext() {
            const routeData = this.$route.query; // Get data from route
            const combinedData = {
                ...routeData,
                firstName: this.localFirstName,
                lastName: this.localLastName,
                phone: this.localPhone,
            };
            console.log("Combined Data:", combinedData);
            this.$router.push({ 
                path: '/signup-owner3', 
                query: combinedData 
            });
        },
    },
};
</script>

<style scoped>
/* Tailwind CSS handles all the styling, no additional styles needed */
</style>
