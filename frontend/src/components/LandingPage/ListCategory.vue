<template>
  <div class="p-14 pt-8 w-screen">
    <h2 class="font-semibold text-2xl text-gray-800">Discover Your Favourite Stay</h2>
    <div
      class="flex overflow-x-auto space-x-4 py-4 scrollbar-none w-full"
      style="scrollbar-width: none; -ms-overflow-style: none;"
    >
      <LandingCard
        v-for="(item, index) in categoryList"
        :key="index"
        :image="item.image"
        :label="item.label"
        class="flex-shrink-0 w-60"
        @click="handleCardClick"
      />
    </div>
  </div>
</template>

<script setup>
import { useRouter } from "vue-router";
import LandingCard from "./LandingCard.vue";

const router = useRouter();

const categoryList = [
  { image: "https://images.unsplash.com/photo-1633015500433-0079867c0211?w=500", label: "Apartment" },
  { image: "https://plus.unsplash.com/premium_photo-1682377521697-bc598b52b08a?w=500", label: "Villa" },
  { image: "https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=500", label: "Hotel" },
  { image: "https://images.unsplash.com/photo-1651510689604-4d2d85daa479?w=500", label: "Resort" },
  { image: "https://images.unsplash.com/photo-1651510689604-4d2d85daa479?w=500", label: "Cabin" },
  { image: "https://images.unsplash.com/photo-1651510689604-4d2d85daa479?w=500", label: "Vocation Home" },
  { image: "https://images.unsplash.com/photo-1651510689604-4d2d85daa479?w=500", label: "Hostel" },
  { image: "https://images.unsplash.com/photo-1651510689604-4d2d85daa479?w=500", label: "Homestay" },
];

const handleCardClick = (data) => {
  router.push({
    path: "/listroom",
    query: {
      category: data.label,
    },
  });
};
</script>
