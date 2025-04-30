<template>
    <div class="hotel-listing">
        <div class="hotel-header">
            <div>
                <h1 class="hotel-name">KHUN TOTEL</h1>
                <p class="hotel-location">
                    Location: No 16-16, Street 258, Sangkat Chak Tomuk, Daun Penh, Phnom Penh, Cambodia
                </p>
            </div>
            <div class="review-score">
                <div class="score">8.1</div>
                <div class="score-text">Excellent</div>
            </div>
        </div>

        <div class="main-image-container">
            <img :src="images[currentImageIndex]" alt="Hotel room" class="main-image" />
            
            <div class="navigation-dots">
                <span 
                    v-for="(_, index) in images" 
                    :key="index" 
                    :class="['dot', { active: index === currentImageIndex }]"
                    @click="setCurrentImage(index)"
                ></span>
            </div>
        </div>

        <div class="thumbnail-gallery">
            <div 
                v-for="(image, index) in thumbnails" 
                :key="index" 
                class="thumbnail-container"
                @click="setCurrentImage(index)"
            >
                <img :src="image" alt="Hotel room thumbnail" class="thumbnail" />
                <div v-if="index === thumbnails.length - 1" class="photo-count">
                    <span>{{ totalPhotos }} photos</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

// Sample image URLs - replace with actual hotel images
const images = ref([
    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80',
    'https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80',
    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80',
]);

// Thumbnails - typically would be smaller versions of the main images
const thumbnails = ref([
    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80',
    'https://images.unsplash.com/photo-1595576508898-0ad5c879a061?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80',
    'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80',
    'https://images.unsplash.com/photo-1552902019-ebcd97aa9aa0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80',
]);

const currentImageIndex = ref(0);
const totalPhotos = ref(12); // Example total photo count

const setCurrentImage = (index) => {
    currentImageIndex.value = index;
};
</script>

<style scoped>
.hotel-listing {
    max-width: 1200px;
    margin: 0 auto;
    font-family: Arial, sans-serif;
    color: #333;
}

.hotel-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.hotel-name {
    font-size: 24px;
    font-weight: bold;
    margin: 0 0 5px 0;
    color: #333;
    text-transform: uppercase;
}

.hotel-location {
    font-size: 14px;
    margin: 0;
    color: #666;
}

.review-score {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #003580;
    color: white;
    padding: 8px 12px;
    border-radius: 5px;
    min-width: 45px;
    text-align: center;
}

.score {
    font-size: 20px;
    font-weight: bold;
}

.score-text {
    font-size: 12px;
}

.main-image-container {
    position: relative;
    width: 100%;
    height: 400px;
    overflow: hidden;
    border-radius: 8px;
    margin-bottom: 10px;
}

.main-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.navigation-dots {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
}

.dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    cursor: pointer;
    transition: background-color 0.3s;
}

.dot.active {
    background-color: white;
}

.thumbnail-gallery {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.thumbnail-container {
    position: relative;
    height: 120px;
    overflow: hidden;
    border-radius: 8px;
    cursor: pointer;
}

.thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.thumbnail-container:hover .thumbnail {
    transform: scale(1.05);
}

.photo-count {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 16px;
    font-weight: bold;
}

@media (max-width: 768px) {
    .hotel-header {
        flex-direction: column;
    }
    
    .review-score {
        margin-top: 10px;
        align-self: flex-start;
    }
    
    .main-image-container {
        height: 300px;
    }
    
    .thumbnail-gallery {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>