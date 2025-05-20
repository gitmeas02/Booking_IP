<template>
    <div class="max-w-screen-xl mx-auto px-4 py-10 font-sans">
        <!-- Heading -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-neutral-800">Services at your property</h2>
        </div>

        <!-- Form Section -->
        <div class="flex flex-col md:flex-row gap-10">
            <!-- Left Column -->
            <div class="flex flex-col gap-6 flex-1">
                <!-- Breakfast Block -->
                <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm space-y-4">
                    <div>
                        <h4 class="text-xl font-bold text-gray-800">Breakfast</h4>
                        <hr class="my-2" />
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-700">Do you serve guests breakfast?</p>
                    </div>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-gray-800">
                            <input type="radio" name="breakfast" value="yes" v-model="breakfast"
                                class="accent-blue-600" />
                            Yes
                        </label>
                        <label class="flex items-center gap-2 text-gray-800">
                            <input type="radio" name="breakfast" value="no" v-model="breakfast"
                                class="accent-blue-600" />
                            No
                        </label>
                    </div>
                </div>

                <!-- Parking Block -->
                <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm space-y-4">
                    <div>
                        <h4 class="text-xl font-bold text-gray-800">Parking</h4>
                        <hr class="my-2" />
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-700">Is parking available to guests?</p>
                    </div>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-gray-800">
                            <input type="radio" name="parking" value="yes" v-model="parking" class="accent-blue-600" />
                            Yes
                        </label>
                        <label class="flex items-center gap-2 text-gray-800">
                            <input type="radio" name="parking" value="no" v-model="parking" class="accent-blue-600" />
                            No
                        </label>
                    </div>
                </div>

                <!-- Price Block -->
                <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm space-y-4">
                    <div>
                        <h4 class="text-xl font-bold text-gray-800">Price</h4>
                        <hr class="my-2" />
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-700">Please set the price</p>
                    </div>
                    <div>
                        <input type="number" v-model="price"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter price in USD" />
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
            breakfast: '',
            parking: '',
            price: null,
        };
    },
};
</script>

<style scoped>
/* Add custom styles here if needed */
</style>
