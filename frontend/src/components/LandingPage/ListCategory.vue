<template>
    <div class="p-14 pt-8 w-screen">
        <h2 class="font-semibold text-2xl text-gray-800">Discover Your Favourite Stay</h2>
        <div class="flex overflow-x-auto space-x-4 py-4 scrollbar-none w-full" style="scrollbar-width: none; -ms-overflow-style: none;">
            <LandingCard
                v-for="(item, index) in categoryList"
                :key="index"
                :image="item.image"
                :label="item.label"
                class="flex-shrink-0 w-60"
            />
        </div>
    </div>
</template>

<script setup>
import LandingCard from './LandingCard.vue';
const categoryList = [
    {
        image: "https://i.pinimg.com/736x/e3/70/9d/e3709d99c1cedcf3c8511f7e2ee1d073.jpg",
        label: "Apartment"
    },
    {
        image: "https://i.pinimg.com/736x/24/b1/7c/24b17c44d5f60789e35956016d9dd13a.jpg",
        label: "Villa"
    },
    {
        image: "https://i.pinimg.com/736x/f4/b8/34/f4b834602bc12ea87dcfdb9fc587c02f.jpg",
        label: "Hotel"
    },
    {
        image: "https://i.pinimg.com/736x/38/e5/c7/38e5c784036d0d789c37a50e3e634432.jpg",
        label: "Resort"
    },
    {
        image: "https://i.pinimg.com/736x/b9/f1/12/b9f1122e8cf847f4d1066b95e6ec88e2.jpg",
        label: "Cabin"
    },
    {
        image: "https://i.pinimg.com/736x/8a/86/14/8a8614955d0bffbde8fdad3701ff8be2.jpg",
        label: "Vocation Home"
    },
    {
        image: "https://i.pinimg.com/736x/c3/a4/7d/c3a47d77d16f26ec68e29c25d4158e82.jpg",
        label: "Hostel"
    },
    {
        image: "https://i.pinimg.com/736x/4d/28/0e/4d280ec85ec88d30a524196a0026a8a6.jpg",
        label: "Homestay"
    },
    
]
</script>

<style scoped>

</style>