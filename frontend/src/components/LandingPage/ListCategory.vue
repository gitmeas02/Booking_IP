<template>
    <div class="p-14 pt-8">
        <h2 class="font-semibold text-2xl text-gray-800">Discover Your Favourite Stay</h2>
        <div class="flex overflow-x-auto space-x-4 py-4 scrollbar-none" style="scrollbar-width: none; -ms-overflow-style: none;">
            <categoryCard
            v-for="(item, index) in categoryList"
            :key="index"
            :image="item.image"
            :label="item.label"
            class="flex-shrink-0 w-64"
            />
        </div>
    </div>
</template>

<script setup>
import categoryCard from './categoryCard.vue';
const categoryList = [
    // {
    //     image: "https://images.unsplash.com/photo-1633015500433-0079867c0211?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTR8fGFwYXJ0bWVudCUyMGFlc3RoZXRpY3xlbnwwfHwwfHx8MA%3D%3D",
    //     label: "Apartment"
    // },
    {
        image: "https://plus.unsplash.com/premium_photo-1682377521697-bc598b52b08a?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8dmlsbGF8ZW58MHx8MHx8fDA%3D",
        label: "Villa"
    },
    {
        image: "https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fGhvdGVsfGVufDB8fDB8fHww",
        label: "Hotel"
    },
    {
        image: "https://images.unsplash.com/photo-1651510689604-4d2d85daa479?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8a29oJTIwcm9uZ3xlbnwwfHwwfHx8MA%3D%3D",
        label: "Resort "
    },
    {
        image: "https://images.unsplash.com/photo-1651510689604-4d2d85daa479?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8a29oJTIwcm9uZ3xlbnwwfHwwfHx8MA%3D%3D",
        label: "Resort "
    },
    {
        image: "https://images.unsplash.com/photo-1651510689604-4d2d85daa479?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8a29oJTIwcm9uZ3xlbnwwfHwwfHx8MA%3D%3D",
        label: "Resort "
    },
    {
        image: "https://images.unsplash.com/photo-1651510689604-4d2d85daa479?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8a29oJTIwcm9uZ3xlbnwwfHwwfHx8MA%3D%3D",
        label: "Resort "
    },
    
]
</script>

<style scoped>

</style>