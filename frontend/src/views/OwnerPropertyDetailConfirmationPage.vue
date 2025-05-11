<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-50">
        <div class="w-full max-w-5xl p-6 space-y-6 font-sans">
            <!-- Step 1 -->
            <div class="bg-white p-5 rounded-lg shadow flex items-start justify-between">
                <div class="flex items-start gap-4">
                    <div class="mt-1">
                        <Icon icon="mdi:check-circle" class="text-green-500 w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500">Step 1</h3>
                        <h2 class="text-lg font-bold text-gray-900">Property details</h2>
                        <p class="text-sm text-gray-600">
                            The basics. Add your property name, address, facilities, and more.
                        </p>
                    </div>
                </div>
                <button class="text-blue-600 font-medium hover:underline">Edit</button>
            </div>

            <!-- Step 2 -->
            <div class="bg-white p-5 rounded-lg shadow flex items-start justify-between">
                <div class="flex items-start gap-4">
                    <div class="mt-1">
                        <Icon icon="mdi:image-outline" class="text-gray-700 w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500">Step 2</h3>
                        <h2 class="text-lg font-bold text-gray-900">Photos</h2>
                        <p class="text-sm text-gray-600">
                            Share some photos of your property so guests know what to expect.
                        </p>
                    </div>
                </div>
                <button
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 text-sm font-medium">
                    Add photos
                </button>
            </div>

            <!-- Step 3 -->
            <div class="bg-white p-5 rounded-lg shadow flex items-start justify-between">
                <div class="flex items-start gap-4">
                    <div class="mt-1">
                        <Icon icon="mdi:credit-card-outline" class="text-gray-700 w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500">Step 3</h3>
                        <h2 class="text-lg font-bold text-gray-900">Final steps</h2>
                        <p class="text-sm text-gray-600">
                            Set up payments and invoicing before you open for bookings.
                        </p>
                    </div>
                </div>
                <button
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 text-sm font-medium">
                    Add final details
                </button>
            </div>

            <!-- Final Button -->
            <div class="pt-4">
                <button
                    class="w-full bg-blue-600 text-white text-lg font-semibold px-8 py-3 rounded-md hover:bg-blue-700 transition duration-200">
                    Complete registration
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Icon } from '@iconify/vue';
</script>
