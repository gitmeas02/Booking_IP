<template>
    <div class="max-w-screen-xl mx-auto px-4 py-10 text-center font-sans">
        <h2 class="text-3xl font-extrabold text-neutral-800 mb-3">
            From the list below, which property category is<br />
            <span class="text-neutral-800">the best fit for your place?</span>
        </h2>

        <div class="grid gap-6 justify-items-center grid-cols-[repeat(auto-fit,minmax(260px,1fr))]">
            <CategoryCard v-for="(card, index) in cardData" :key="index" v-bind="card" />
        </div>
    </div>
</template>

<script setup>
import CategoryCard from '@/components/Owner/CategoryCard.vue'
import { ref } from 'vue'

const cardData = ref([
    {
        title: 'Hotel',
        description: 'Accommodations for travelers often with restaurants, meeting rooms and other guest services.'
    },
    {
        title: 'Guesthouse',
        description: 'Private home with separate living facilities for host and guest.'
    },
    {
        title: 'Bed and breakfast',
        description: 'Private home offering overnight stays and breakfast.'
    },
    {
        title: 'Homestay',
        description: 'A shared home where the guest has a private room and the host lives and is on site/'
    },
    {
        title: 'Hostel',
        description: 'Budget accommodations with mostly dorm-style beds and social atmosphere.'
    },
    {
        title: 'Condo hotel',
        description: 'Independent apartments with some hotel facilities like a front desk.'
    },
    {
        title: 'Country house',
        description: 'Private home in the countryside with simple accommodations.'
    },
    {
        title: 'Farm stay',
        description: 'Private farm with simple accommodations'
    }
])
</script>