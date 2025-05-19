<template>
    <header class="flex justify-between items-center p-4 shadow bg-white">
        <img src="../../assets/logo.png" alt="Logo" class="h-10" />
        <nav class="flex items-center gap-6">
            <CircleDollarSign/>
            <CircleHelp/>
            <ShoppingCart/>
            <!-- // :to="isOwnerLoggedIn ? '/owner' : '/authentication/SignIn'" -->
            <RouterLink to="/setting"><button class="bg-[#4B2B2B] text-white px-4 py-2 rounded">Your Property</button></RouterLink>
            <RouterLink to="/authentication/signin">
            <User />
            </RouterLink>
        </nav>
    </header>
</template>

<script setup>
import {RouterLink} from 'vue-router'
import { User } from 'lucide-vue-next';
import { CircleDollarSign } from 'lucide-vue-next';
import { ShoppingCart } from 'lucide-vue-next';
import { CircleHelp } from 'lucide-vue-next';
import { onMounted, ref } from 'vue'
// import axios from '@/axios'
// const isAuthenticated = ref(false)
const destination = ref('/authentication/signin')
onMounted(async () => {
  // try {
  //   const res = await axios.get('/user') // backend check
  //   if (res?.data?.user) {
  //     isAuthenticated.value = true
  //     destination.value = '/setting'
  //   }
  // } catch (err) {
  //   console.warn('User not authenticated')
  //   isAuthenticated.value = false
  //   destination.value = '/authentication/signin'
  // }
})
</script>

<style scoped>
</style>