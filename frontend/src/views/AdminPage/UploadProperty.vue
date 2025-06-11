<template>
    <div class="container">
        <div class="property-card">
            <!-- Header -->
            <div class="header">
                <h1>Manager Hotel</h1>
                <div class="user-icon"></div>
            </div>

            <!-- Navigation -->
            <div class="flex">
                <button class=" flex items-center h-fit py-1 px-2 underline" @click="goBack">
                    <Icon icon="ep:arrow-left-bold"></Icon>
                    <p>Back</p>
                </button>
                <div class="navigation">
                    <button class="nav-button">Home</button>
                </div>
            </div>


            <!-- Photo Upload Section -->
            <div class="upload-area" @dragover.prevent="onDragOver" @drop.prevent="onDrop" @click="triggerFileInput">
                <div class="upload-content">
                    <p>Click or Drag & Drop to Upload Your Photo</p>
                    <input ref="fileInput" type="file" accept="image/*" multiple @change="handleFileUpload"
                        class="file-input" />
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="tab-nav">
                <div class="tab active" @click="toggleEditMode">
                    {{ isEditing ? 'Save' : 'Edit' }}
                </div>
            </div>

            <!-- Photo List Mode -->
            <div v-if="isEditing" class="photo-list-edit">
                <div v-for="(photo, index) in photos" :key="index" class="photo-list-item">
                    <img :src="photo.url" alt="Room photo" class="photo-thumb" />
                    <div class="photo-buttons">
                        <button class="btn delete" @click="deletePhoto(index)">Delete</button>
                        <button class="btn update" @click="updatePhoto(index)">Update</button>
                    </div>
                </div>

                <!-- Hidden input for updating -->
                <input ref="updatePhotoInput" type="file" accept="image/*" @change="handlePhotoUpdate"
                    class="file-input" />
            </div>

            <!-- Grid View Mode -->
            <div v-else class="photo-gallery">
                <template :key="index" v-for="(photo, index) in displayedPhotos">
                    <div class="photo-container" @click="openPhotoModal(index)">
                        <img :src="photo.url" alt="Room photo" class="room-photo">
                        <div v-if="index === 4 && remainingPhotoCount > 0" class="photo-count">
                            {{ remainingPhotoCount }}+ Photos
                        </div>
                    </div>
                </template>
            </div>

            <!-- Form Controls -->
            <div class="form-layout">
                <div class="form-column">
                    <!-- Input Fields -->
                    <input type="text" placeholder="Input room number" class="form-input" v-model="roomNumber">
                    <select class="form-select" v-model="roomType">
                        <option disabled value="">select type room</option>
                        <option value="standard">Standard</option>
                        <option value="deluxe">Deluxe</option>
                        <option value="suite">Suite</option>
                    </select>

                    <!-- Price Control -->
                    <div class="price-control">
                        <label>Set price</label>
                        <div class="price-input">
                            <button @click="decreasePrice" class="price-button">−</button>
                            <div class="price-display">${{ price }}</div>
                            <button @click="increasePrice" class="price-button">+</button>
                        </div>
                    </div>

                    <!-- Hotel Selection -->
                    <select class="form-select" v-model="selectedHotel">
                        <option disabled value="">Select hotel</option>
                        <option v-for="hotel in hotels" :key="hotel.id" :value="hotel.id">{{ hotel.name }}</option>
                    </select>
                </div>

                <!-- Facilities Checkboxes -->
                <div class="facilities-section">
                    <p class="facilities-title">Facilities</p>
                    <div class="facilities-grid">
                        <div v-for="(facility, index) in facilities" :key="index" class="facility-item">
                            <input type="checkbox" :id="`facility-${index}`" v-model="facility.selected">
                            <label :for="`facility-${index}`">{{ facility.name }}</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Area -->
            <div class="description-container">
                <textarea placeholder="description" class="description-input" v-model="description"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="button-container">
                <button class="host-button">Host Property</button>
            </div>
        </div>

        <!-- Modal Viewer -->
        <div v-if="modalVisible" class="modal-overlay" @click.self="closeModal">
            <div class="modal-content">
                <button class="modal-close" @click="closeModal">
                    <Icon icon="charm:cross" />
                </button>
                <button class="modal-nav left" @click="prevPhoto" :disabled="currentPhotoIndex === 0">
                    <Icon icon="mingcute:left-fill" />
                </button>
                <img :src="photos[currentPhotoIndex].url" class="modal-image" />
                <button class="modal-nav right" @click="nextPhoto" :disabled="currentPhotoIndex === photos.length - 1">
                    <Icon icon="mingcute:right-fill" />
                </button>
            </div>
        </div>

    </div>
</template>

<script>

import { ref, computed } from 'vue';
import { Icon } from '@iconify/vue';

