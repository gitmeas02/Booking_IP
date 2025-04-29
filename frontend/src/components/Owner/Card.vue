<template>
    <div class="card">
        <Icon :icon="icon" width="24" height="24" />
        <h2 class="title">{{ title }}</h2>
        <p class="description">{{ description }}</p>
        <button class="cta-button">List your properties</button>
    </div>
</template>

<script setup>
import { Icon } from '@iconify/vue';

defineProps({
    icon: {
        type: String,
        required: true
    },
    title: {
        type: String,
        required: true
    },
    description: {
        type: String,
        required: true
    }
});
</script>

<style scoped>
.card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center; /* Center content horizontally */
    max-width: 300px;
    padding: 24px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    background-color: white;
    font-family: sans-serif;
    height: 225px; /* Set a fixed height for the card */
}

.title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 12px;
}

.description {
    font-size: 14px;
    color: #555;
    margin-bottom: 20px;
}

.cta-button {
    background-color: #2d2d2d;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    margin-top: auto; /* Pushes button to the bottom */
}

.cta-button:hover {
    background-color: #1f1f1f;
}
</style>

