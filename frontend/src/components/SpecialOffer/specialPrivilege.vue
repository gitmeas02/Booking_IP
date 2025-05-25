<template>
    <div class="pl-14 pr-14 pt-8 w-screen ">
        <h2 class="font-semibold text-2xl text-gray-800 pb-6">UNLIMITED PROMOTION</h2>
        <div class=" flex flex-start pt-0" style="scrollbar-width: none; -ms-overflow-style: none;">
            <OurSpecial/>
             <div class="flex overflow-x-auto space-x-4 py-4 scrollbar-none w-full" style="scrollbar-width: none; -ms-overflow-style: none;">
                <LandingCard
                    v-for="(item, index) in couponList"
                    :key="index"
                    :image="item.image"
                    :label="item.label"
                    class="flex-shrink-0 w-60 ml-4"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>

<script setup>
import OurSpecial from './OurSpecial.vue'
import LandingCard from '../LandingPage/LandingCard.vue'
const couponList = [
    {
        image: "https://d1csarkz8obe9u.cloudfront.net/posterpreviews/hotel-discount-simple-design-template-e601042a09ee44da40a0468201b32d1d_screen.jpg?ts=1737781554",
        label: ""
    },
        {
        image: "https://d1csarkz8obe9u.cloudfront.net/posterpreviews/hotel-discount-simple-design-template-e601042a09ee44da40a0468201b32d1d_screen.jpg?ts=1737781554",
        label: ""
    },
       {
        image: "https://d1csarkz8obe9u.cloudfront.net/posterpreviews/hotel-discount-simple-design-template-e601042a09ee44da40a0468201b32d1d_screen.jpg?ts=1737781554",
        label: ""
    },
        {
        image: "https://d1csarkz8obe9u.cloudfront.net/posterpreviews/hotel-discount-simple-design-template-e601042a09ee44da40a0468201b32d1d_screen.jpg?ts=1737781554",
        label: ""
    },
    {
        image: "https://d1csarkz8obe9u.cloudfront.net/posterpreviews/hotel-discount-simple-design-template-e601042a09ee44da40a0468201b32d1d_screen.jpg?ts=1737781554",
        label: ""
    },
    {
        image: "https://d1csarkz8obe9u.cloudfront.net/posterpreviews/hotel-discount-simple-design-template-e601042a09ee44da40a0468201b32d1d_screen.jpg?ts=1737781554",
        label: ""
    },
  
]
</script>