<template>
    <div class="max-w-screen-xl mx-auto px-4 py-10 font-sans">
        <!-- Heading -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-neutral-800">Payment</h2>
        </div>

        <!-- Form Section -->
        <div class="flex flex-col md:flex-row gap-10">
            <!-- Left Column -->
            <div class="flex flex-col gap-6 flex-1">
                <!-- Breakfast Block -->
                <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm space-y-4">
                    <div>
                        <h4 class="text-xl font-bold text-gray-800">How can your guests pay for their stay</h4>
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-start gap-2 text-gray-800">
                            <input type="radio" name="breakfast" value="yes" v-model="breakfast"
                                class="mt-1 accent-blue-600" />
                            <span>
                                Online, when they make a reservation, PteasKhmer will facilitate your guest's payment
                                with the Payments service.
                            </span>
                        </label>
                        <label class="flex items-start gap-2 text-gray-800">
                            <input type="radio" name="breakfast" value="no" v-model="breakfast"
                                class="mt-1 accent-blue-600" />
                            <span>
                                By Credit card at my property.
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Parking Block -->
                <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm space-y-4">
                    <div>
                        <h4 class="text-xl font-bold text-gray-800">How payments by PteasKhmer works</h4>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700">1. Your guest pays through PteasKhmer.com with options</p>
                        <p class="text-sm text-gray-700">2. We facilitate your guest's payment. You don't have to deal
                            with fraud, chargebacks, or invalid cards.</p>
                        <p class="text-sm text-gray-700">3. PteasKhmer.com sends payouts to you. You'll receive a bank
                            transfer by the 15th of each month.</p>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between pt-4">
                    <button
                        class="px-6 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        @click="handleBack">
                        Back
                    </button>
                    <button
                        class="px-6 py-2 text-white bg-black rounded-md shadow-md hover:bg-gray-300 hover:text-black focus:outline-none focus:ring-2 focus:ring-amber-400"
                        @click="handleContinue" :disabled="!hotelName || !starRating || isManaged === null">
                        Continue
                    </button>
                </div>
            </div>

            <!-- Right Column Placeholder -->
            <div class="flex-1">
                <!-- Optional preview/image content -->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            breakfast: null,
            hotelName: '',
            starRating: '',
            isManaged: null
        };
    },
    methods: {
        handleBack() {
            // Handle back navigation
        },
        handleContinue() {
            // Handle continue navigation
        }
    }
}
</script>

<style scoped>
/* Optional scoped styles */
</style>
