<template>
  <div class="dashboard">
    <!-- Profile section -->
    <div class="profile">
      <div class="profile-left">
        <img src="../assets/images/4x6.JPG" alt="Profile" class="profile-image" />
        <div>
          <h2 class="profile-name">Hi, {{ user?.name || 'Guest' }}</h2>
          <p class="profile-member">Member since 2024</p>
        </div>
      </div>
      <button class="logout-button" @click="logout">Logout</button>
    </div>

    <!-- Cards Grid -->
    <div class="cards-grid">
      <Card v-for="(card, index) in cards" :key="index" :title="card.title" :items="card.items" />
    </div>
  </div>
</template>

<script setup>
import Card from '@/components/SettingCard.vue'
import { ref, onMounted } from 'vue'
import axios from 'axios'

const cards = [
  {
    title: 'Payment Info',
    items: [
      { label: 'Rewards & Wallet', icon: 'mdi:wallet', route: '/wallet' },
      { label: 'Payment methods', icon: 'ri:bank-card-line', route: '/payments' }
    ]
  },
  {
    title: 'Manage account',
    items: [
      { label: 'Personal details', icon: 'ic:baseline-person', route: '/setting-details' },
      { label: 'Security Settings', icon: 'ri:lock-2-line', route: '/setting-details' },
      { label: 'Other travelers', icon: 'ri:user-shared-line', route: '/setting-details' }
    ]
  },
  {
    title: 'Preferences',
    items: [
      { label: 'Customization preferences', icon: 'ri:settings-3-line', route: '/preferences/customization' },
      { label: 'Email preferences', icon: 'ic:baseline-email', route: '/preferences/email' }
    ]
  },
  {
    title: 'Travel activity',
    items: [
      { label: 'Trips and bookings', icon: 'mdi:calendar-check', route: '/trips' },
      { label: 'Saved Lists', icon: 'mdi:bookmark-outline', route: '/saved' },
      { label: 'My reviews', icon: 'mdi:comment-text-outline', route: '/reviews' }
    ]
  },
  {
    title: 'Help and Support',
    items: [
      { label: 'Contact Customer Service', icon: 'ri:customer-service-2-line', route: '/support/contact' },
      { label: 'Safety resource center', icon: 'ri:shield-keyhole-line', route: '/support/safety' },
      { label: 'Dispute resolution', icon: 'ri:exchange-dollar-line', route: '/support/disputes' }
    ]
  },
  {
    title: 'Legal and Privacy',
    items: [
      { label: 'Privacy and Policy', icon: 'ri:shield-check-line', route: '/legal/privacy' },
      { label: 'Content guidelines', icon: 'ri:article-line', route: '/legal/content' }
    ]
  },
  {
    title: 'Manage your property',
    items: [
      { label: 'List your property', icon: 'mdi:home-plus-outline', route: '/property/list' }
    ]
  }
]

const user = ref(null)
const error = ref('')
const API_BASE_URL = 'http://localhost:8100'

const getUser = async () => {
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      throw new Error('No token is found')
    }
    const response = await axios.get(`${API_BASE_URL}/api/me`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    user.value = response.data.user
  } catch (error) {
    console.error('Failed to fetch user', error)
    error.value = 'Failed to fetch user data.'
  }
}

const logout = () => {
  localStorage.removeItem('token')
  window.location.href = '/login'
}

onMounted(() => {
  getUser()
})
</script>

<style scoped>

.profile {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 40px;
}

.profile-left {
  display: flex;
  align-items: center;
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

.logout-button {
  padding: 8px 16px;
  background-color: #d9534f;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s;
}

.logout-button:hover {
  background-color: #c9302c;
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

  .profile-left {
    margin-bottom: 10px;
  }

  .cards-grid {
    grid-template-columns: 1fr;
  }
}

@media (min-width: 769px) and (max-width: 1024px) {
  .cards-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
