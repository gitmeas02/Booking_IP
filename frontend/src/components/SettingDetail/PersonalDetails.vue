<template>
    <div>
        <div class="header">
            <div>
                <h2>Personal details</h2>
                <p>Update your info and find out how it’s used</p>
            </div>
            <img class="profile-pic" src="../../assets/images/4x6.JPG" alt="Profile" />
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
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.header h2 {
    margin: 0;
    font-size: 22px;
}

.header p {
    margin-top: 4px;
    color: #666;
    font-size: 14px;
}

.profile-pic {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
}
</style>