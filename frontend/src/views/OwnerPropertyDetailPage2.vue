<template>
    <div class="max-w-screen-xl mx-auto px-4 py-10 font-sans">
        <!-- Heading -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-neutral-800">
                What can guests use at your hotel?
            </h2>
        </div>

        <!-- Form Section -->
        <div class="flex flex-col md:flex-row gap-10">
            <!-- Left Column -->
            <div class="flex-1 space-y-6">
                <!-- Form Box -->
                <div class="bg-white p-6 rounded-lg shadow-md space-y-8">
                    <!-- Checkboxes -->
                    <div class="flex flex-col gap-1">
                        <div v-for="(label, key) in amenityOptions" :key="key" class="flex gap-4">
                            <input type="checkbox" :id="key" v-model="amenities[key]" />
                            <label :for="key">{{ label }}</label>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons (still in left column but below the white box) -->
                <div class="flex justify-between">
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
            amenityOptions: {
                restaurant: "Restaurant",
                roomService: "Room Service",
                bar: "Bar",
                fitnessCenter: "Fitness Center",
                twentyFourHourFrontDesk: "24-Hour Front Desk",
                sauna: "Sauna",
                terrace: "Terrace",
                nonSmokingRoom: "Non-smoking Rooms",
                familyRoom: "Family Room",
                spa: "Spa",
                freeWifi: "Free Wi-Fi",
                swimmingPool: "Swimming Pool",
                airConditioning: "Air Conditioning",
                waterPark: "Water Park",
                beach: "Beach"
            },
            amenities: {
                restaurant: false,
                roomService: false,
                bar: false,
                fitnessCenter: false,
                twentyFourHourFrontDesk: false,
                sauna: false,
                terrace: false,
                nonSmokingRoom: false,
                familyRoom: false,
                spa: false,
                freeWifi: false,
                swimmingPool: false,
                airConditioning: false,
                waterPark: false,
                beach: false
            }
        }
    }
}
</script>
<style scoped></style>