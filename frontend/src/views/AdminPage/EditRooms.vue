<template>
  <div class="container">
    <div class="property-card">
      <!-- Header -->
      <div class="header">
        <h1>Edit Room</h1>
        <div class="user-icon"></div>
      </div>

      <!-- Navigation -->
      <div class="navigation">
        <button class="nav-button" @click="goBack">Back</button>
      </div>

      <!-- House Selection Step -->
      <div v-if="currentStep === 'selectHouse'" class="selection-step">
        <h2 class="text-2xl font-bold mb-4">Select House</h2>
        <div v-if="isLoadingHouses" class="loading">Loading houses...</div>
        <div v-else-if="houses.length === 0" class="no-data">No houses found</div>
        <div v-else class="houses-grid">
          <div
            v-for="house in houses"
            :key="house.id"
            class="house-card cursor-pointer border rounded-lg p-4 hover:shadow-lg transition-shadow"
            @click="selectHouse(house)"
          >
            <h3 class="text-xl font-semibold">{{ house.property_name }}</h3>
            <p class="text-gray-600">{{ house.location?.city }}</p>
            <p class="text-sm text-gray-500">{{ house.description }}</p>
          </div>
        </div>
      </div>

      <!-- Room Selection Step -->
      <div v-if="currentStep === 'selectRoom'" class="selection-step">
        <h2 class="text-2xl font-bold mb-4">
          Select Room from {{ selectedHouse.name }}
        </h2>
        <button 
          class="mb-4 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
          @click="goToHouseSelection"
        >
          ← Back to House Selection
        </button>
        <div v-if="isLoadingRooms" class="loading">Loading rooms...</div>
        <div v-else-if="rooms.length === 0" class="no-data">No rooms found for this house</div>
        <div v-else class="rooms-grid">
          <div
            v-for="room in rooms"
            :key="room.id"
            class="room-card cursor-pointer border rounded-lg p-4 hover:shadow-lg transition-shadow"
            @click="selectRoom(room)"
          >
            <div class="flex items-center space-x-4">
              <img
                v-if="room.images && room.images.length > 0"
                :src="getImageUrl(room.images[0].thumbnail_url)"
                alt="Room thumbnail"
                class="w-16 h-16 object-cover rounded"
              />
              <div class="flex-1">
                <h3 class="text-xl font-semibold">{{ room.name }}</h3>
                <p class="text-gray-600">Capacity: {{ room.capacity }} people</p>
                <p class="text-green-600 font-bold">${{ room.default_price }}</p>
                <p class="text-sm text-gray-500">{{ room.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Edit Room Form -->
      <div v-if="currentStep === 'editRoom'" class="edit-form">
        <h2 class="text-2xl font-bold mb-4">
          Edit Room: {{ selectedRoom?.name }}
        </h2>
        <button 
          class="mb-4 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
          @click="goToRoomSelection"
        >
          ← Back to Room Selection
        </button>

        <!-- Current Images Display -->
        <div v-if="currentImages.length > 0" class="current-images mb-6">
          <h3 class="text-lg font-semibold mb-2">Current Images</h3>
          <div class="images-grid grid grid-cols-2 md:grid-cols-4 gap-4">
<div
  v-for="(image, index) in currentImages"
  :key="image.id"
  class="relative image-item"
>
  <img
    :src="getImageUrl(image.image_url)"
    :alt="`Room image ${index + 1}`"
    class="w-full h-24 object-cover rounded cursor-pointer"
    :class="{ 'opacity-40 grayscale': imagesToDelete.includes(image.id) }"
    @click="openImageModal(getImageUrl(image.image_url))"
  />
  <div 
    v-if="imagesToDelete.includes(image.id)"
    class="absolute inset-0 flex items-center justify-center"
  >
    <span class="bg-red-600 text-white px-2 py-1 rounded text-sm">Marked for deletion</span>
  </div>
  <button
    @click="markImageForDeletion(image.id)"
    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600"
    :class="{ 'bg-red-700': imagesToDelete.includes(image.id) }"
  >
    ×
  </button>
</div>
          </div>
        </div>

        <!-- Photo Upload Section -->
        <div
          class="upload-area"
          @dragover.prevent="onDragOver"
          @drop.prevent="onDrop"
          @click="triggerFileInput"
        >
          <div class="upload-content">
            <p>Click or Drag & Drop to Upload New Photos</p>
            <input
              ref="fileInput"
              type="file"
              accept="image/*"
              multiple
              @change="handleFileUpload"
              class="file-input"
            />
          </div>
        </div>

        <!-- New Images Preview -->
        <div v-if="newPhotos.length > 0" class="new-images mb-6">
          <h3 class="text-lg font-semibold mb-2">New Images to Upload</h3>
          <div class="images-grid grid grid-cols-2 md:grid-cols-4 gap-4">
            <div
              v-for="(photo, index) in newPhotos"
              :key="index"
              class="relative image-item"
            >
              <img
                :src="photo.url"
                :alt="`New image ${index + 1}`"
                class="w-full h-24 object-cover rounded"
              />
              <button
                @click="removeNewPhoto(index)"
                class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600"
              >
                ×
              </button>
            </div>
          </div>
        </div>

        <!-- Form Fields -->
        <div class="form-column space-y-4 p-4 bg-white rounded-2xl shadow-md">
          <!-- Room Name -->
          <div class="flex w-full flex-col">
            <label
              for="roomName"
              class="block text-sm font-medium text-gray-700 mb-1"
            >
              Room Name
            </label>
            <input
              id="roomName"
              type="text"
              class="form-input w-full border border-gray-300 p-2 focus:ring focus:ring-blue-200"
              placeholder="Enter room name"
              v-model="roomName"
              required
            />
          </div>

          <!-- Room Capacity -->
          <div class="flex w-full flex-col">
            <label
              for="peopleCapacity"
              class="block text-sm font-medium text-gray-700 mb-1"
            >
              Room Capacity (People)
            </label>
            <input
              id="peopleCapacity"
              type="number"
              class="form-input w-full border border-gray-300 p-2 focus:ring focus:ring-blue-200"
              placeholder="How many people can stay?"
              v-model="peopleCapacity"
              required
            />
          </div>

          <!-- Price Control -->
          <div class="flex justify-center items-center">
            <div class="border-l-2 border-r-2 border-black pl-6 pr-6">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Set Price
              </label>
              <div class="flex items-center space-x-2">
                <button
                  @click="decreasePrice"
                  class="price-button bg-red-100 hover:bg-red-200 text-gray-600 w-8 h-8"
                >
                  −
                </button>
                <div class="text-3xl font-semibold w-20 text-center">
                  ${{ price }}
                </div>
                <button
                  @click="increasePrice"
                  class="price-button bg-green-100 hover:bg-green-200 text-gray-600 w-8 h-8"
                >
                  +
                </button>
              </div>
            </div>
          </div>

          <!-- Availability Toggle -->
          <div class="flex w-full flex-col">
            <label class="flex items-center space-x-2">
              <input
                type="checkbox"
                v-model="isAvailable"
                class="form-checkbox text-indigo-600"
              />
              <span class="text-gray-700">Room is available for booking</span>
            </label>
          </div>
        </div>

        <!-- Facilities Checkboxes -->
        <div class="facilities-section mt-6">
          <div class="p-4">
            <p class="text-2xl font-bold mb-6">Facilities</p>
          </div>
          <div v-if="isLoadingAmenities" class="loading">Loading amenities...</div>
          <div v-else class="facilities-container">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div
                v-for="group in amenitiesData"
                :key="group.id"
                class="facility-group p-4 border rounded-lg"
              >
                <h2 class="text-xl font-semibold mb-4">{{ group.name }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                  <label
                    v-for="amenity in group.amenities"
                    :key="amenity.id"
                    class="flex items-center space-x-2 cursor-pointer"
                  >
                    <input
                      type="checkbox"
                      v-model="amenity.selected"
                      class="form-checkbox text-indigo-600"
                    />
                    <span class="text-gray-700">{{ amenity.amenity_name }}</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Description Area -->
        <div class="description-container mt-6">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Description
          </label>
          <textarea
            placeholder="Room description..."
            class="description-input w-full border border-gray-300 p-3 rounded focus:ring focus:ring-blue-200"
            rows="4"
            v-model="description"
          ></textarea>
        </div>

        <!-- Update Button -->
        <div class="button-container mt-6">
          <button 
            class="update-button w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
            @click="updateRoom"
            :disabled="isUpdating"
          >
            {{ isUpdating ? 'Updating...' : 'Update Room' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Image Modal -->
    <div v-if="imageModalVisible" class="modal-overlay" @click.self="closeImageModal">
      <div class="modal-content">
        <button class="modal-close" @click="closeImageModal">×</button>
        <img :src="modalImageSrc" class="modal-image rounded-2xl" />
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import axios from "@/axios";

export default {
  name: "EditRoom",
  setup() {
    // Navigation state
    const currentStep = ref('selectHouse'); // 'selectHouse', 'selectRoom', 'editRoom'
    
    // Loading states
    const isLoadingHouses = ref(false);
    const isLoadingRooms = ref(false);
    const isLoadingAmenities = ref(false);
    const isUpdating = ref(false);
    
    // Data
    const houses = ref([]);
    const rooms = ref([]);
    const amenitiesData = ref([]);
    const selectedHouse = ref(null);
    const selectedRoom = ref(null);
    
    // Form data
    const roomName = ref("");
    const peopleCapacity = ref(1);
    const price = ref(0);
    const description = ref("");
    const isAvailable = ref(true);
    
    // Image handling
    const currentImages = ref([]);
    const newPhotos = ref([]);
    const imagesToDelete = ref([]);
    const fileInput = ref(null);
    const imageModalVisible = ref(false);
    const modalImageSrc = ref("");
    
    // Constants
    const URL = import.meta.env.VITE_API_BASE_URL;
    const maxPhotos = 10;
    const maxFileSize = 5 * 1024 * 1024; // 5MB

    // Computed
    const totalImages = computed(() => 
      currentImages.value.length - imagesToDelete.value.length + newPhotos.value.length
    );

    // API Functions
    async function fetchHouses() {
      isLoadingHouses.value = true;
      try {
        const token = localStorage.getItem("token");
        if (!token) {
          alert("You must be logged in.");
          return;
        }
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        
        const response = await axios.get(`${URL}/houses/owner`);
        houses.value = response.data;
      } catch (error) {
        console.error("Failed to fetch houses:", error);
        alert("Failed to load houses. Please try again.");
      } finally {
        isLoadingHouses.value = false;
      }
    }

    async function fetchRooms(houseId) {
      isLoadingRooms.value = true;
      try {
        const token = localStorage.getItem("token");
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        
        const response = await axios.get(`${URL}/houses/${houseId}/rooms`);
        rooms.value = response.data;
      } catch (error) {
        console.error("Failed to fetch rooms:", error);
        alert("Failed to load rooms. Please try again.");
      } finally {
        isLoadingRooms.value = false;
      }
    }

    async function fetchRoomDetails(houseId, roomId) {
      try {
        const token = localStorage.getItem("token");
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        
        const response = await axios.get(`${URL}/houses/${houseId}/rooms/${roomId}`);
        const room = response.data;
        
        // Populate form with room data
        roomName.value = room.name;
        peopleCapacity.value = room.capacity;
        price.value = room.default_price;
        description.value = room.description || "";
        isAvailable.value = room.is_available;
        currentImages.value = room.images || [];
        
        // Set amenities selection
        if (room.amenities) {
          amenitiesData.value.forEach(group => {
            group.amenities.forEach(amenity => {
              amenity.selected = room.amenities.some(roomAmenity => roomAmenity.id === amenity.id);
            });
          });
        }
      } catch (error) {
        console.error("Failed to fetch room details:", error);
        alert("Failed to load room details. Please try again.");
      }
    }

    async function getAmenities() {
      isLoadingAmenities.value = true;
      try {
        const response = await axios.get(`${URL}/amenities`);
        if (!Array.isArray(response.data.data)) {
          amenitiesData.value = [];
          return;
        }
        amenitiesData.value = response.data.data.map((group) => ({
          ...group,
          amenities: Array.isArray(group.amenities)
            ? group.amenities.map((a) => ({
                ...a,
                selected: false,
              }))
            : [],
        }));
      } catch (error) {
        console.error("Failed to load amenities:", error);
        amenitiesData.value = [];
      } finally {
        isLoadingAmenities.value = false;
      }
    }

    async function updateRoom() {
      if (!selectedHouse.value || !selectedRoom.value) {
        alert("No room selected for update.");
        return;
      }

      // Validation
      if (!roomName.value || !peopleCapacity.value || price.value < 0) {
        alert("Please fill in all required fields.");
        return;
      }

      if (totalImages.value === 0) {
        alert("Room must have at least one image.");
        return;
      }

      if (totalImages.value > maxPhotos) {
        alert(`Cannot have more than ${maxPhotos} images.`);
        return;
      }

      isUpdating.value = true;
      
      try {
        const formData = new FormData();
        
        // Basic room data
        formData.append('name', roomName.value);
        formData.append('description', description.value);
        formData.append('capacity', peopleCapacity.value);
        formData.append('default_price', price.value);
        formData.append('is_available', isAvailable.value ? '1' : '0');
        
        // Selected amenities
        const selectedAmenities = amenitiesData.value
          .flatMap(group => group.amenities)
          .filter(amenity => amenity.selected)
          .map(amenity => amenity.id);
        
        selectedAmenities.forEach((amenityId, index) => {
          formData.append(`amenities[${index}]`, amenityId);
        });
        
        // Images to delete
        imagesToDelete.value.forEach((imageId, index) => {
          formData.append(`deleted_images[${index}]`, imageId);
        });
        
        // New images
        newPhotos.value.forEach((photo, index) => {
          formData.append(`images[${index}]`, photo.file);
        });

        const token = localStorage.getItem("token");
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        
        const response = await axios.post(
          `${URL}/houses/${selectedHouse.value.id}/rooms/${selectedRoom.value.id}`,
          formData,
          {
            headers: { 
              "Content-Type": "multipart/form-data",
              "X-HTTP-Method-Override": "PUT"
            },
          }
        );
        
        alert("Room updated successfully!");
        
        // Reset form and go back to room selection
        resetForm();
        currentStep.value = 'selectRoom';
        await fetchRooms(selectedHouse.value.id);
        
      } catch (error) {
        console.error("Failed to update room:", error);
        const errors = error.response?.data?.errors;
        if (errors) {
          const errorMessages = Object.values(errors).flat().join("\n");
          alert(`Failed to update room:\n${errorMessages}`);
        } else {
          alert("Failed to update room. Please try again.");
        }
      } finally {
        isUpdating.value = false;
      }
    }

    // Navigation functions
    function selectHouse(house) {
      selectedHouse.value = house;
      currentStep.value = 'selectRoom';
      fetchRooms(house.id);
    }

    function selectRoom(room) {
      selectedRoom.value = room;
      currentStep.value = 'editRoom';
      fetchRoomDetails(selectedHouse.value.id, room.id);
    }

    function goToHouseSelection() {
      currentStep.value = 'selectHouse';
      selectedHouse.value = null;
      rooms.value = [];
    }

    function goToRoomSelection() {
      currentStep.value = 'selectRoom';
      selectedRoom.value = null;
      resetForm();
    }

    function goBack() {
      if (currentStep.value === 'selectRoom') {
        goToHouseSelection();
      } else if (currentStep.value === 'editRoom') {
        goToRoomSelection();
      }
    }

    function resetForm() {
      roomName.value = "";
      peopleCapacity.value = 1;
      price.value = 0;
      description.value = "";
      isAvailable.value = true;
      currentImages.value = [];
      newPhotos.value = [];
      imagesToDelete.value = [];
      
      // Reset amenities
      amenitiesData.value.forEach(group => {
        group.amenities.forEach(amenity => {
          amenity.selected = false;
        });
      });
    }

    // Image handling functions
    function getImageUrl(path) {
      // Adjust this based on your MinIO/storage setup
      return path.startsWith('http') ? path : `http://localhost:9000/${path}`;
    }

function markImageForDeletion(imageId) {
  console.log("Image ID:", imageId);
  console.log("Current images to delete:", imagesToDelete.value);
  
  const index = imagesToDelete.value.indexOf(imageId);
  if (index > -1) {
    imagesToDelete.value.splice(index, 1);
  } else {
    imagesToDelete.value.push(imageId);
  }
  
  console.log("Updated images to delete:", imagesToDelete.value);
}

    function triggerFileInput() {
      fileInput.value?.click();
    }

    function handleFileUpload(event) {
      const files = Array.from(event.target.files);
      previewImages(files);
      event.target.value = ''; // Reset input
    }

    function onDrop(event) {
      const files = Array.from(event.dataTransfer.files);
      previewImages(files);
    }

    function onDragOver() {}

    function previewImages(files) {
      if (totalImages.value + files.length > maxPhotos) {
        alert(`Cannot upload more than ${maxPhotos} photos total.`);
        return;
      }

      files.forEach((file) => {
        if (!file.type.startsWith("image/")) {
          alert("Only image files are allowed.");
          return;
        }
        if (file.size > maxFileSize) {
          alert("File size exceeds 5MB limit.");
          return;
        }
        
        const reader = new FileReader();
        reader.onload = (e) => {
          newPhotos.value.push({ url: e.target.result, file });
        };
        reader.readAsDataURL(file);
      });
    }

    function removeNewPhoto(index) {
      newPhotos.value.splice(index, 1);
    }

    function openImageModal(imageSrc) {
      modalImageSrc.value = imageSrc;
      imageModalVisible.value = true;
    }

    function closeImageModal() {
      imageModalVisible.value = false;
      modalImageSrc.value = "";
    }

    // Price controls
    function increasePrice() {
      price.value += 1;
    }

    function decreasePrice() {
      if (price.value > 0) price.value -= 1;
    }

    // Lifecycle
    onMounted(() => {
      fetchHouses();
      getAmenities();
    });

    return {
      // Navigation
      currentStep,
      goBack,
      goToHouseSelection,
      goToRoomSelection,
      
      // Loading states
      isLoadingHouses,
      isLoadingRooms,
      isLoadingAmenities,
      isUpdating,
      
      // Data
      houses,
      rooms,
      amenitiesData,
      selectedHouse,
      selectedRoom,
      
      // Form data
      roomName,
      peopleCapacity,
      price,
      description,
      isAvailable,
      
      // Image handling
      currentImages,
      newPhotos,
      imagesToDelete,
      fileInput,
      imageModalVisible,
      modalImageSrc,
      
      // Functions
      selectHouse,
      selectRoom,
      updateRoom,
      getImageUrl,
      markImageForDeletion,
      triggerFileInput,
      handleFileUpload,
      onDrop,
      onDragOver,
      removeNewPhoto,
      openImageModal,
      closeImageModal,
      increasePrice,
      decreasePrice,
      
      // Computed
      totalImages,
    };
  },
};
</script>

<style scoped>
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.property-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.header h1 {
  font-size: 2rem;
  font-weight: bold;
  color: #1f2937;
}

.user-icon {
  width: 40px;
  height: 40px;
  background: #e5e7eb;
  border-radius: 50%;
}

.navigation {
  margin-bottom: 24px;
}

.nav-button {
  padding: 8px 16px;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.nav-button:hover {
  background: #2563eb;
}

.selection-step {
  margin-bottom: 24px;
}

.houses-grid, .rooms-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 16px;
  margin-top: 16px;
}

.house-card, .room-card {
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  padding: 16px;
  transition: all 0.2s;
}

.house-card:hover, .room-card:hover {
  border-color: #3b82f6;
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.loading, .no-data {
  text-align: center;
  padding: 40px;
  color: #6b7280;
  font-size: 1.1rem;
}

.upload-area {
  border: 2px dashed #d1d5db;
  border-radius: 12px;
  padding: 40px;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s;
  margin-bottom: 24px;
}

.upload-area:hover {
  border-color: #3b82f6;
  background: #f8fafc;
}

.file-input {
  display: none;
}

.images-grid {
  margin-top: 16px;
}

.image-item {
  position: relative;
}

.image-item img {
  width: 100%;
  height: 96px;
  object-fit: cover;
  border-radius: 8px;
  border: 2px solid #e5e7eb;
}

.price-button {
  border: none;
  border-radius: 50%;
  cursor: pointer;
  font-size: 1.2rem;
  font-weight: bold;
  transition: all 0.2s;
}

.form-input {
  border-radius: 8px;
  padding: 12px;
  font-size: 1rem;
}

.form-input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.description-input {
  border-radius: 8px;
  resize: vertical;
  min-height: 100px;
}

.description-input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.update-button {
  font-size: 1.1rem;
  font-weight: 600;
  transition: all 0.2s;
}

.update-button:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  position: relative;
  max-width: 90%;
  max-height: 90%;
}

.modal-image {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

.modal-close {
  position: absolute;
  top: -40px;
  right: 0;
  background: white;
  border: none;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 1.2rem;
  font-weight: bold;
}

.facilities-container {
  margin-top: 16px;
}

.facility-group {
  background: #f9fafb;
}

.form-checkbox {
  width: 18px;
  height: 18px;
  accent-color: #3b82f6;
}
</style>