<template>
  <div class="room-management-container">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Room Management</h1>
          <p class="text-gray-600 mt-1">Manage rooms for your properties</p>
        </div>
        <button
          @click="showCreateModal = true"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
        >
          <Plus class="w-5 h-5" />
          <span>Add New Room</span>
        </button>
      </div>
    </div>

    <!-- Property Selection -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
      <h2 class="text-lg font-semibold mb-4">Select Property</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div
          v-for="property in properties"
          :key="property.id"
          :class="[
            'border-2 rounded-lg p-4 cursor-pointer transition-all',
            selectedProperty?.id === property.id
              ? 'border-blue-500 bg-blue-50'
              : 'border-gray-200 hover:border-gray-300'
          ]"
          @click="selectProperty(property)"
        >
          <div class="flex items-center justify-between">
            <div>
              <h3 class="font-semibold text-gray-800">{{ property.property_name }}</h3>
              <p class="text-sm text-gray-600">{{ property.description }}</p>
              <div class="flex items-center mt-2">
                <div class="flex text-yellow-400">
                  <Star
                    v-for="star in property.star_rating"
                    :key="star"
                    class="w-4 h-4 fill-current"
                  />
                </div>
                <span class="ml-2 text-sm text-gray-500">{{ property.star_rating }} stars</span>
              </div>
            </div>
            <div class="text-right">
              <div class="text-2xl font-bold text-blue-600">
                {{ getRoomCount(property.id) }}
              </div>
              <div class="text-sm text-gray-500">rooms</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Rooms List -->
    <div v-if="selectedProperty" class="bg-white rounded-lg shadow-md p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold">
          Rooms for {{ selectedProperty.property_name }}
        </h2>
        <div class="flex space-x-2">
          <button
            @click="loadRooms(selectedProperty.id)"
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
          >
            <RotateCcw class="w-4 h-4" />
            <span>Refresh</span>
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="flex justify-center items-center py-8">
        <div class="w-8 h-8 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
      </div>

      <!-- Rooms Grid -->
      <div v-else-if="rooms.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="room in rooms"
          :key="room.id"
          class="border rounded-lg p-4 hover:shadow-md transition-shadow"
        >
          <!-- Room Image -->
          <div class="aspect-w-16 aspect-h-9 mb-4">
            <img
              :src="getRoomImage(room)"
              :alt="room.name"
              class="w-full h-48 object-cover rounded-lg"
              loading="lazy"
            />
          </div>

          <!-- Room Info -->
          <div class="space-y-2">
            <h3 class="font-semibold text-gray-800">{{ room.name }}</h3>
            <p class="text-sm text-gray-600">{{ room.description }}</p>
            
            <div class="flex justify-between items-center">
              <div class="text-lg font-bold text-blue-600">
                ${{ room.default_price }}/night
              </div>
              <div class="text-sm text-gray-500">
                {{ room.capacity }} guests
              </div>
            </div>

            <!-- Amenities -->
            <div class="flex flex-wrap gap-1 mt-2">
              <span
                v-for="amenity in room.amenities?.slice(0, 3)"
                :key="amenity.id"
                class="px-2 py-1 bg-gray-100 text-xs rounded-full text-gray-600"
              >
                {{ amenity.amenity_name }}
              </span>
              <span
                v-if="room.amenities?.length > 3"
                class="px-2 py-1 bg-gray-100 text-xs rounded-full text-gray-600"
              >
                +{{ room.amenities.length - 3 }} more
              </span>
            </div>

            <!-- Actions -->
            <div class="flex space-x-2 mt-4">
              <button
                @click="editRoom(room)"
                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors"
              >
                Edit
              </button>
              <button
                @click="confirmDelete(room)"
                class="flex-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm transition-colors"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-8">
        <Home class="w-16 h-16 mx-auto text-gray-400 mb-4" />
        <h3 class="text-lg font-semibold text-gray-600 mb-2">No rooms yet</h3>
        <p class="text-gray-500 mb-4">Add your first room to get started</p>
        <button
          @click="showCreateModal = true"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors"
        >
          Add Room
        </button>
      </div>
    </div>

    <!-- Create/Edit Room Modal -->
    <div
      v-if="showCreateModal || showEditModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    >
      <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">
              {{ isEditMode ? 'Edit Room' : 'Add New Room' }}
            </h2>
            <button
              @click="closeModal"
              class="text-gray-400 hover:text-gray-600"
            >
              <X class="w-6 h-6" />
            </button>
          </div>

          <form @submit.prevent="saveRoom">
            <!-- Room Name -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Room Name *
              </label>
              <input
                v-model="roomForm.name"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="e.g., Deluxe Suite"
              />
            </div>

            <!-- Description -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Description
              </label>
              <textarea
                v-model="roomForm.description"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Describe your room..."
              ></textarea>
            </div>

            <!-- Price and Capacity -->
            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Price per Night ($) *
                </label>
                <input
                  v-model.number="roomForm.default_price"
                  type="number"
                  min="0"
                  step="0.01"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Capacity (guests) *
                </label>
                <input
                  v-model.number="roomForm.capacity"
                  type="number"
                  min="1"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>

            <!-- Bed Type and Room Size -->
            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Bed Type
                </label>
                <select
                  v-model="roomForm.bed_type"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="single">Single</option>
                  <option value="double">Double</option>
                  <option value="queen">Queen</option>
                  <option value="king">King</option>
                  <option value="bunk">Bunk</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Room Size (sq ft)
                </label>
                <input
                  v-model.number="roomForm.room_size"
                  type="number"
                  min="0"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>

            <!-- Amenities -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Amenities
              </label>
              <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3">
                <label
                  v-for="amenity in amenities"
                  :key="amenity.id"
                  class="flex items-center space-x-2"
                >
                  <input
                    type="checkbox"
                    :value="amenity.id"
                    v-model="roomForm.amenities"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  />
                  <span class="text-sm">{{ amenity.amenity_name }}</span>
                </label>
              </div>
            </div>

            <!-- Images -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Room Images
              </label>
              <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                <input
                  ref="fileInput"
                  type="file"
                  multiple
                  accept="image/*"
                  @change="handleImageUpload"
                  class="hidden"
                />
                <div class="text-center">
                  <Upload class="w-8 h-8 mx-auto text-gray-400 mb-2" />
                  <button
                    type="button"
                    @click="$refs.fileInput.click()"
                    class="text-blue-600 hover:text-blue-700"
                  >
                    Click to upload images
                  </button>
                  <p class="text-sm text-gray-500 mt-1">
                    PNG, JPG, GIF up to 5MB each
                  </p>
                </div>
              </div>
              
              <!-- Image Preview -->
              <div v-if="imagePreview.length > 0" class="mt-4">
                <div class="grid grid-cols-3 gap-2">
                  <div
                    v-for="(image, index) in imagePreview"
                    :key="index"
                    class="relative"
                  >
                    <img
                      :src="image"
                      alt="Preview"
                      class="w-full h-20 object-cover rounded border"
                    />
                    <button
                      type="button"
                      @click="removeImage(index)"
                      class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs"
                    >
                      ×
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="isSubmitting"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
              >
                {{ isSubmitting ? 'Saving...' : (isEditMode ? 'Update Room' : 'Create Room') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useOptimizedAPI, useOptimizedClick } from '@/composables/usePerformance.js';
import axios from 'axios';
import { Home, Plus, RotateCcw, Star, Upload, X } from 'lucide-vue-next';
import { computed, onMounted, reactive, ref } from 'vue';

// Reactive state
const properties = ref([]);
const rooms = ref([]);
const amenities = ref([]);
const selectedProperty = ref(null);
const isLoading = ref(false);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const isSubmitting = ref(false);
const imagePreview = ref([]);
const selectedImages = ref([]);

// Form data
const roomForm = reactive({
  name: '',
  description: '',
  default_price: 0,
  capacity: 1,
  bed_type: 'double',
  room_size: null,
  amenities: []
});

// Computed properties
const isEditMode = computed(() => showEditModal.value);
const getRoomCount = (propertyId) => {
  return rooms.value.filter(room => room.application_id === propertyId).length;
};

// Optimized API calls
const { execute: executeAPI } = useOptimizedAPI(
  (endpoint, options = {}) => axios({ url: endpoint, ...options }),
  { cacheTime: 2 * 60 * 1000 }
);

// Optimized click handlers
const { handleClick: handleOptimizedClick } = useOptimizedClick(async (fn) => {
  await fn();
}, 300);

// Load properties
const loadProperties = async () => {
  try {
    isLoading.value = true;
    const response = await executeAPI('/owner/properties', {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    });
    
    if (response.data.success) {
      properties.value = response.data.data;
    }
  } catch (error) {
    console.error('Error loading properties:', error);
    alert('Failed to load properties');
  } finally {
    isLoading.value = false;
  }
};

// Load rooms for selected property
const loadRooms = async (propertyId) => {
  try {
    isLoading.value = true;
    const response = await executeAPI(`/api/owner/properties/${propertyId}/rooms`, {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    });
    
    if (response.data.success) {
      rooms.value = response.data.data.rooms;
    }
  } catch (error) {
    console.error('Error loading rooms:', error);
    alert('Failed to load rooms');
  } finally {
    isLoading.value = false;
  }
};

// Load amenities
const loadAmenities = async () => {
  try {
    const response = await executeAPI('/owner/amenities', {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    });
    
    if (response.data.success) {
      amenities.value = response.data.data;
    }
  } catch (error) {
    console.error('Error loading amenities:', error);
  }
};

// Select property
const selectProperty = (property) => {
  selectedProperty.value = property;
  loadRooms(property.id);
};

// Handle image upload
const handleImageUpload = (event) => {
  const files = Array.from(event.target.files);
  selectedImages.value = files;
  
  // Create preview URLs
  imagePreview.value = files.map(file => URL.createObjectURL(file));
};

// Remove image
const removeImage = (index) => {
  URL.revokeObjectURL(imagePreview.value[index]);
  imagePreview.value.splice(index, 1);
  selectedImages.value.splice(index, 1);
};

// Save room
const saveRoom = handleOptimizedClick(async () => {
  if (!selectedProperty.value) {
    alert('Please select a property first');
    return;
  }

  try {
    isSubmitting.value = true;
    
    const formData = new FormData();
    formData.append('application_id', selectedProperty.value.id);
    formData.append('name', roomForm.name);
    formData.append('description', roomForm.description);
    formData.append('default_price', roomForm.default_price);
    formData.append('capacity', roomForm.capacity);
    formData.append('bed_type', roomForm.bed_type);
    
    if (roomForm.room_size) {
      formData.append('room_size', roomForm.room_size);
    }
    
    // Add amenities
    roomForm.amenities.forEach(amenityId => {
      formData.append('amenities[]', amenityId);
    });
    
    // Add images
    selectedImages.value.forEach(image => {
      formData.append('images[]', image);
    });

    const response = await axios.post('/owner/rooms', formData, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
        'Content-Type': 'multipart/form-data'
      }
    });

    if (response.data.success) {
      alert('Room created successfully!');
      closeModal();
      loadRooms(selectedProperty.value.id);
    }
  } catch (error) {
    console.error('Error saving room:', error);
    alert('Failed to save room');
  } finally {
    isSubmitting.value = false;
  }
});

