<template>
    <div class="p-14 pt-8">
        <h2 class="font-semibold text-2xl text-gray-800 pb-6">UNLIMITED PROMOTION</h2>
        <div class="flex justify-start items-center ">
            <OurSpecial/>
            <categoryCard
            v-for="(item, index) in couponList"
            :key="index"
            :image="item.image"
            :label="item.label"
            class="flex-shrink-0 w-64"
            />
        </div>
    </div>
</template>

<style scoped>

</style>

<script setup>
import OurSpecial from './OurSpecial.vue'
import CategoryCard from '../LandingPage/categoryCard.vue'
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
]
</script>