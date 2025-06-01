<!-- OwnerApplicationForm.vue -->
<template>
  <form @submit.prevent="submitApplication" enctype="multipart/form-data" class="space-y-4 max-w-4xl mx-auto">
    <!-- Property Info -->
    <div>
      <h2 class="text-xl font-bold mb-2">Property Information</h2>
      <input v-model="form.property_type" placeholder="Property Type" class="input" required />
      <input v-model="form.fit_category" placeholder="Fit Category" class="input" required />
      <input v-model="form.property_name" placeholder="Property Name" class="input" required />
      <input v-model.number="form.stars" type="number" placeholder="Stars (optional)" class="input" />
    </div>

    <!-- Location -->
    <div>
      <h2 class="text-xl font-bold mb-2">Location</h2>
      <input v-model="form.location.street" placeholder="Street" class="input" required />
      <input v-model="form.location.floor" placeholder="Floor (optional)" class="input" />
      <input v-model="form.location.country" placeholder="Country" class="input" required />
      <input v-model="form.location.city" placeholder="City" class="input" required />
      <input v-model="form.location.zip_code" placeholder="Zip Code" class="input" required />
    </div>

    <!-- Amenities -->
    <div>
      <h2 class="text-xl font-bold mb-2">Amenities</h2>
      <div v-for="amenity in amenities" :key="amenity.id" class="flex items-center gap-2">
        <input type="checkbox" :value="amenity.id" v-model="form.amenities" />
        <img :src="amenity.icon_url" alt="icon" class="w-5 h-5" />
        <span>{{ amenity.amenity_name }}</span>
      </div>
    </div>

    <!-- Services -->
    <div>
      <h2 class="text-xl font-bold mb-2">Services</h2>
      <label><input type="checkbox" v-model="form.services.breakfast" /> Breakfast</label>
      <label><input type="checkbox" v-model="form.services.parking" /> Parking</label>
    </div>

    <!-- House Rules -->
    <div>
      <h2 class="text-xl font-bold mb-2">House Rules</h2>
      <input v-model="form.rules.checkin_from" placeholder="Check-in From" class="input" required />
      <input v-model="form.rules.checkin_to" placeholder="Check-in To" class="input" required />
      <input v-model="form.rules.checkout_from" placeholder="Check-out From" class="input" required />
      <input v-model="form.rules.checkout_to" placeholder="Check-out To" class="input" required />
      <label><input type="checkbox" v-model="form.rules.allow_pet" /> Allow Pets</label>
    </div>

    <!-- Photos -->
    <div>
      <h2 class="text-xl font-bold mb-2">Photos</h2>
      <input type="file" multiple accept="image/*" @change="handlePhotos" required />
    </div>

    <!-- Payment -->
    <div>
      <h2 class="text-xl font-bold mb-2">Payment Methods</h2>
      <label><input type="checkbox" v-model="form.payment.at_property" /> Pay at Property</label>
      <label><input type="checkbox" v-model="form.payment.online" /> Online Payment</label>
      <label><input type="checkbox" v-model="form.payment.pteas_khmer" /> Pteas Khmer</label>
    </div>

    <!-- Identity -->
    <div>
      <h2 class="text-xl font-bold mb-2">Identity</h2>
      <input v-model="form.identity.first_name" placeholder="First Name" class="input" required />
      <input v-model="form.identity.middle_name" placeholder="Middle Name" class="input" />
      <input v-model="form.identity.last_name" placeholder="Last Name" class="input" required />
      <input v-model="form.identity.first_name_id" placeholder="ID First Name" class="input" required />
      <input v-model="form.identity.middle_name_id" placeholder="ID Middle Name" class="input" />
      <input v-model="form.identity.last_name_id" placeholder="ID Last Name" class="input" required />
      <input v-model="form.identity.email" type="email" placeholder="Email" class="input" required />
      <input v-model="form.identity.phone" placeholder="Phone Number" class="input" required />
      <input v-model="form.identity.country" placeholder="Country" class="input" required />
      <input v-model="form.identity.address1" placeholder="Address Line 1" class="input" required />
      <input v-model="form.identity.address2" placeholder="Address Line 2" class="input" />
    </div>

    <button type="submit" class="btn">Submit Application</button>
  </form>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from '@/axios.js';

const form = ref({
  property_type: '',
  fit_category: '',
  property_name: '',
  stars: null,
  location: {
    street: '', floor: '', country: '', city: '', zip_code: ''
  },
  amenities: [],
  services: {
    breakfast: false, parking: false
  },
  rules: {
    checkin_from: '', checkin_to: '', checkout_from: '', checkout_to: '', allow_pet: false
  },
  payment: {
    at_property: false, online: false, pteas_khmer: false
  },
  identity: {
    first_name: '', middle_name: '', last_name: '',
    first_name_id: '', middle_name_id: '', last_name_id: '',
    email: '', phone: '', country: '', address1: '', address2: ''
  },
  photos: []
});

const amenities = ref([]);

const fetchAmenities = async () => {
  const res = await axios.get('/api/amenities');
  amenities.value = res.data.data; // uses the `data` array from status: success
};

const handlePhotos = (event) => {
  form.value.photos = Array.from(event.target.files);
};

const submitApplication = async () => {
  const formData = new FormData();
  for (const key in form.value) {
    if (key === 'photos') {
      form.value.photos.forEach(photo => formData.append('photos[]', photo));
    } else {
      formData.append(key, JSON.stringify(form.value[key]));
    }
  }

  try {
    const token = localStorage.getItem('token');
    await axios.post('/api/owner/apply', formData, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'multipart/form-data',
      }
    });
    alert('Application submitted successfully!');
  } catch (error) {
    console.error(error);
    alert('Submission failed.');
  }
};


onMounted(fetchAmenities);
</script>

<style scoped>
.input {
  display: block;
  width: 100%;
  padding: 0.5rem;
  margin-bottom: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.btn {
  background-color: #38b2ac;
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  cursor: pointer;
}
</style>
