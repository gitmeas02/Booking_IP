<template>
    <div class="max-w-screen-xl mx-auto px-4 py-10 text-center font-sans">
        <h2 class="text-3xl font-extrabold text-neutral-800 mb-3">
            List your property and start welcoming guests in<br />
            <span class="text-neutral-800">no time!</span>
        </h2>

        <p class="text-gray-600 text-base mb-8">
            To get started, select the type of property you want to list.
        </p>

        <div class="grid gap-6 justify-items-center grid-cols-[repeat(auto-fit,minmax(260px,1fr))]">
            <Card v-for="(card, index) in cardData" :key="index" v-bind="card" />
        </div>
    </div>
</template>

<script setup>
import Card from '@/components/Owner/Card.vue';

const cardData = [
    {
        icon: 'material-symbols:apartment',
        title: 'Apartment',
        description: 'Furnished and self-catering accommodations where guests rent the entire place.'
    },
    {
        icon: 'material-symbols:house',
        title: 'Homes',
        description: 'Properties like apartments, vacation homes, villas, etc.'
    },
    {
        icon: 'material-symbols:hotel',
        title: 'Hotel and More',
        description: 'Properties like hotels, guest houses, hostels, condo hotels, etc.'
    },
    {
        icon: 'material-symbols:camping',
        title: 'Alternative Places',
        description: 'Properties like boats, campgrounds, luxury tents, etc.'
    }
];
</script>