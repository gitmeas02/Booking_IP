<template>
    <div class="p-14 pt-8 w-screen">
        <h2 class="font-semibold text-2xl text-gray-800">Discover Your Favourite Stay</h2>
        <div class="flex overflow-x-auto space-x-4 py-4 scrollbar-none w-full" style="scrollbar-width: none; -ms-overflow-style: none;">
            <LandingCard
                v-for="(item, index) in visibleCategories"
                :key="item.id"
                :image="item.image"
                :label="item.label"
                :loading="isLoading"
                class="flex-shrink-0 w-60"
                @click="handleCategoryClick(item)"
            />
        </div>
        
        <!-- Loading indicator for additional categories -->
        <div v-if="isLoading" class="flex justify-center items-center py-4">
            <div class="w-6 h-6 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import LandingCard from './LandingCard.vue';

const router = useRouter();
const isLoading = ref(false);
const loadedCount = ref(6); // Initially load 6 categories

// Optimized category list with local images and progressive loading
const categoryList = ref([
    {
        id: 1,
        image: "/src/assets/images/apartment.jpg",
        fallbackImage: "https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=400&h=250&fit=crop",
        label: "Apartment",
        type: "apartment"
    },
    {
        id: 2,
        image: "/src/assets/images/villa.jpg",
        fallbackImage: "https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=400&h=250&fit=crop",
        label: "Villa",
        type: "villa"
    },
    {
        id: 3,
        image: "/src/assets/images/hotel.jpg",
        fallbackImage: "https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&h=250&fit=crop",
        label: "Hotel",
        type: "hotel"
    },
    {
        id: 4,
        image: "/src/assets/images/resort.jpg",
        fallbackImage: "https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=400&h=250&fit=crop",
        label: "Resort",
        type: "resort"
    },
    {
        id: 5,
        image: "/src/assets/images/cabin.jpg",
        fallbackImage: "https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=400&h=250&fit=crop",
        label: "Cabin",
        type: "cabin"
    },
    {
        id: 6,
        image: "/src/assets/images/vacation-home.jpg",
        fallbackImage: "https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=400&h=250&fit=crop",
        label: "Vacation Home",
        type: "vacation_home"
    },
    {
        id: 7,
        image: "/src/assets/images/hostel.jpg",
        fallbackImage: "https://images.unsplash.com/photo-1555854877-bab0e460b1e5?w=400&h=250&fit=crop",
        label: "Hostel",
        type: "hostel"
    },
    {
        id: 8,
        image: "/src/assets/images/homestay.jpg",
        fallbackImage: "https://images.unsplash.com/photo-1586375300773-8384e3e4916f?w=400&h=250&fit=crop",
        label: "Homestay",
        type: "homestay"
    }
]);

// Computed property for visible categories (progressive loading)
const visibleCategories = computed(() => {
    return categoryList.value.slice(0, loadedCount.value);
});

// Handle category click with optimized navigation
const handleCategoryClick = (category) => {
    router.push({
        name: 'ListRoomPage',
        query: {
            category: category.type,
            type: category.label
        }
    });
};

// Progressive loading of categories
const loadMoreCategories = () => {
    if (loadedCount.value < categoryList.value.length) {
        isLoading.value = true;
        
        setTimeout(() => {
            loadedCount.value = Math.min(loadedCount.value + 3, categoryList.value.length);
            isLoading.value = false;
        }, 300);
    }
};

onMounted(() => {
    // Load more categories on intersection
    const observer = new IntersectionObserver(
        (entries) => {
            if (entries[0].isIntersecting) {
                loadMoreCategories();
            }
        },
        { threshold: 0.1 }
    );
    
    // Observe the component container
    const container = document.querySelector('.p-14.pt-8.w-screen');
    if (container) {
        observer.observe(container);
    }
});
</script>

<style scoped>
/* Smooth scrolling for horizontal scroll */
.overflow-x-auto {
    scroll-behavior: smooth;
}

/* Optimize scrollbar for performance */
.scrollbar-none::-webkit-scrollbar {
    display: none;
}

/* Loading animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.flex-shrink-0 {
    animation: fadeIn 0.3s ease-out;
}
</style>