// Edit room
const editRoom = (room) => {
  // Populate form with room data
  Object.assign(roomForm, {
    name: room.name,
    description: room.description,
    default_price: room.default_price,
    capacity: room.capacity,
    bed_type: room.bed_type,
    room_size: room.room_size,
    amenities: room.amenities?.map(a => a.id) || []
  });
  
  showEditModal.value = true;
};

// Delete room
const confirmDelete = (room) => {
  if (confirm(`Are you sure you want to delete "${room.name}"?`)) {
    deleteRoom(room.id);
  }
};

const deleteRoom = async (roomId) => {
  try {
    const response = await axios.delete(`/api/owner/rooms/${roomId}`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    });

    if (response.data.success) {
      alert('Room deleted successfully!');
      loadRooms(selectedProperty.value.id);
    }
  } catch (error) {
    console.error('Error deleting room:', error);
    alert('Failed to delete room');
  }
};

// Close modal
const closeModal = () => {
  showCreateModal.value = false;
  showEditModal.value = false;
  
  // Reset form
  Object.assign(roomForm, {
    name: '',
    description: '',
    default_price: 0,
    capacity: 1,
    bed_type: 'double',
    room_size: null,
    amenities: []
  });
  
  // Clear images
  imagePreview.value.forEach(url => URL.revokeObjectURL(url));
  imagePreview.value = [];
  selectedImages.value = [];
};

// Get room image
const getRoomImage = (room) => {
  if (room.images && room.images.length > 0) {
    return `/storage/${room.images[0].image_url}`;
  }
  return '/src/assets/images/default-room.jpg';
};

// Initialize
onMounted(async () => {
  await Promise.all([
    loadProperties(),
    loadAmenities()
  ]);
});
</script>

<style scoped>
.aspect-w-16 {
  position: relative;
  width: 100%;
  height: 0;
  padding-bottom: 56.25%;
}

.aspect-w-16 img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Scrollbar styling */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
