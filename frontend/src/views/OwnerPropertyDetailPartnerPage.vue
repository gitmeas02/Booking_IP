<template>
    <div class="max-w-screen-xl mx-auto px-4 py-10 font-sans">
        <!-- Heading -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-neutral-800">Partner Verification</h2>
        </div>

        <!-- Form Section -->
        <div class="flex flex-col md:flex-row gap-10">
            <!-- Left Column -->
            <div class="flex flex-col gap-6 flex-1">
                <!-- Ownership Block -->
                <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm space-y-4">
                    <div>
                        <p class="text-sm text-gray-700">
                            To comply with various legal and regulatory requirements, we need to collect and verify some
                            info about you and your property.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-800">
                            Is the accommodation owned by an individual or business entity?
                        </h4>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ownership Type</label>
                        <select
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 bg-white text-gray-800">
                            <option>I'm an individual running a business</option>
                            <option>Business entity</option>
                            <option>Other</option>
                        </select>
                    </div>
                </div>

                <!-- Owner Info Block -->
                <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm space-y-4">
                    <div>
                        <p class="text-sm text-gray-700">
                            Provide the full names and dates of birth of all individuals who own the accommodation.
                        </p>
                    </div>
                    <div class="flex flex-col gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="first-name">First
                                Name</label>
                            <input id="first-name" type="text" placeholder="First Name"
                                class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                                v-model="hotelName" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="last-name">Last
                                Name</label>
                            <input id="last-name" type="text" placeholder="Last Name"
                                class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                                v-model="hotelName" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="dob">Date of Birth</label>
                            <input id="dob" type="date"
                                class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 text-gray-800" />
                        </div>
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
            hotelName: '',
            starRating: '',
            isManaged: null
        }
    },
    methods: {
        handleBack() {
            // Handle going back
        },
        handleContinue() {
            // Handle continue
        }
    }
}
</script>

<style scoped>
/* Add any scoped custom styles here if needed */
</style>
