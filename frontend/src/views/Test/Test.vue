<template>
  <form @submit.prevent="submitApplication" class="max-w-3xl mx-auto p-6 bg-white rounded shadow space-y-6">
    <!-- Error message if API Base URL is undefined -->
    <div v-if="!apiBaseUrl" class="text-red-600 font-semibold mt-2 mb-4">
      Error: API Base URL is not defined. Please check your .env file.
    </div>

    <!-- Property Type -->
    <div>
      <label class="font-semibold mb-1 block" for="property_type">Property Type *</label>
      <input id="property_type" v-model="form.property_type" required type="text" class="input" placeholder="e.g. Apartment, Home" />
    </div>

    <!-- Fit Category -->
    <div>
      <label class="font-semibold mb-1 block" for="fit_category">Fit Category *</label>
      <input id="fit_category" v-model="form.fit_category" required type="text" class="input" placeholder="e.g. Hotel, Condo" />
    </div>

    <!-- Property Name -->
    <div>
      <label class="font-semibold mb-1 block" for="property_name">Property Name *</label>
      <input id="property_name" v-model="form.property_name" required type="text" class="input" />
    </div>

    <!-- Stars -->
    <div>
      <label class="font-semibold mb-1 block" for="stars">Stars (optional)</label>
      <input id="stars" v-model.number="form.stars" type="number" min="0" max="5" class="input" />
    </div>

    <!-- Location group -->
    <fieldset class="border p-4 rounded space-y-4">
      <legend class="font-semibold">Location *</legend>

      <div>
        <label for="street" class="block mb-1">Street *</label>
        <input id="street" v-model="form.location.street" required type="text" class="input" />
      </div>

      <div>
        <label for="floor" class="block mb-1">Floor (optional)</label>
        <input id="floor" v-model="form.location.floor" type="text" class="input" />
      </div>

      <div>
        <label for="country" class="block mb-1">Country *</label>
        <input id="country" v-model="form.location.country" required type="text" class="input" />
      </div>

      <div>
        <label for="city" class="block mb-1">City *</label>
        <input id="city" v-model="form.location.city" required type="text" class="input" />
      </div>

      <div>
        <label for="zip_code" class="block mb-1">Zip Code *</label>
        <input id="zip_code" v-model="form.location.zip_code" required type="text" class="input" />
      </div>
    </fieldset>

    <!-- Amenities -->
    <div>
      <label class="block mb-1 font-semibold">Amenities (Select multiple)</label>
      <select multiple v-model="form.amenities" class="input">
        <option v-for="amenity in amenitiesList" :key="amenity.id" :value="amenity.id">{{ amenity.name }}</option>
      </select>
    </div>

    <!-- Services -->
    <fieldset class="border p-4 rounded space-y-3">
      <legend class="font-semibold">Services *</legend>
      <label class="inline-flex items-center space-x-2">
        <input type="checkbox" v-model="form.services.breakfast" />
        <span>Breakfast</span>
      </label>
      <label class="inline-flex items-center space-x-2">
        <input type="checkbox" v-model="form.services.parking" />
        <span>Parking</span>
      </label>
    </fieldset>

    <!-- House Rules -->
    <fieldset class="border p-4 rounded space-y-3">
      <legend class="font-semibold">House Rules *</legend>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="checkin_from" class="block mb-1">Check-in From *</label>
          <input id="checkin_from" type="time" v-model="form.rules.checkin_from" required class="input" />
        </div>

        <div>
          <label for="checkin_to" class="block mb-1">Check-in To *</label>
          <input id="checkin_to" type="time" v-model="form.rules.checkin_to" required class="input" />
        </div>

        <div>
          <label for="checkout_from" class="block mb-1">Check-out From *</label>
          <input id="checkout_from" type="time" v-model="form.rules.checkout_from" required class="input" />
        </div>

        <div>
          <label for="checkout_to" class="block mb-1">Check-out To *</label>
          <input id="checkout_to" type="time" v-model="form.rules.checkout_to" required class="input" />
        </div>
      </div>

      <label class="inline-flex items-center space-x-2 mt-2">
        <input type="checkbox" v-model="form.rules.allow_pet" />
        <span>Allow Pets</span>
      </label>
    </fieldset>

    <!-- Photos upload -->
    <fieldset class="border p-4 rounded space-y-3">
      <legend class="font-semibold">Photos (Min 4) *</legend>
      <div
        @dragover.prevent="dragActive = true"
        @dragleave.prevent="dragActive = false"
        @drop="handleDrop"
        :class="[
          'border-2 border-dashed rounded p-6 text-center cursor-pointer',
          dragActive ? 'border-blue-500 bg-blue-50' : 'border-gray-300',
        ]"
        @click="fileInput.click()"
      >
        <input type="file" multiple class="hidden" ref="fileInput" @change="handleFiles" accept="image/*" />
        <p class="mb-2 text-gray-600">Drag & drop images here or <span class="text-blue-600 underline">browse</span></p>
        <p v-if="form.photos.length" class="font-semibold text-gray-700">Selected photos:</p>
        <ul class="list-disc list-inside text-sm text-gray-700">
          <li v-for="(photo, i) in form.photos" :key="i">{{ photo.name }}</li>
        </ul>
      </div>
    </fieldset>

    <!-- Payment -->
    <fieldset class="border p-4 rounded space-y-3">
      <legend class="font-semibold">Payment Options *</legend>
      <label class="inline-flex items-center space-x-2">
        <input type="checkbox" v-model="form.payment.at_property" />
        <span>Credit Card at Property</span>
      </label>
      <label class="inline-flex items-center space-x-2">
        <input type="checkbox" v-model="form.payment.online" />
        <span>Online Payment</span>
      </label>
      <label class="inline-flex items-center space-x-2">
        <input type="checkbox" v-model="form.payment.pteas_khmer" />
        <span>Use Platform Payments</span>
      </label>
    </fieldset>

    <!-- Identity -->
    <fieldset class="border p-4 rounded space-y-3">
      <legend class="font-semibold">Identity *</legend>

      <div class="grid grid-cols-3 gap-4">
        <input
          v-model="form.identity.first_name"
          required
          placeholder="First Name"
          class="input"
          type="text"
        />
        <input
          v-model="form.identity.middle_name"
          placeholder="Middle Name"
          class="input"
          type="text"
        />
        <input
          v-model="form.identity.last_name"
          required
          placeholder="Last Name"
          class="input"
          type="text"
        />
      </div>

      <div class="grid grid-cols-3 gap-4">
        <input
          v-model="form.identity.first_name_id"
          required
          placeholder="First Name (ID)"
          class="input"
          type="text"
        />
        <input
          v-model="form.identity.middle_name_id"
          placeholder="Middle Name (ID)"
          class="input"
          type="text"
        />
        <input
          v-model="form.identity.last_name_id"
          required
          placeholder="Last Name (ID)"
          class="input"
          type="text"
        />
      </div>

      <input
        v-model="form.identity.email"
        required
        placeholder="Email"
        type="email"
        class="input"
      />
      <input
        v-model="form.identity.phone"
        required
        placeholder="Phone"
        type="text"
        class="input"
      />
      <input
        v-model="form.identity.country"
        required
        placeholder="Country"
        type="text"
        class="input"
      />
      <input
        v-model="form.identity.address1"
        required
        placeholder="Address line 1"
        type="text"
        class="input"
      />
      <input
        v-model="form.identity.address2"
        placeholder="Address line 2 (optional)"
        type="text"
        class="input"
      />
    </fieldset>

    <!-- Debug Info -->
    <div class="bg-gray-100 p-4 rounded text-sm">
      <p><strong>Debug Info:</strong></p>
      <p>API Base URL: {{ apiBaseUrl || 'undefined' }}</p>
      <p>Full URL: {{ apiBaseUrl ? `${apiBaseUrl}/owner/apply` : 'undefined/owner/apply' }}</p>
    </div>

    <!-- Submit Button -->
    <button
      type="submit"
      :disabled="loading || !isValid || !apiBaseUrl"
      class="w-full py-3 bg-blue-600 text-white font-semibold rounded disabled:opacity-50"
    >
      {{ loading ? 'Submitting...' : 'Submit Application' }}
    </button>

    <!-- Error / Success Messages -->
    <p v-if="error" class="text-red-600 font-semibold mt-2">{{ error }}</p>
    <p v-if="success" class="text-green-600 font-semibold mt-2">{{ success }}</p>
  </form>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import axios from 'axios'

