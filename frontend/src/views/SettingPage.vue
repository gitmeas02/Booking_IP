<template>
    <div class="dashboard">
      <!-- Profile section -->
      <div class="profile">
        <img src="../assets/images/4x6.JPG" alt="Profile" class="profile-image" />
        <div>
          <h2 class="profile-name">Hi,  {{user?.name|| 'Guest' }}</h2>
          <p class="profile-member">Member since 2024</p>
        </div>
      </div>
  
      <!-- Cards Grid -->
      <div class="cards-grid">
        <Card v-for="(card, index) in cards" :key="index" :title="card.title" :items="card.items" />
      </div>
    </div>
  </template>
  

<script setup>
import Card from '@/components/SettingCard.vue'

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
import { ref, onMounted } from 'vue';
  import axios from 'axios';
  
  const user = ref(null);
  const error = ref('');
  const API_BASE_URL = 'http://localhost:8100';
  
  const getUser = async () => {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('No token is found');
      }
      const response = await axios.get(
        `${API_BASE_URL}/api/me`,
        {
          headers: {
            Authorization: `Bearer ${token}`
          }
        }
      );
      user.value = response.data.user;
      console.log('User data:', user.value);
    } catch (error) {
      console.error('Failed to fetch user', error);
      error.value = 'Failed to fetch user data.';
    }
  };
  
  onMounted(() => {
    getUser();
  });
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