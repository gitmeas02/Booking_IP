<template>
  <div class="container">
    <div class="property-card">
      <!-- Header -->
      <div class="header">
        <h1>Manager Hotel</h1>
        <div class="user-icon"></div>
      </div>

      <!-- Navigation -->
      <div class="navigation">
        <button class="nav-button">Home</button>
      </div>

      <!-- Photo Upload Section -->
      <div
        class="upload-area"
        @dragover.prevent="onDragOver"
        @drop.prevent="onDrop"
        @click="triggerFileInput"
      >
        <div class="upload-content">
          <p>Click or Drag & Drop to Upload Your Photo</p>
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

      <!-- Selection -->
      <div class="form-column space-y-4 p-4 bg-white rounded-2xl shadow-md">
        <!-- Room Info -->
        <!-- Room Count -->
        <div class="flex w-screen flex-col">
          <label
            for="roomCount"
            class="block text-sm font-medium text-gray-700 mb-1"
            >Number of Rooms</label
          >
          <input
            id="roomCount"
            type="number"
            placeholder="Enter number of rooms (e.g., 5)"
            class="form-input w-full border border-gray-300 p-2 focus:ring focus:ring-blue-200"
            v-model="roomCount"
            required
          />
        </div>

        <div class="flex w-screen flex-col">
          <label
            for="roomType"
            class="block text-sm font-medium text-gray-700 mb-1"
            >Room Type</label
          >
          <input
            type="text"
            class="form-input w-full border border-gray-300 p-2 focus:ring focus:ring-blue-200"
            placeholder="Input Room Type eg. Family Room"
            v-model="roomType"
            required
          />
        </div>

        <!-- Room Capacity -->
        <div class="flex w-screen flex-col">
          <label
            for="peopleCapacity"
            class="block text-sm font-medium text-gray-700 mb-1"
            >Room Capacity (People)</label
          >
          <input
            id="peopleCapacity"
            type="number"
            class="form-input w-full border border-gray-300 p-2 focus:ring focus:ring-blue-200"
            placeholder="How many people can stay? (e.g., 5)"
            v-model="peopleCapacity"
            required
          />
        </div>

        <!-- Price and Discount -->
        <div class="flex justify-center items-center">
          <!-- Price Control -->
          <div class="border-l-2 border-r-2 border-black pl-6 pr-6">
            <label class="block text-sm font-medium text-gray-700 mb-1"
              >Set Price</label
            >
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
      </div>

      <!-- Tab Navigation -->
      <div class="tab-nav pt-10">
        <div class="tab active" @click="toggleEditMode">
          {{ isEditing ? "Save" : "Edit" }}
        </div>
      </div>

      <!-- Photo List Mode -->
      <div v-if="isEditing" class="photo-list-edit">
        <div
          v-for="(photo, index) in photos"
          :key="index"
          class="photo-list-item"
        >
          <img :src="photo.url" alt="Room photo" class="photo-thumb" />
          <div class="photo-buttons">
            <button class="btn delete" @click="deletePhoto(index)">
              Delete
            </button>
            <button class="btn update" @click="updatePhoto(index)">
              Update
            </button>
          </div>
        </div>

        <!-- Hidden input for updating -->
        <input
          ref="updatePhotoInput"
          type="file"
          accept="image/*"
          @change="handlePhotoUpdate"
          class="file-input"
        />
      </div>

      <!-- Grid View Mode -->
      <div v-else class="photo-gallery">
        <template :key="index" v-for="(photo, index) in displayedPhotos">
          <div class="photo-container" @click="openPhotoModal(index)">
            <img :src="photo.url" alt="Room photo" class="room-photo" />
            <div
              v-if="index === 4 && remainingPhotoCount > 0"
              class="photo-count"
            >
              {{ remainingPhotoCount }}+ Photos
            </div>
          </div>
        </template>
      </div>

      <!-- Form Controls -->
      <div class="form-layout">
        <!-- Facilities Checkboxes -->
        <div class="p-4">
          <p class="text-2xl font-bold mb-6">Facilities</p>
        </div>
        <div class="facilities-container">
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
      <div class="description-container">
        <textarea
          placeholder="description"
          class="description-input"
          v-model="description"
        ></textarea>
      </div>

      <!-- Submit Button -->
      <div class="button-container mt-3">
        <button class="host-button" @click="submitForm">Host Property</button>
      </div>
    </div>

    <!-- Modal Viewer -->
    <div v-if="modalVisible" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <button class="modal-close" @click="closeModal">
          <Icon icon="charm:cross" />
        </button>
        <button
          class="modal-nav left"
          @click="prevPhoto"
          :disabled="currentPhotoIndex === 0"
        >
          <Icon icon="mingcute:left-fill" />
        </button>
        <img
          :src="photos[currentPhotoIndex].url"
          class="modal-image rounded2xl"
        />
        <button
          class="modal-nav right"
          @click="nextPhoto"
          :disabled="currentPhotoIndex === photos.length - 1"
        >
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
  name: "UploadProperty",
  components: {
    Icon,
  },
  setup() {
    const peopleCapacity = ref(1);
    const roomCount = ref(1);
    const roomType = ref("");
    const price = ref(0);
    const description = ref("");
    const isEditing = ref(false);
    const updatePhotoInput = ref(null);
    const photoIndexToUpdate = ref(null);
    const fileInput = ref(null);
    const modalVisible = ref(false);
    const currentPhotoIndex = ref(0);
    const amenitiesData = ref([]);
    const isLoadingAmenities = ref(false);
    const photos = ref([]);
    const maxPhotos = 10;
    const maxFileSize = 5 * 1024 * 1024; // 5MB
    const appId = 1;
    const URL = import.meta.env.VITE_API_BASE_URL;
    console.log("API Base URL:", URL);

    const displayedPhotos = computed(() => photos.value.slice(0, 5));
    const remainingPhotoCount = computed(() => photos.value.length - 5);

    async function getAmenities(retries = 3, delay = 1000) {
      isLoadingAmenities.value = true;
      for (let attempt = 1; attempt <= retries; attempt++) {
        try {
          const res = await axios.get(`${URL}/amenities`);
          console.log("Amenities API response:", res.data.data);
          if (!Array.isArray(res.data.data)) {
            console.error(
              "Expected an array, got:",
              typeof res.data.data,
              res.data.data
            );
            amenitiesData.value = [];
            alert("Invalid amenities data received. Please contact support.");
            return;
          }
          amenitiesData.value = res.data.data.map((group) => ({
            ...group,
            amenities: Array.isArray(group.amenities)
              ? group.amenities.map((a) => ({
                  ...a,
                  selected: false,
                }))
              : [],
          }));
          return;
        } catch (error) {
          console.error(`Attempt ${attempt} failed:`, {
            message: error.message,
            response: error.response?.data,
            status: error.response?.status,
            config: error.config,
          });
          if (attempt === retries) {
            alert(
              "Failed to load amenities after multiple attempts. Please try again."
            );
            amenitiesData.value = [];
          } else {
            await new Promise((resolve) => setTimeout(resolve, delay));
          }
        } finally {
          isLoadingAmenities.value = false;
        }
      }
    }

    async function submitForm() {
      const formData = new FormData();

      // Build rooms array
      const rooms = [];
      for (let i = 1; i <= roomCount.value; i++) {
        const roomName =
          roomCount.value > 1 ? `${roomType.value} #${i}` : roomType.value;
        const room = {
          name: roomName,
          capacity: peopleCapacity.value,
          default_price: price.value,
          description: description.value || null,
          amenities: amenitiesData.value
            .flatMap((group) => group.amenities)
            .filter((a) => a.selected)
            .map((a) => a.id),
        };
        rooms.push(room);
      }

      // Validate required fields
      if (!roomCount.value || !roomType.value || !peopleCapacity.value) {
        alert("Please fill in all required fields.");
        return;
      }

      if (!rooms[0].amenities.length) {
        alert("Please select at least one amenity.");
        return;
      }

      if (!photos.value.length) {
        alert("Please upload at least one image.");
        return;
      }

      // Append rooms to FormData with proper indexing
      rooms.forEach((room, index) => {
        formData.append(`rooms[${index}][name]`, room.name);
        formData.append(`rooms[${index}][capacity]`, room.capacity);
        formData.append(`rooms[${index}][default_price]`, room.default_price);
        if (room.description) {
          formData.append(`rooms[${index}][description]`, room.description);
        }
        room.amenities.forEach((amenityId, amenityIndex) => {
          formData.append(
            `rooms[${index}][amenities][${amenityIndex}]`,
            amenityId
          );
        });
      });

      // Append images for each room
      photos.value.forEach((photo, index) => {
        formData.append(`rooms[0][images][${index}]`, photo.file);
      });

      try {
        const token = localStorage.getItem("token");
        if (!token) {
          alert("You must be logged in to submit.");
          return;
        }
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        const res = await axios.post(
          `${URL}/owner/property/${appId}/room`,
          formData,
          {
            headers: { "Content-Type": "multipart/form-data" },
          }
        );
        console.log("Property created:", res.data);
        alert("Property hosted successfully!");
      } catch (error) {
        console.error(
          "Failed to host property:",
          error.response?.data || error
        );
        const errors = error.response?.data?.errors;
        if (errors) {
          const errorMessages = Object.values(errors).flat().join("\n");
          alert(`Failed to host property:\n${errorMessages}`);
        } else {
          alert("Failed to host property. Please try again.");
        }
      }
    }

    const previewImages = (files) => {
      if (photos.value.length + files.length > maxPhotos) {
        alert(`Cannot upload more than ${maxPhotos} photos.`);
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
          photos.value.push({ url: e.target.result, file }); // Store both URL and File
        };
        reader.readAsDataURL(file);
      });
      isEditing.value = true;
    };

    onMounted(() => {
      getAmenities();
    });

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

    const onDragOver = () => {};

    const toggleEditMode = () => {
      isEditing.value = !isEditing.value;
    };

    const updatePhoto = (index) => {
      photoIndexToUpdate.value = index;
      updatePhotoInput.value?.click();
    };

    const handlePhotoUpdate = (event) => {
      const file = event.target.files[0];
      if (file && file.type.startsWith("image/")) {
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
      roomCount,
      roomType,
      price,
      description,
      isEditing,
      updatePhotoInput,
      photoIndexToUpdate,
      fileInput,
      modalVisible,
      currentPhotoIndex,
      photos,
      amenitiesData,
      displayedPhotos,
      remainingPhotoCount,
      increasePrice,
      decreasePrice,
      triggerFileInput,
      handleFileUpload,
      peopleCapacity,
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
      submitForm,
      isLoadingAmenities,
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
  padding: 8px 30px;
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

/* ========================
   Form Section
======================== */
.form-column {
  display: flex;
  gap: 15px;
  align-items: center;
  justify-content: space-between;
}

/* ========================
   Facilities Section
======================== */
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
  font-size: 40px;
  display: flex;
  justify-content: center;
  cursor: pointer;
  align-items: center;
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
  background: rgb(150, 40, 40);
  padding: 8px 20px;
  border-radius: 3px;
  cursor: pointer;
  color: white;
}

.host-button:hover {
  background-color: #f5f5f5;
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

.modal-img-container {
  flex: 1 1 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
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
