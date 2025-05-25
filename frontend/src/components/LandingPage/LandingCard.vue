<template>
    <!-- <div class="p-4"> -->
        <div
            class="relative w-60 h-[17.5rem] rounded-lg bg-amber-600 overflow-hidden transition-transform duration-300 ease-in-out hover:-translate-y-1 hover:scale-110 shadow-lg"
            role="button"
            tabindex="0"
            @click="handleClick"
        >
            <img
                :src="image"
                :alt="label"
                class="w-full h-full object-cover rounded-lg"
            />
            <div class="absolute bottom-2 left-2 bg-transparent bg-opacity-50 px-2 py-1 rounded">
                <p class="text-lg text-blue-50 font-bold">{{ label }}</p>
            </div>
        </div>
    <!-- </div> -->
</template>

<script setup>
defineProps({
    image: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
});

const handleClick = () => {
    console.log(`Category clicked: ${label}`);
};
</script>

<style scoped>
/* Add any additional styles if needed */
</style>