const dragActive = ref(false)
const fileInput = ref(null)
const loading = ref(false)
const error = ref('')
const success = ref('')

// Get API base URL and add debug logging
const apiBaseUrl = import.meta.env.VITE_API_BASE_URL
console.log('Environment variables:', import.meta.env)
console.log('API Base URL:', apiBaseUrl)

// Example amenities list, replace with real data from your API or props
const amenitiesList = [
  { id: 1, name: 'WiFi' },
  { id: 2, name: 'Pool' },
  { id: 3, name: 'Gym' },
  { id: 4, name: 'Air Conditioning' },
]

const form = reactive({
  property_type: '',
  fit_category: '',
  property_name: '',
  stars: null,
  location: {
    street: '',
    floor: '',
    country: '',
    city: '',
    zip_code: '',
  },
  amenities: [],
  services: {
    breakfast: false,
    parking: false,
  },
  rules: {
    checkin_from: '',
    checkin_to: '',
    checkout_from: '',
    checkout_to: '',
    allow_pet: false,
  },
  photos: [], // File[]
  payment: {
    at_property: false,
    online: false,
    pteas_khmer: false,
  },
  identity: {
    first_name: '',
    middle_name: '',
    last_name: '',
    first_name_id: '',
    middle_name_id: '',
    last_name_id: '',
    email: '',
    phone: '',
    country: '',
    address1: '',
    address2: '',
  },
})

