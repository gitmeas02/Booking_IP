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
        <div class="flex w-screen flex-col">
          <label
            for="roomNumber"
            class="block text-sm font-medium text-gray-700 mb-1"
            >Room Number</label
          >
          <input
            id="roomNumber"
            type="text"
            placeholder="Enter total rooms (e.g., 5)"
            class="form-input w-full border border-gray-300 p-2 focus:ring focus:ring-blue-200"
            v-model="roomNumber"
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
          />
        </div>
          <div class="flex w-screen flex-col">
          <label
            for="roomType"
            class="block text-sm font-medium text-gray-700 mb-1"
            >People</label
          >
          <input
            type="text"
            class="form-input w-full border border-gray-300 p-2 focus:ring focus:ring-blue-200"
            placeholder="How Many People Can Stay? eg.(5)"
            v-model="people"
          />
        </div>

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
            <div class="text-lg font-semibold w-20 text-center">
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

        <!-- Hotel Selection -->
      </div>
      <!-- Selection -->
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
      <div class="">
          <div class=" grid grid-cols-1 md:grid-cols-2">
          <div
            v-for="(group, i) in facilitiesGrouped"
            :key="group.category"
            class=" p-2"
          >
            <h2 class="text-xl font-semibold mb-4">{{ group.category }}</h2>
            <div class="grid grid-cols-2 md:grid-cols-3  gap-3">
              <label
                v-for="(item, j) in group.items"
                :key="item.name"
                class="flex items-center space-x-1 cursor-pointer font-mono"
              >
                <input
                  type="checkbox"
                  v-model="item.selected"
                  class="form-checkbox text-indigo-600"
                />
                <span class="text-gray-700">{{ item.name }}</span>
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
        <button class="host-button">Host Property</button>
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
import { ref, computed } from "vue";
import { Icon } from "@iconify/vue";
import roomImg from "@/assets/Bed/room.png";

export default {
  name: "UploadProperty",
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

  setup() {
    // ========================
    // Reactive Variables
    // ========================
    const people = ref("");
    const roomNumber = ref("");
    const roomType = ref("");
    const price = ref(77);
    const selectedHotel = ref("");
    const description = ref("");
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
      { url: roomImg },
      { url: roomImg },
      { url: roomImg },
      { url: roomImg },
      { url: roomImg },
      { url: roomImg },
      { url: roomImg },
    ]);

    const hotels = ref([
      { id: 1, name: "Grand Hotel" },
      { id: 2, name: "City Center Hotel" },
      { id: 3, name: "Beach Resort" },
    ]);
    const chunkedGroups = computed(() => {
      const chunks = [];
      for (let i = 0; i < facilitiesGrouped.value.length; i += 2) {
        chunks.push(facilitiesGrouped.value.slice(i, i + 2));
      }
      return chunks;
    });
    const facilitiesGrouped = ref([
      {
        category: "Room",
        items: [
          { name: "Air conditioning", selected: false },
          { name: "Heating", selected: false },
          { name: "Free Wi-Fi", selected: false },
          { name: "Flat-screen TV", selected: false },
          { name: "Safe", selected: false },
          { name: "Mini fridge", selected: false },
          { name: "Microwave", selected: false },
          { name: "Coffee/tea maker", selected: false },
          { name: "Balcony or terrace", selected: false },
        ],
      },
      {
        category: "Bathroom",
        items: [
          { name: "Private bathroom", selected: false },
          { name: "Shower", selected: false },
          { name: "Bathtub", selected: false },
          { name: "Towels", selected: false },
          { name: "Hairdryer", selected: false },
          { name: "Toiletries", selected: false },
          { name: "Hot water", selected: false },
        ],
      },
      {
        category: "Kitchen",
        items: [
          { name: "Kitchen", selected: false },
          { name: "Kitchenette", selected: false },
          { name: "Stove", selected: false },
          { name: "Oven", selected: false },
          { name: "Toaster", selected: false },
          { name: "Dishwasher", selected: false },
        ],
      },
      {
        category: "Leisure & Wellness",
        items: [
          { name: "Swimming pool", selected: false },
          { name: "Hot tub", selected: false },
          { name: "Spa", selected: false },
          { name: "Sauna", selected: false },
          { name: "Fitness center", selected: false },
          { name: "Beachfront", selected: false },
        ],
      },
      {
        category: "Property",
        items: [
          { name: "24-hour front desk", selected: false },
          { name: "Daily housekeeping", selected: false },
          { name: "Elevator", selected: false },
          { name: "Free parking", selected: false },
          { name: "Pet-friendly", selected: false },
        ],
      },
      {
        category: "Business",
        items: [
          { name: "Business center", selected: false },
          { name: "Meeting room", selected: false },
          { name: "High-speed internet", selected: false },
        ],
      },
      {
        category: "Food & Beverage",
        items: [
          { name: "Restaurant", selected: false },
          { name: "Bar", selected: false },
          { name: "Room service", selected: false },
          { name: "Breakfast included", selected: false },
          { name: "Mini-market", selected: false },
        ],
      },
      {
        category: "Accessibility",
        items: [
          { name: "Wheelchair accessible", selected: false },
          { name: "Accessible bathroom", selected: false },
        ],
      },
      {
        category: "Transportation",
        items: [
          { name: "Airport shuttle", selected: false },
          { name: "Car rental", selected: false },
          { name: "EV charging station", selected: false },
        ],
      },
      {
        category: "Family & Kids",
        items: [
          { name: "Baby cot", selected: false },
          { name: "Kids’ play area", selected: false },
          { name: "Board games", selected: false },
        ],
      },
      {
        category: "Pet",
        items: [
          { name: "Pet bowls", selected: false },
          { name: "Pet bed", selected: false },
        ],
      },
      {
        category: "Safety",
        items: [
          { name: "Smoke alarms", selected: false },
          { name: "Fire extinguishers", selected: false },
          { name: "CCTV", selected: false },
        ],
      },
    ]);

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
        if (file.type.startsWith("image/")) {
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
      facilitiesGrouped,
      //   chunkedGroups,
      //   facilities,
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
/* .form-layout {
  display: flex;
  align-items: start;
  justify-content: space-between;
  flex-direction: row;
  margin: 10px 50px;
} */

/* @media (max-width: 768px) {
  .form-layout {
    grid-template-columns: 1fr;
  }
} */

.form-column {
  display: flex;
  gap: 15px;
  align-items: center;
  justify-content: space-between;
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
   Facilities Grid
======================== */
/* .facilities-section {
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
} */

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
