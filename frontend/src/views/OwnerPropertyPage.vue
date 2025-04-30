<template>
    <div class="container">
        <h2 class="headline">
            List your property and start welcoming guests in<br />
            <span class="highlight">no time!</span>
        </h2>

        <p class="subtext">
            To get started, select the type of property you want to list.
        </p>

        <div class="card-grid">
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
        description:
            'Furnished and self-catering accommodations where guests rent the entire place.'
    },
    {
        icon: 'material-symbols:house',
        title: 'Homes',
        description: 'Properties like apartments, vacation homes, villas, etc.'
    },
    {
        icon: 'material-symbols:hotel',
        title: 'Hotel and More',
        description:
            'Properties like hotels, guest houses, hostels, condo hotels, etc.'
    },
    {
        icon: 'material-symbols:camping',
        title: 'Alternative Places',
        description: 'Properties like boats, campgrounds, luxury tents, etc.'
    }
];
</script>

<style scoped>
.container {
    max-width: 1200px;
    margin: auto;
    padding: 40px 16px;
    text-align: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.headline {
    font-size: 32px;
    font-weight: 800;
    color: #2d2d2d;
    margin-bottom: 12px;
}

.highlight {
    color: #2d2d2d;
}

.subtext {
    color: #666;
    font-size: 16px;
    margin-bottom: 32px;
}

.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 24px;
    justify-items: center;
}
</style>