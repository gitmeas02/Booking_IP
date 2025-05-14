<template>
    <div class="max-w-screen-xl mx-auto px-4 py-10 font-sans">
        <!-- Heading -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-neutral-800">Invoicing</h2>
        </div>

        <!-- Form Section -->
        <div class="flex flex-col md:flex-row gap-10">
            <!-- Left Column -->
            <div class="flex-1 space-y-6">
                <!-- Form Card -->
                <div class="bg-white p-6 rounded-lg shadow-md space-y-8">
                    <!-- Hotel Name -->
                    <div>
                        <p class="text-sm text-gray-600 mt-1">
                            At the beginning of each month, we'll send you an invoice for all complete bookings in the
                            previous month.
                        </p>
                    </div>

                    <!-- Name -->
                    <div>
                        <h4 class="text-xl font-semibold mb-2">
                            What name should be on the invoice?
                        </h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2">
                                <input type="radio" />
                                Khun Meas
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" />
                                Khun Hotel
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" />
                                Alo Alo
                            </label>
                        </div>
                    </div>
                    <hr>
                    <!-- Info -->
                    <div>
                        <h4 class="text-xl font-semibold mb-2">
                            Does this recrpient have the same address as your property?
                        </h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2">
                                <input type="radio" />
                                Yes
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" />
                                No
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Buttons moved OUTSIDE the white box -->
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

            <!-- Right Column -->
            <div class="flex-1">
                <!-- Optional content -->
            </div>
        </div>
    </div>
</template>
<script>
export default {

}
</script>
<style scoped></style>