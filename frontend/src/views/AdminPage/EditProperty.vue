<template>
    <div class="container">
        <div class="property-card">
            <!-- Header -->
            <div class="header">
                <h1>Edit Room</h1>
                <div>
                    <select v-model="selectedPropertyId" @change="onPropertyChange">
                        <option value="">Select Property</option>
                        <option v-for="property in properties" :key="property.id" :value="property.id">
                            {{ property.property_name }}
                        </option>
                    </select>
                    <select v-model="selectedRoomId" @change="onRoomChange" :disabled="!selectedPropertyId">
                        <option value="">Select Room</option>
                        <option v-for="room in rooms" :key="room.id" :value="room.id">
                            {{ room.name }}
                        </option>
                    </select>
                </div>
                <div class="user-icon"></div>
            </div>

            <!-- Navigation -->
            <div class="navigation">
                <button class="nav-button">Home</button>
            </div>

            <!-- Loading State -->
            <div v-if="isLoading" class="loading-container">
                <p>Loading room data...</p>
            </div>

            <!-- Edit Form -->
            <div v-else-if="selectedRoomId && roomData">
                <!-- Photo Upload Section -->
                <div class="upload-area" @dragover.prevent="onDragOver" @drop.prevent="onDrop"
                    @click="triggerFileInput">
                    <div class="upload-content">
                        <p>Click or Drag & Drop to Upload New Photos</p>
                        <input ref="fileInput" type="file" accept="image/*" multiple @change="handleFileUpload"
                            class="file-input" />
                    </div>
                </div>

                <!-- Room Information -->
                <div class="form-column space-y-4 p-4 bg-white rounded-2xl shadow-md">
                    <div class="flex w-full flex-col">
                        <label for="roomName" class="block text-sm font-medium text-gray-700 mb-1">Room Name</label>
                        <input id="roomName" type="text"
                            class="form-input w-full border border-gray-300 p-2 focus:ring focus:ring-blue-200"
                            placeholder="Enter room name" v-model="roomData.name" required />
                    </div>

                    <div class="flex w-full flex-col">
                        <label for="peopleCapacity" class="block text-sm font-medium text-gray-700 mb-1">Room Capacity
                            (People)</label>
                        <input id="peopleCapacity" type="number"
                            class="form-input w-full border border-gray-300 p-2 focus:ring focus:ring-blue-200"
                            placeholder="How many people can stay?" v-model.number="roomData.capacity" required />
                    </div>

                    <!-- Price Control -->
                    <div class="flex justify-center items-center">
                        <div class="border-l-2 border-r-2 border-black pl-6 pr-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Set Price</label>
                            <div class="flex items-center space-x-2">
                                <button @click="decreasePrice" type="button"
                                    class="price-button bg-red-100 hover:bg-red-200 text-gray-600 w-8 h-8">−</button>
                                <div class="text-3xl font-semibold w-20 text-center">
                                    ${{ roomData.default_price }}
                                </div>
                                <button @click="increasePrice" type="button"
                                    class="price-button bg-green-100 hover:bg-green-200 text-gray-600 w-8 h-8">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="tab-nav pt-10">
                    <div class="tab active" @click="toggleEditMode">
                        {{ isEditing ? "View Mode" : "Edit Photos" }}
                    </div>
                </div>

                <!-- Photo List Edit Mode -->
                <div v-if="isEditing" class="photo-list-edit">
                    <!-- Existing Photos -->
                    <div v-for="(photo, index) in existingPhotos" :key="'existing-' + photo.id" class="photo-list-item">
                        <img :src="photo.image_url ? `${IMAGE_BASE}/${photo.image_url}` : ''" alt="Room photo"
                            class="photo-thumb" />
                        <div class="photo-buttons">
                            <button class="btn delete" @click="deleteExistingPhoto(index)" type="button">Delete</button>
                        </div>
                    </div>
                    <!-- New Photos -->
                    <div v-for="(photo, index) in newPhotos" :key="'new-' + index" class="photo-list-item">
                        <img :src="photo.url" alt="New room photo" class="photo-thumb" />
                        <div class="photo-buttons">
                            <button class="btn delete" @click="deleteNewPhoto(index)" type="button">Delete</button>
                            <button class="btn update" @click="updatePhoto(index)" type="button">Update</button>
                        </div>
                    </div>
                    <!-- Hidden input for updating -->
                    <input ref="updatePhotoInput" type="file" accept="image/*" @change="handlePhotoUpdate"
                        class="file-input" />
                </div>

                <!-- Grid View Mode -->
                <div v-else class="photo-gallery">
                    <template v-for="(photo, index) in displayedPhotos" :key="photo.id || 'new-' + index">
                        <div class="photo-container" @click="openPhotoModal(index)">
                            <img :src="photo.image_url ? `${IMAGE_BASE}/${photo.image_url}` : photo.url"
                                alt="Room photo" class="room-photo" />
                            <div v-if="index === 4 && remainingPhotoCount > 0" class="photo-count">
                                {{ remainingPhotoCount }}+ Photos
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Facilities -->
                <div class="form-layout">
                    <div class="p-4">
                        <p class="text-2xl font-bold mb-6">Facilities</p>
                    </div>
                    <div class="facilities-container">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div v-for="group in amenitiesData" :key="group.id"
                                class="facility-group p-4 border rounded-lg">
                                <h2 class="text-xl font-semibold mb-4">{{ group.name }}</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <label v-for="amenity in group.amenities" :key="amenity.id"
                                        class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" v-model="amenity.selected"
                                            class="form-checkbox text-indigo-600" />
                                        <span class="text-gray-700">{{ amenity.amenity_name }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="description-container">
                    <textarea placeholder="Room description" class="description-input"
                        v-model="roomData.description"></textarea>
                </div>

                <!-- Update Button -->
                <div class="button-container mt-3">
                    <button class="host-button" @click="updateRoom" :disabled="isUpdating" type="button">
                        {{ isUpdating ? 'Updating...' : 'Update Room' }}
                    </button>
                </div>
            </div>

            <!-- No Room Selected State -->
            <div v-else class="no-selection">
                <p>Please select a property and room to edit.</p>
            </div>
        </div>

        <!-- Modal Viewer -->
        <div v-if="modalVisible" class="modal-overlay" @click.self="closeModal">
            <div class="modal-content">
                <button class="modal-close" @click="closeModal" type="button">
                    <Icon icon="charm:cross" />
                </button>
                <button class="modal-nav left" @click="prevPhoto" :disabled="currentPhotoIndex === 0" type="button">
                    <Icon icon="mingcute:left-fill" />
                </button>
                <img :src="allPhotos[currentPhotoIndex]?.image_url ? `${IMAGE_BASE}/${allPhotos[currentPhotoIndex].image_url}` : allPhotos[currentPhotoIndex]?.url"
                    class="modal-image rounded2xl" />
                <button class="modal-nav right" @click="nextPhoto"
                    :disabled="currentPhotoIndex === allPhotos.length - 1" type="button">
                    <Icon icon="mingcute:right-fill" />
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import { Icon } from "@iconify/vue";
import axios from "@/axios";

export default {
    name: "EditRoom",
    components: { Icon },
    setup() {
        const API_BASE = import.meta.env.VITE_API_BASE_URL; // e.g. "http://localhost:8100/api"
        const IMAGE_BASE = import.meta.env.VITE_IMAGE_BASE_URL || "http://localhost:9000"; // adjust as needed

        // Reactive data
        const properties = ref([]);
        const rooms = ref([]);
        const allRooms = ref([]); // store full list once
        const selectedPropertyId = ref("");
        const selectedRoomId = ref("");
        const roomData = ref(null);
        const isLoading = ref(false);
        const isUpdating = ref(false);
        const isEditing = ref(false);
        const existingPhotos = ref([]);
        const newPhotos = ref([]);
        const photosToDelete = ref([]);
        const amenitiesData = ref([]);
        const fileInput = ref(null);
        const updatePhotoInput = ref(null);
        const photoIndexToUpdate = ref(null);
        const modalVisible = ref(false);
        const currentPhotoIndex = ref(0);
        const maxPhotos = 10;
        const maxFileSize = 5 * 1024 * 1024; // 5MB

        // Computed
        const allPhotos = computed(() => [...existingPhotos.value, ...newPhotos.value]);
        const displayedPhotos = computed(() => allPhotos.value.slice(0, 5));
        const remainingPhotoCount = computed(() => Math.max(0, allPhotos.value.length - 5));

        // Load all properties + rooms once
        async function loadProperties() {
            try {
                const token = localStorage.getItem("token");
                if (!token) {
                    alert("Authentication required");
                    return;
                }
                axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
                // Ensure baseURL is set; here we do full URL:
                const response = await axios.get(`${API_BASE}/owner/property`);
                properties.value = response.data.properties || [];
                allRooms.value = response.data.rooms || [];
                // Clear any previous selection
                selectedPropertyId.value = "";
                selectedRoomId.value = "";
                roomData.value = null;
                existingPhotos.value = [];
                newPhotos.value = [];
            } catch (error) {
                console.error("Failed to load properties:", error);
                alert("Failed to load properties");
            }
        }

        // Filter rooms locally when a property is selected
        function loadRooms(propertyId) {
            if (!propertyId) {
                rooms.value = [];
                selectedRoomId.value = "";
                roomData.value = null;
                existingPhotos.value = [];
                newPhotos.value = [];
                photosToDelete.value = [];
                return;
            }
            rooms.value = allRooms.value.filter(room => room.application_id == propertyId);
            // clear previous room selection
            selectedRoomId.value = "";
            roomData.value = null;
            existingPhotos.value = [];
            newPhotos.value = [];
            photosToDelete.value = [];
        }

        // Load roomData from the filtered rooms list
        function loadRoomData(roomId) {
            if (!roomId || !selectedPropertyId.value) {
                roomData.value = null;
                existingPhotos.value = [];
                return;
            }
            isLoading.value = true;
            try {
                const room = rooms.value.find(r => r.id == roomId);
                if (!room) {
                    throw new Error("Room not found");
                }
                roomData.value = {
                    id: room.id,
                    name: room.name,
                    capacity: parseInt(room.capacity),
                    default_price: parseFloat(room.default_price),
                    description: room.description || ""
                };
                existingPhotos.value = Array.isArray(room.images) ? [...room.images] : [];
                newPhotos.value = [];
                photosToDelete.value = [];

                // Set amenities
                if (room.amenities && amenitiesData.value.length > 0) {
                    const roomAmenityIds = room.amenities.map(a => a.id);
                    amenitiesData.value.forEach(group => {
                        group.amenities.forEach(amenity => {
                            amenity.selected = roomAmenityIds.includes(amenity.id);
                        });
                    });
                }
            } catch (error) {
                console.error("Failed to load room data:", error);
                alert("Failed to load room data");
            } finally {
                isLoading.value = false;
            }
        }

        async function getAmenities() {
            try {
                const token = localStorage.getItem("token");
                axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
                const response = await axios.get(`${API_BASE}/amenities`);
                amenitiesData.value = response.data.data?.map(group => ({
                    ...group,
                    amenities: Array.isArray(group.amenities)
                        ? group.amenities.map(a => ({ ...a, selected: false }))
                        : []
                })) || [];
            } catch (error) {
                console.error("Failed to load amenities:", error);
                alert("Failed to load amenities");
            }
        }

        async function updateRoom() {
            if (!roomData.value || !selectedPropertyId.value) {
                alert("Please select a property and room to update");
                return;
            }
            if (!roomData.value.name.trim()) {
                alert("Room name is required");
                return;
            }
            if (!roomData.value.capacity || roomData.value.capacity < 1) {
                alert("Valid capacity is required");
                return;
            }
            if (!roomData.value.default_price || roomData.value.default_price < 0) {
                alert("Valid price is required");
                return;
            }
            isUpdating.value = true;
            const formData = new FormData();
            formData.append('name', roomData.value.name.trim());
            formData.append('capacity', roomData.value.capacity.toString());
            formData.append('default_price', roomData.value.default_price.toString());
            if (roomData.value.description) {
                formData.append('description', roomData.value.description.trim());
            }
            // Amenities
            const selectedAmenities = amenitiesData.value
                .flatMap(group => group.amenities)
                .filter(a => a.selected)
                .map(a => a.id);
            selectedAmenities.forEach((amenityId, index) => {
                formData.append(`amenities[${index}]`, amenityId);
            });
            // New images
            newPhotos.value.forEach((photo, index) => {
                formData.append(`images[${index}]`, photo.file);
            });
            // Delete images
            photosToDelete.value.forEach((photoId, index) => {
                formData.append(`delete_images[${index}]`, photoId);
            });

            try {
                const token = localStorage.getItem("token");
                if (!token) throw new Error("No authentication token found");
                axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
                // PATCH endpoint – ensure API_BASE ends with /api or include it
                const res = await axios.patch(
                    `${API_BASE}/owner/edit/house/${selectedPropertyId.value}/room/${roomData.value.id}`,
                    formData
                );
                alert("Room updated successfully!");
                // After update, you might reload properties/rooms or update local allRooms
                // For simplicity, reload all:
                await loadProperties();
            } catch (error) {
                console.error("Failed to update room:", error);
                if (error.response) {
                    alert(`Failed to update room: ${error.response.data.message || "Unknown error"}`);
                } else if (error.request) {
                    alert("Failed to update room: No response from server");
                } else {
                    alert(`Failed to update room: ${error.message}`);
                }
            } finally {
                isUpdating.value = false;
            }
        }

        // Event handlers
        const onPropertyChange = () => {
            loadRooms(selectedPropertyId.value);
        };
        const onRoomChange = () => {
            loadRoomData(selectedRoomId.value);
        };
        const increasePrice = () => {
            if (roomData.value) roomData.value.default_price = Number(roomData.value.default_price) + 1;
        };
        const decreasePrice = () => {
            if (roomData.value && roomData.value.default_price > 0) {
                roomData.value.default_price = Number(roomData.value.default_price) - 1;
            }
        };
        const triggerFileInput = () => fileInput.value?.click();
        const handleFileUpload = event => {
            const files = Array.from(event.target.files);
            previewImages(files);
            event.target.value = '';
        };
        const previewImages = files => {
            if (allPhotos.value.length + files.length > maxPhotos) {
                alert(`Cannot upload more than ${maxPhotos} photos.`);
                return;
            }
            files.forEach(file => {
                if (!file.type.startsWith("image/")) {
                    alert(`File "${file.name}" is not an image.`);
                    return;
                }
                if (file.size > maxFileSize) {
                    alert(`File "${file.name}" exceeds 5MB limit.`);
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => {
                    newPhotos.value.push({ url: e.target.result, file, name: file.name });
                };
                reader.readAsDataURL(file);
            });
        };
        const onDragOver = e => e.preventDefault();
        const onDrop = e => {
            e.preventDefault();
            previewImages(Array.from(e.dataTransfer.files));
        };
        const toggleEditMode = () => { isEditing.value = !isEditing.value; };
        const deleteExistingPhoto = index => {
            const photo = existingPhotos.value[index];
            if (photo.id) photosToDelete.value.push(photo.id);
            existingPhotos.value.splice(index, 1);
        };
        const deleteNewPhoto = index => newPhotos.value.splice(index, 1);
        const updatePhoto = index => {
            photoIndexToUpdate.value = index;
            updatePhotoInput.value?.click();
        };
        const handlePhotoUpdate = event => {
            const file = event.target.files[0];
            if (file && file.type.startsWith("image/") && photoIndexToUpdate.value !== null) {
                if (file.size > maxFileSize) {
                    alert("File size exceeds 5MB limit.");
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => {
                    newPhotos.value[photoIndexToUpdate.value] = { url: e.target.result, file, name: file.name };
                    photoIndexToUpdate.value = null;
                };
                reader.readAsDataURL(file);
            }
            event.target.value = '';
        };
        const openPhotoModal = index => {
            currentPhotoIndex.value = index;
            modalVisible.value = true;
        };
        const closeModal = () => modalVisible.value = false;
        const nextPhoto = () => {
            if (currentPhotoIndex.value < allPhotos.value.length - 1) currentPhotoIndex.value++;
        };
        const prevPhoto = () => {
            if (currentPhotoIndex.value > 0) currentPhotoIndex.value--;
        };

        onMounted(async () => {
            await getAmenities();
            await loadProperties();
        });

        return {
            properties,
            rooms,
            selectedPropertyId,
            selectedRoomId,
            roomData,
            isLoading,
            isUpdating,
            isEditing,
            existingPhotos,
            newPhotos,
            allPhotos,
            displayedPhotos,
            remainingPhotoCount,
            amenitiesData,
            fileInput,
            updatePhotoInput,
            modalVisible,
            currentPhotoIndex,
            onPropertyChange,
            onRoomChange,
            increasePrice,
            decreasePrice,
            triggerFileInput,
            handleFileUpload,
            onDragOver,
            onDrop,
            toggleEditMode,
            deleteExistingPhoto,
            deleteNewPhoto,
            updatePhoto,
            handlePhotoUpdate,
            openPhotoModal,
            closeModal,
            nextPhoto,
            prevPhoto,
            updateRoom,
            IMAGE_BASE, // for template binding
        };
    },
};
</script>

<style scoped>
/* ... (keep all the existing styles from the original component) ... */
.container {
    max-width: 1440px;
    margin: 0 auto;
    padding-top: 20px;
    padding-left: 20px;
    padding-right: 20px;
    font-family: Arial, sans-serif;
}

.property-card {
    background-color: white;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

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
    padding: 8px 30px;
    background-color: #b71c1c;
    color: white;
    font-size: 14px;
    cursor: pointer;
    margin-bottom: 15px;
}

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

.loading-container {
    text-align: center;
    padding: 40px;
    color: #666;
}

.no-selection {
    text-align: center;
    padding: 40px;
    color: #666;
    font-size: 16px;
}

.photo-list-edit {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}

.photo-list-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.photo-thumb {
    width: 45%;
    height: 20%;
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

.photo-gallery .photo-container:nth-child(n + 4) {
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
    background-color: rgba(128, 128, 128, 0.6);
    color: white;
    font-size: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-column {
    display: flex;
    gap: 15px;
    align-items: center;
    justify-content: space-between;
}

.facilities-container {
    margin-bottom: 20px;
}

.facility-group {
    background-color: #f9f9f9;
}

.facility-group h2 {
    color: #333;
    border-bottom: 1px solid #ddd;
    padding-bottom: 8px;
}

.price-button {
    width: 40px;
    border: none;
    background: white;
    font-size: 40px;
    display: flex;
    justify-content: center;
    cursor: pointer;
    align-items: center;
}

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

.button-container {
    display: flex;
    justify-content: flex-end;
}

.host-button {
    border: 1px solid #ccc;
    background: rgb(150, 40, 40);
    padding: 8px 20px;
    border-radius: 3px;
    cursor: pointer;
    color: white;
}

.host-button:hover:not(:disabled) {
    background-color: rgb(120, 30, 30);
}

.host-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.modal-overlay {
    position: fixed;
    inset: 0;
    width: 100%;
    height: 100%;
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

.modal-image {
    width: 90vw;
    height: 90vh;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 2px 16px rgba(0, 0, 0, 0.5);
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