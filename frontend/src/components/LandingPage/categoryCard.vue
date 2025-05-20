<template>
    <div class="p-5">
        <div class="relative w-60 h-70 rounded-lg bg-amber-600 overflow-hidden transition delay-100 duration-300 ease-in-out hover:-translate-y-1 hover:scale-110">
            <img :src="image" alt="category" class="w-full h-full object-cover rounded-lg">
            <div class="absolute bottom-2 left-2">
                <p class="text-lg text-white font-bold ">{{label }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
    defineProps({
        image: String,
        label: String
    })
</script>

<style scoped>

</style>