<template>
    <div class="max-w-screen-xl mx-auto px-4 py-10 font-sans">
        <!-- Heading -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-neutral-800">House Rules</h2>
        </div>

        <!-- Form Section -->
        <div class="flex flex-col md:flex-row gap-10">
            <!-- Left Column -->
            <div class="flex-1 space-y-6">
                <!-- Form Card -->
                <div class="bg-white p-6 rounded-lg shadow-md space-y-8">
                    <!-- Check-in/out Time -->
                    <div>
                        <h4 class="text-xl font-semibold mb-4">What are your check-in and check-out time?</h4>

                        <!-- Check-In -->
                        <div class="mb-6">
                            <h5 class="text-lg font-medium mb-2">Check-In</h5>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">From</label>
                                    <input type="time" v-model="checkInFrom"
                                        class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500" />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Until</label>
                                    <input type="time" v-model="checkInUntil"
                                        class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500" />
                                </div>
                            </div>
                        </div>

                        <!-- Check-Out -->
                        <div>
                            <h5 class="text-lg font-medium mb-2">Check-Out</h5>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">From</label>
                                    <input type="time" v-model="checkOutFrom"
                                        class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500" />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">Until</label>
                                    <input type="time" v-model="checkOutUntil"
                                        class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr />

                    <!-- Children & Pet Policy -->
                    <div class="space-y-6">
                        <!-- Children -->
                        <div>
                            <h4 class="text-xl font-semibold mb-2">Do you allow children?</h4>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="children_allowed" value="yes" v-model="allowChildren" />
                                    Yes
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="children_allowed" value="no" v-model="allowChildren" />
                                    No
                                </label>
                            </div>
                        </div>

                        <!-- Pets -->
                        <div>
                            <h4 class="text-xl font-semibold mb-2">Do you allow pets?</h4>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="pets_allowed" value="yes" v-model="allowPets" />
                                    Yes
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="pets_allowed" value="no" v-model="allowPets" />
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between">
                    <button
                        class="px-6 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        @click="handleBack">
                        Back
                    </button>
                    <button
                        class="px-6 py-2 text-white bg-black rounded-md shadow-md hover:bg-gray-300 hover:text-black focus:outline-none focus:ring-2 focus:ring-amber-400"
                        @click="handleContinue"
                        :disabled="!checkInFrom || !checkOutUntil || allowChildren === null || allowPets === null">
                        Continue
                    </button>
                </div>
            </div>

            <!-- Right Column -->
            <div class="flex-1">
                <!-- Optional content -->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            checkInFrom: '',
            checkInUntil: '',
            checkOutFrom: '',
            checkOutUntil: '',
            allowChildren: null,
            allowPets: null
        };
    },
    methods: {
        handleBack() {
            console.log("Back clicked");
            // Add back navigation logic here
        },
        handleContinue() {
            console.log("Continue clicked");
            // Add continue/submit logic here
        }
    }
};
</script>

<style scoped>
/* You can add extra custom styles here if needed */
</style>