export default {
    name: 'UploadProperty',
    components: {
        Icon,
    },

    // data() {
    //     return {
    //         icons: {
    //             "Air Conditioning": "material-symbols:air",
    //             "TV": "material-symbols:tv",
    //             "Mini Bar": "mdi:glass-cocktail",
    //             "Free Breakfast": "material-symbols:fastfood-outline-rounded",
    //             "Free Wi-Fi": "material-symbols:wifi",
    //             "Hot Water": "material-symbols:shower-outline-rounded",
    //             "Phone Call": "material-symbols:deskphone-outline",
    //         }

    //     };

    // },

    methods:{
        goBack() {
            this.$router.back(); // Go to previous route
        }
    },

    setup() {
        // ========================
        // Reactive Variables
        // ========================
        const roomNumber = ref('');
        const roomType = ref('');
        const price = ref(77);
        const selectedHotel = ref('');
        const description = ref('');
        const isEditing = ref(false);
        const updatePhotoInput = ref(null);
        const photoIndexToUpdate = ref(null);
        const fileInput = ref(null);
        const modalVisible = ref(false);
        const currentPhotoIndex = ref(0);

        // ========================
        // Data Sources
        // ========================
        const photos = ref([
            { url: '/Bed/bed.png' },
            { url: '/Bed/bed.png' },
            { url: '/Bed/bed.png' },
            { url: '/Bed/bed.png' },
            { url: '/Bed/bed.png' },
            { url: '/Bed/bed.png' },
            { url: '/Bed/bed.png' },
        ]);

        const hotels = ref([
            { id: 1, name: 'Grand Hotel' },
            { id: 2, name: 'City Center Hotel' },
            { id: 3, name: 'Beach Resort' },
        ]);

        const facilities = ref(Array.from({ length: 32 }, () => ({
            name: 'Hot water',
            selected: false,
        })));

        // ========================
        // Computed Properties
        // ========================
        const displayedPhotos = computed(() => photos.value.slice(0, 5));
        const remainingPhotoCount = computed(() => photos.value.length - 5);

        // ========================
        // Event Handlers
        // ========================
        const goToEditIfMore = (index) => {
            if (index === 4 && remainingPhotoCount.value > 0) {
                isEditing.value = true;
            }
        };

        const increasePrice = () => {
            price.value += 1;
        };

        const decreasePrice = () => {
            if (price.value > 0) price.value -= 1;
        };

        const triggerFileInput = () => {
            fileInput.value?.click();
        };

        const handleFileUpload = (event) => {
            const files = Array.from(event.target.files);
            previewImages(files);
        };

        const onDrop = (event) => {
            const files = Array.from(event.dataTransfer.files);
            previewImages(files);
        };

        const onDragOver = () => {
            // Optional: handle UI feedback
        };

        const previewImages = (files) => {
            isEditing.value = true;
            files.forEach((file) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        photos.value.push({ url: e.target.result });
                    };
                    reader.readAsDataURL(file);
                }
            });
        };

        const toggleEditMode = () => {
            isEditing.value = !isEditing.value;
        };

        const updatePhoto = (index) => {
            photoIndexToUpdate.value = index;
            updatePhotoInput.value?.click();
        };

        const handlePhotoUpdate = (event) => {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (photoIndexToUpdate.value !== null) {
                        photos.value[photoIndexToUpdate.value].url = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        };

        const deletePhoto = (index) => {
            photos.value.splice(index, 1);
        };

        const openPhotoModal = (index) => {
            currentPhotoIndex.value = index;
            modalVisible.value = true;
        };

        const closeModal = () => {
            modalVisible.value = false;
        };

        const nextPhoto = () => {
            if (currentPhotoIndex.value < photos.value.length - 1) {
                currentPhotoIndex.value++;
            }
        };

        const prevPhoto = () => {
            if (currentPhotoIndex.value > 0) {
                currentPhotoIndex.value--;
            }
        };

        return {
            roomNumber,
            roomType,
            price,
            selectedHotel,
            description,
            isEditing,
            updatePhotoInput,
            photoIndexToUpdate,
            fileInput,
            modalVisible,
            currentPhotoIndex,
            photos,
            hotels,
            facilities,
            displayedPhotos,
            remainingPhotoCount,
            goToEditIfMore,
            increasePrice,
            decreasePrice,
            triggerFileInput,
            handleFileUpload,
            onDrop,
            onDragOver,
            toggleEditMode,
            updatePhoto,
            handlePhotoUpdate,
            deletePhoto,
            openPhotoModal,
            closeModal,
            nextPhoto,
            prevPhoto,
        };
    },
};
</script>



<style scoped>
/* ========================
   Layout and Typography
======================== */
.container {
    max-width: 1440px;
    margin: 0 auto;
    padding: 60px;
    font-family: Arial, sans-serif;
}

