<template>
    <div class="max-w-xl mx-auto mt-10 p-6 border rounded-lg shadow">
        <h1 class="text-2xl font-semibold mb-4">Apply for Owner Role</h1>

        <button :disabled="loading" @click="submitApplication"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50">
            {{ loading ? 'Applying...' : 'Apply as Owner' }}
        </button>

        <p v-if="successMessage" class="mt-4 text-green-600">{{ successMessage }}</p>
        <p v-if="errorMessage" class="mt-4 text-red-600">{{ errorMessage }}</p>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const formData = {
    property_type: 'Apartment',
    fit_category: 'Luxury',
    property_name: 'My Property',
    identity: {
        is_individual_owner: true,
        first_name: 'John',
        last_name: 'Doe',
        email: 'john@example.com',
        phone: '012345678'
    }
};

const token = localStorage.getItem('token') // or wherever you store it

const submitApplication = async () => {
    loading.value = true
    successMessage.value = ''
    errorMessage.value = ''

    try {
        const response = await axios.post(
            'http://localhost:8100/api/owner/apply',
            formData,
            {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        )
        successMessage.value = response.data.message || 'Application submitted successfully.'
    } catch (error) {
        if (error.response) {
            errorMessage.value = error.response.data.message || 'Application failed.'
        } else {
            errorMessage.value = 'Network error.'
        }
    } finally {
        loading.value = false
    }
}
</script>


<style scoped>
/* Optional: Add styles here if needed */
</style>
