<template>
  <div class="min-h-screen bg-gray-100 p-10 font-sans md:p-5">
    <!-- Profile section -->
    <div class="flex items-center mb-10 md:flex-col md:items-start">
      <img src="../assets/images/4x6.JPG" alt="Profile"
        class="w-16 h-16 rounded-full object-cover mr-4 md:mr-0 md:mb-2" />
      <div>
        <h2 class="text-2xl font-bold m-0">Hi, MengHour</h2>
        <p class="text-gray-600 mt-1">Member since 2024</p>
      </div>
    </div>

    <!-- Cards Grid -->
    <div
      class="grid gap-3 grid-cols-[repeat(auto-fill,minmax(380px,1fr))] md:grid-cols-1 sm:grid-cols-1 lg:grid-cols-2">
      <Card v-for="(card, index) in cards" :key="index" :title="card.title" :items="card.items" />
    </div>
  </div>
</template>

<script setup>
import Card from '../components/Card.vue'

const cards = [
  {
    title: 'Payment Info',
    items: [
      { label: 'Rewards & Wallet', icon: 'mdi:wallet' },
      { label: 'Payment methods', icon: 'ri:bank-card-line' }
    ]
  },
  {
    title: 'Manage account',
    items: [
      { label: 'Personal details', icon: 'ic:baseline-person' },
      { label: 'Security Settings', icon: 'ri:lock-2-line' },
      { label: 'Other travelers', icon: 'ri:user-shared-line' }
    ]
  },
  {
    title: 'Preferences',
    items: [
      { label: 'Customization preferences', icon: 'ri:settings-3-line' },
      { label: 'Email preferences', icon: 'ic:baseline-email' }
    ]
  },
  {
    title: 'Travel activity',
    items: [
      { label: 'Trips and bookings', icon: 'mdi:calendar-check' },
      { label: 'Saved Lists', icon: 'mdi:bookmark-outline' },
      { label: 'My reviews', icon: 'mdi:comment-text-outline' }
    ]
  },
  {
    title: 'Help and Support',
    items: [
      { label: 'Contact Customer Service', icon: 'ri:customer-service-2-line' },
      { label: 'Safety resource center', icon: 'ri:shield-keyhole-line' },
      { label: 'Dispute resolution', icon: 'ri:exchange-dollar-line' }
    ]
  },
  {
    title: 'Legal and Privacy',
    items: [
      { label: 'Privacy and Policy', icon: 'ri:shield-check-line' },
      { label: 'Content guidelines', icon: 'ri:article-line' }
    ]
  },
  {
    title: 'Manage your property',
    items: [{ label: 'List your property', icon: 'mdi:home-plus-outline' }]
  }
]
</script>