.property-card {
    background-color: white;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

/* ========================
   Header Section
======================== */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.header h1 {
    font-size: 20px;
    font-weight: bold;
    margin: 0;
}

.user-icon {
    width: 24px;
    height: 24px;
    background-color: #f4d742;
    border-radius: 50%;
}

/* ========================
   Navigation Tabs/Buttons
======================== */
.nav-button {
    border: 1px solid #ccc;
    background: white;
    padding: 5px 15px;
    font-size: 14px;
    margin-bottom: 20px;
    cursor: pointer;
}

.nav-button:hover {
    background-color: #f5f5f5;
}

.tab {
    display: inline-block;
    padding: 5px 15px;
    background-color: #b71c1c;
    color: white;
    font-size: 14px;
    cursor: pointer;
    margin-bottom: 15px;
}

/* ========================
   Upload Area
======================== */
.upload-area {
    border: 2px dashed #ccc;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    cursor: pointer;
    background-color: #fafafa;
    transition: background-color 0.2s ease;
}

.upload-area:hover {
    background-color: #f0f0f0;
}

.upload-content {
    text-align: center;
    color: #777;
    font-size: 14px;
}

.file-input {
    display: none;
}

/* ========================
   Edit Mode Photo List
======================== */
.photo-list-edit {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}

.photo-list-item {
    display: flex;
    align-items: center;
    gap: 20px;
}

.photo-thumb {
    width: 120px;
    height: 90px;
    object-fit: cover;
    border: 1px solid #ccc;
    border-radius: 3px;
}

.photo-buttons {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 6px 12px;
    font-size: 14px;
    border: none;
    border-radius: 3px;
    color: white;
    cursor: pointer;
}

.btn.delete {
    background-color: black;
}

.btn.update {
    background-color: #6e2c2c;
}

/* ========================
   Grid View Photo Gallery
======================== */
.photo-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.photo-container {
    position: relative;
    flex: 1 1 calc(33.333% - 10px);
    max-width: calc(33.333% - 10px);
}

/* Larger grid items for 4th photo onward */
.photo-gallery .photo-container:nth-child(n+4) {
    flex: 1 1 calc(50% - 10px);
    max-width: calc(50% - 10px);
}

.room-photo {
    width: 100%;
    height: 250px;
    object-fit: cover;
}

.photo-count {
    position: absolute;
    width: 100%;
    height: 97%;
    bottom: 7px;
    /* left: 0; */
    background-color: rgba(128, 128, 128, 0.6);
    color: white;
    /* padding: 3px 8px; */
    font-size: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ========================
   Form Section
======================== */
.form-layout {
    display: flex;
    align-items: start;
    justify-content: space-between;
    flex-direction: row;
    margin: 10px 50px;
}

@media (max-width: 768px) {
    .form-layout {
        grid-template-columns: 1fr;
    }
}

.form-column {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-input,
.form-select {
    display: flex;
    align-items: center;
    text-align: center;
    /* padding: 8px; */
    width: 360px;
    height: 60px;
    border: 1px solid #ccc;
    border-radius: 3px;
    font-size: 20px;
}

/* ========================
   Price Control Component
======================== */
.price-control {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.price-input {
    display: flex;
    align-items: center;
    height: 60px;
    font-size: 20px;
    border: 1px solid #ccc;
    border-radius: 3px;
}

.price-button {
    width: 40px;
    border: none;
    background: white;
    font-size: 30px;
    cursor: pointer;
}

.price-button:first-child {
    border-right: 1px solid #ccc;
}

.price-button:last-child {
    border-left: 1px solid #ccc;
}

.price-display {
    flex-grow: 1;
    text-align: center;
    padding: 8px;
}

/* ========================
   Facilities Grid
======================== */
.facilities-section {
    border: 1px solid #ccc;
    border-radius: 3px;
    padding: 10px;
}

.facilities-title {
    font-weight: bold;
    margin-bottom: 10px;
}

.facilities-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 15px;
}

.facility-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
}

/* ========================
   Description Box
======================== */
.description-container {
    margin-bottom: 20px;
}

.description-input {
    width: 100%;
    height: 120px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 3px;
    resize: vertical;
}

/* ========================
   Submit Button Section
======================== */
.button-container {
    display: flex;
    justify-content: flex-end;
}

.host-button {
    border: 1px solid #ccc;
    background: white;
    padding: 8px 20px;
    border-radius: 3px;
    cursor: pointer;
}

.host-button:hover {
    background-color: #f5f5f5;
}

.modal-overlay {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999;
}

.modal-content {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    width: 100vw;
    height: 100vh;
    position: relative;
}

.modal-img-container {
    flex: 1 1 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.modal-image {
    max-width: 90vw;
    max-height: 90vh;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 2px 16px rgba(0, 0, 0, 0.5);
    background: #fff;
}

.modal-close {
    position: fixed;
    top: 32px;
    right: 32px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    width: 48px;
    height: 48px;
    font-size: 32px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1001;
}

.modal-nav {
    background: rgba(0, 0, 0, 0.7);
    color: white;
    width: 56px;
    height: 56px;
    font-size: 32px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    transition: 0.2s;
}

.modal-nav.left {
    position: fixed;
    left: 32px;
    top: 50%;
    transform: translateY(-50%);
}

.modal-nav.right {
    position: fixed;
    right: 32px;
    top: 50%;
    transform: translateY(-50%);
}
</style>