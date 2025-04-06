<template>
    <div class="dashboard">
        <!-- Profile section -->
        <div class="profile">
            <img src="../assets/images/4x6.JPG" alt="Profile" class="profile-image" />
            <div>
                <h2 class="profile-name">Hi, MengHour</h2>
                <p class="profile-member">Member since 2024</p>
            </div>
        </div>

        <!-- Cards Grid -->
        <div class="cards-grid">
            <Card title="Payment Info" :items="['Rewards & Wallet', 'Payment methods']" />
            <Card title="Manage account" :items="['Personal details', 'Security Settings', 'Other travelers']" />
            <Card title="Preferences" :items="['Customization preferences', 'Email preferences']" />
            <Card title="Travel activity" :items="['Trips and bookings', 'Saved Lists', 'My reviews']" />
            <Card title="Help and Support"
                :items="['Contact Customer Service', 'Safety resource center', 'Dispute resolution']" />
            <Card title="Legal and Privacy" :items="['Privacy and Policy', 'Content guidelines']" />
            <Card title="Manage your property" :items="['List your property']" />
        </div>
    </div>
</template>

<script setup>
import Card from '../components/Card.vue'
</script>

<style scoped>
.dashboard {
    padding: 40px;
    background-color: #f4f4f4;
    font-family: sans-serif;
    min-height: 100vh;
}

.profile {
    display: flex;
    align-items: center;
    margin-bottom: 40px;
}

.profile-image {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 16px;
}

.profile-name {
    font-size: 24px;
    font-weight: bold;
    margin: 0;
}

.profile-member {
    color: #666;
    margin: 4px 0 0;
}

.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 12px;
}

@media (max-width: 768px) {
    .dashboard {
        padding: 20px;
    }

    .profile {
        flex-direction: column;
        align-items: flex-start;
    }

    .profile-image {
        margin-bottom: 10px;
    }

    .cards-grid {
        grid-template-columns: 1fr;
        /* Stack cards vertically on small screens */
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .cards-grid {
        grid-template-columns: repeat(2, 1fr);
        /* Two cards per row on tablets */
    }
}
</style>