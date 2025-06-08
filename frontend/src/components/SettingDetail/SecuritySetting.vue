<template>
    <div class="p-4">
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-2">Security settings</h2>
            <p class="text-gray-600 text-sm">
                Change your security settings, set up secure authentication, or delete your account.
            </p>
        </div>

        <div class="border-t py-4 flex justify-between items-start gap-4">
            <div>
                <strong class="font-semibold">Two-factor authentication</strong>
                <p class="text-gray-600 text-sm mt-1">
                    Increase your account’s security by setting up two-factor authentication.
                </p>
            </div>
            <a href="#" class="text-blue-600 font-medium text-sm">Set up</a>
        </div>

        <div class="border-t py-4 flex justify-between items-start gap-4">
            <div>
                <strong class="font-semibold">Active sessions</strong>
                <p class="text-gray-600 text-sm mt-1">
                    Signing out will log you out from all devices except this one. This can take up to 10 minutes.
                </p>
            </div>
            <a href="#" class="text-blue-600 font-medium text-sm" @click="signOut" >Sign out</a>
        </div>

        <div class="border-t py-4 flex justify-between items-start gap-4">
            <div>
                <strong class="font-semibold">Delete account</strong>
                <p class="text-gray-600 text-sm mt-1">Permanently delete your account</p>
            </div>
            <a href="#" class="text-red-600 font-medium text-sm">Delete account</a>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()
const signOut = async () => {
  try {
    // Optional: Call backend logout endpoint to invalidate token
    // await axios.post('http://localhost:8100/api/logout', {}, {
    //   withCredentials: true
    // })

    // Remove token from localStorage
    localStorage.removeItem('token')

    // Redirect to login page or homepage
   router.push({ name: 'Homepage' }) 
  } catch (err) {
    console.error('Sign out failed:', err)
    router.push('/') 
    alert('Sign out failed. Check console.')
  }
}
</script>

<style scoped>
/* Tailwind CSS handles the layout, no need for additional custom CSS */
</style>