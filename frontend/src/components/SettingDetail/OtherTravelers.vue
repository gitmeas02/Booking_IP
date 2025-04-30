<template>
    <div class="container-1">
        <div class="group">
            <h1 class="title">Other travelers</h1>
            <p class="subtitle">Add or edit info about the people you’re traveling with.</p>
        </div>
        <button class="add-button">
            <span class="icon">+</span>
            <span>Add new traveler</span>
        </button>
    </div>

</template>

<script setup>
// Add any interactivity here later if needed
</script>

<style scoped>
.container-1 {
    padding: 0;
    background-color: #ffffff;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.group {
    display: flex;
    flex-direction: column;
}

.title {
    font-size: 1.75rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.subtitle {
    color: #6b7280;
    margin-bottom: 1.5rem;
}

.add-button {
    height: 2.5rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background-color: #2563eb;
    color: white;
    padding: 0.5rem 1.25rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
    align-self: flex-end;
}

.add-button:hover {
    background-color: #1e40af;
}

.icon {
    font-size: 1rem;
}

/* Responsive styles for small screens */
@media (max-width: 640px) {
    .add-button {
        align-self: stretch;
        /* full width */
        justify-content: center;
        font-size: 16px;
    }

    .title {
        font-size: 1.5rem;
    }

    .subtitle {
        font-size: 14px;
    }
}
</style>