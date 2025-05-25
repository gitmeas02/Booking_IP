<template>
    <header class="flex justify-between items-center p-4 shadow bg-white">
        <RouterLink to="/"><img src="../../assets/logo.png" alt="Logo" class="h-10" /></RouterLink>
        <nav class="flex items-center gap-6">
            <CircleDollarSign/>
            <CircleHelp/>
            <ShoppingCart/>
            <RouterLink to="/owner-property"><button class="bg-[#4B2B2B] text-white px-4 py-2 rounded">Your Property</button></RouterLink>
            <RouterLink to="/authentication/signin" class="flex items-center gap-2 text-gray-700 hover:text-gray-900 bg-gray-100">
                <span class="hidden md:inline">Sign In</span>
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