function handleFiles(e) {
  const files = e.target.files
  for (let i = 0; i < files.length; i++) {
    form.photos.push(files[i])
  }
}

function handleDrop(e) {
  dragActive.value = false
  const files = e.dataTransfer.files
  for (let i = 0; i < files.length; i++) {
    form.photos.push(files[i])
  }
}

const isValid = computed(() => {
  // Minimal validation example, extend as needed
  return (
    form.property_type.trim() &&
    form.fit_category.trim() &&
    form.property_name.trim() &&
    form.location.street.trim() &&
    form.location.country.trim() &&
    form.location.city.trim() &&
    form.location.zip_code.trim() &&
    form.rules.checkin_from &&
    form.rules.checkin_to &&
    form.rules.checkout_from &&
    form.rules.checkout_to &&
    form.photos.length >= 4 &&
    form.identity.first_name.trim() &&
    form.identity.last_name.trim() &&
    form.identity.first_name_id.trim() &&
    form.identity.last_name_id.trim() &&
    form.identity.email.trim() &&
    form.identity.phone.trim() &&
    form.identity.country.trim() &&
    form.identity.address1.trim() &&
    (form.services.breakfast || form.services.parking) && // At least one service?
    (form.payment.at_property || form.payment.online || form.payment.pteas_khmer)
  )
})
const token =localStorage.getItem('token');
console.log(token)
async function submitApplication() {
  if (!apiBaseUrl) {
    error.value = 'API configuration error: VITE_API_BASE_URL is not defined. Please check your .env file.'
    return
  }

  if (!isValid.value) {
    error.value = 'Please fill in all required fields and upload at least 4 photos.'
    return
  }

  error.value = ''
  loading.value = true
  success.value = ''

  try {
    // Because photos are files, use FormData
    const formData = new FormData()
       formData.append('property_type', form.property_type)
    formData.append('fit_category', form.fit_category)
    formData.append('property_name', form.property_name)
    if (form.stars !== null && form.stars !== '') {
      formData.append('stars', form.stars.toString())
    }

    // Nested location fields
    for (const [key, value] of Object.entries(form.location)) {
      if (value) {
        formData.append(`location[${key}]`, value)
      }
    }

    // Amenities array (send each as amenities[] to be parsed as array on backend)
    form.amenities.forEach((amenityId) => {
      formData.append('amenities[]', amenityId)
    })

    formData.append('location[street]', form.location.street);
    if (form.location.floor) formData.append('location[floor]', form.location.floor);
    formData.append('location[country]', form.location.country);
    formData.append('location[city]', form.location.city);
    formData.append('location[zip_code]', form.location.zip_code);

    // Services
    formData.append('services[breakfast]', form.services.breakfast ? '1' : '0');
    formData.append('services[parking]', form.services.parking ? '1' : '0');

    // Rules
    formData.append('rules[checkin_from]', form.rules.checkin_from);
    formData.append('rules[checkin_to]', form.rules.checkin_to);
    formData.append('rules[checkout_from]', form.rules.checkout_from);
    formData.append('rules[checkout_to]', form.rules.checkout_to);
    formData.append('rules[allow_pet]', form.rules.allow_pet ? '1' : '0');

    // Payment
    formData.append('payment[at_property]', form.payment.at_property ? '1' : '0');
    formData.append('payment[online]', form.payment.online ? '1' : '0');
    formData.append('payment[pteas_khmer]', form.payment.pteas_khmer ? '1' : '0');

    // Identity
    formData.append('identity[first_name]', form.identity.first_name);
    if (form.identity.middle_name) formData.append('identity[middle_name]', form.identity.middle_name);
    formData.append('identity[last_name]', form.identity.last_name);
    formData.append('identity[first_name_id]', form.identity.first_name_id);
    if (form.identity.middle_name_id) formData.append('identity[middle_name_id]', form.identity.middle_name_id);
    formData.append('identity[last_name_id]', form.identity.last_name_id);
    formData.append('identity[email]', form.identity.email);
    formData.append('identity[phone]', form.identity.phone);
    formData.append('identity[country]', form.identity.country);
    formData.append('identity[address1]', form.identity.address1);
    if (form.identity.address2) formData.append('identity[address2]', form.identity.address2);

    // Append amenities as array items (not JSON string)
    form.amenities.forEach((amenity, idx) => {
      formData.append(`amenities[${idx}]`, amenity);
    });

    // Append photos files as array items
    form.photos.forEach((file, idx) => {
      formData.append(`photos[${idx}]`, file);
    });
    const token = localStorage.getItem('token');
    const response = await axios.post(`${apiBaseUrl}/owner/apply`, formData, {
      headers: { 
        'Content-Type': 'multipart/form-data',
        'Authorization': `Bearer ${token}`, 
      }
    });

    success.value = 'Application submitted successfully!'
    console.log('Success:', response.data)
  } catch (e) {
  console.error('Submission error:', e);
  if (e.response) {
    console.error('Response data:', e.response.data);
    console.error('Response status:', e.response.status);
    console.error('Response headers:', e.response.headers);
    error.value = e.response.data.message || e.response.data.errors?.join(', ') || 'Submission failed';
  } else if (e.request) {
    error.value = 'No response received from server. Check if the backend is running.';
  } else {
    error.value = `Request setup error: ${e.message}`;
  }
}finally {
    loading.value = false
  }
}
</script>

<style scoped>
.input {
  border: 1px solid #d1d5db; /* border-gray-300 */
  border-radius: 0.375rem;    /* rounded */
  padding: 0.5rem 0.75rem;    /* py-2 px-3 */
  width: 100%;                /* w-full */
  box-sizing: border-box;
}
</style>