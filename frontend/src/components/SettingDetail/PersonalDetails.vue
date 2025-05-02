<template>
    <div>
        <div class="flex justify-between items-center mb-8 gap-4 flex-wrap">
            <div>
                <h2 class="text-2xl font-semibold mb-1">Personal details</h2>
                <p class="text-gray-600 text-sm">Update your info and find out how it’s used</p>
            </div>
            <img class="w-12 h-12 rounded-full object-cover" src="../../assets/images/4x6.JPG" alt="Profile" />
        </div>

        <DetailRow label="Name" value="KhunMeas" />
        <DetailRow label="Display name" value="Choose a display name" />
        <DetailRow label="Email address" value="khunmeas@gmail.com"
            note="This is the email address you use to sign in. It’s also where we send your booking confirmations." />
        <DetailRow label="Phone number" value="Add your phone number"
            note="Properties or attractions you book will use this number if they need to contact you." />
        <DetailRow label="Date of birth" value="Enter your date of birth" />
        <DetailRow label="Nationality" value="Select the country/region you’re from" />
        <DetailRow label="Gender" value="Select your gender" />
        <DetailRow label="Address" value="Add your address" />
        <DetailRow label="Passport details" value="Not provided" />
    </div>
</template>

<script setup>
import DetailRow from './DetailRow.vue'
</script>

<style scoped>
/* Tailwind already covers styles, so no additional custom styles needed */
</style>