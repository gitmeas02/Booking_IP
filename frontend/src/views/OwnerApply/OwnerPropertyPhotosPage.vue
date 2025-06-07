<template>
  <div class="max-w-screen-xl mx-auto px-4 py-10 font-sans">
    <!-- Heading -->
    <div class="text-center mb-10">
      <h2 class="text-3xl font-extrabold text-neutral-800">What does your hotel look like?</h2>
    </div>

    <!-- Form Section -->
    <div class="flex flex-col md:flex-row gap-10">
      <!-- Left Column -->
      <div class="flex-1 space-y-6">
        <!-- Form Card -->
        <div class="bg-white p-6 rounded-lg shadow-md space-y-8">
          <!-- Description -->
          <div>
            <h4 class="text-xl font-semibold mb-2">Upload at least 5 photos of your property.</h4>
            <p class="text-sm text-gray-600 mt-1">
              The more you upload, the more likely you are to get bookings. You can add more later.
            </p>
          </div>

          <!-- Drag and Drop Upload -->
          <div
            class="border border-dashed border-gray-400 rounded-md p-6 text-center cursor-pointer hover:bg-gray-50 transition"
            @dragover.prevent="dragActive = true"
            @dragleave.prevent="dragActive = false"
            @drop.prevent="handleDrop"
            :class="{ 'bg-gray-100': dragActive }"
            role="region"
            aria-label="Drag and drop or upload photos"
          >
            <div class="flex flex-col items-center space-y-2">
              <Icon icon="mdi:image" class="text-gray-500" width="48" height="48" />
              <p class="font-semibold">Drag and drop or</p>
              <label
                class="inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-700 rounded cursor-pointer hover:bg-blue-200"
              >
                <input
                  type="file"
                  class="hidden"
                  multiple
                  accept="image/jpeg,image/jpg,image/png"
                  @change="handleFileChange"
                  aria-label="Upload hotel photos"
                />
                Upload photos
              </label>
              <p class="text-xs text-gray-400">jpg/jpeg or png, maximum 47MB each</p>
            </div>
          </div>

          <!-- Error Message -->
          <div v-if="errorMessage" class="text-red-500 text-sm text-center">
            {{ errorMessage }}
          </div>

          <!-- Preview with Remove -->
          <div class="grid grid-cols-3 gap-4 pt-4" v-if="files.length">
            <div v-for="(file, index) in files" :key="index" class="relative group">
              <img :src="file.preview" class="w-full h-28 object-cover rounded" :alt="'Preview of photo ' + (index + 1)" />
              <button
                @click="removeFile(index)"
                class="absolute top-1 right-1 bg-white rounded-full shadow-md p-1 text-red-500 hover:text-red-700 hover:scale-110 transition"
                aria-label="Remove photo"
              >
                <Icon icon="mdi:close-circle" width="20" height="20" />
              </button>
            </div>
          </div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-between">
          <button
            class="px-6 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400"
            @click="handleBack"
          >
            Back
          </button>
          <button
            class="px-6 py-2 text-white bg-black rounded-md shadow-md hover:bg-gray-300 hover:text-black focus:outline-none focus:ring-2 focus:ring-amber-400"
            @click="handleContinue"
            :disabled="files.length < 5"
          >
            Continue
          </button>
        </div>
      </div>

      <!-- Right Column -->
      <div class="flex-1">
        <!-- Optional content -->
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, onUnmounted } from "vue";
import { useValidationStore } from "@/stores/validationStore";
import { useRouter } from "vue-router";
import { Icon } from "@iconify/vue";

export default defineComponent({
  components: { Icon },
  setup() {
    const store = useValidationStore();
    const router = useRouter();
    const files = ref([]);
    const dragActive = ref(false);
    const errorMessage = ref("");
  
        
    //    form.photos.forEach((file, idx) => {
    //   formData.append(`photos[${idx}]`, file);
    // });
    // Validate file type and size
    const validateFile = (file) => {
      const allowedTypes = ["image/jpeg", "image/jpg", "image/png"];
      const maxSize = 47 * 1024 * 1024; // 47MB in bytes
      if (!allowedTypes.includes(file.type)) {
        errorMessage.value = "Only JPG, JPEG, or PNG files are allowed.";
        return false;
      }
      if (file.size > maxSize) {
        errorMessage.value = "File size exceeds 47MB limit.";
        return false;
      }
      return true;
    };

    // Check for duplicate files by name
    const isDuplicate = (file) => {
      return files.value.some((existing) => existing.raw.name === file.name && existing.raw.size === file.size);
    };

    // Process files and generate previews
    const processFiles = (newFiles) => {
      errorMessage.value = "";
      const validFiles = Array.from(newFiles)
        .filter((file) => {
          if (!validateFile(file)) return false;
          if (isDuplicate(file)) {
            errorMessage.value = `Duplicate file detected: ${file.name}`;
            return false;
          }
          return true;
        })
        .map((file) => ({
          raw: file,
          preview: URL.createObjectURL(file),
        }));
      if (validFiles.length > 0) {
        files.value = [...files.value, ...validFiles];
        store.setPropertyValue("photos", files.value.map((f) => f.raw));
      }
    };

    // Handle file input change
    const handleFileChange = (event) => {
      const newFiles = event.target.files;
      if (newFiles.length) {
        processFiles(newFiles);
      }
    };

    // Handle drag and drop
    const handleDrop = (event) => {
      dragActive.value = false;
      const newFiles = event.dataTransfer.files;
      if (newFiles.length) {
        processFiles(newFiles);
      }
    };

    // Remove file and revoke preview URL
    const removeFile = (index) => {
      URL.revokeObjectURL(files.value[index].preview);
      files.value.splice(index, 1);
      store.setPropertyValue("photos", files.value.map((f) => f.raw));
      errorMessage.value = files.value.length < 5 ? "Please upload at least 5 photos." : "";
    };

    // Continue to next page
    const handleContinue = () => {
      if (files.value.length < 5) {
        errorMessage.value = "Please upload at least 5 photos.";
        return;
      }
      errorMessage.value = "";
      // For debugging only; remove in production
      console.log("Current:", store.property);
      router.push({ name: "OwnerPropertyPage9" });
      console.log("Current:", JSON.stringify(store.property, null, 2));
            console.log("Current in Page Payment :", store.property);

    };

    // Go back to previous page
    const handleBack = () => {
      errorMessage.value = "";
      router.push({ name: "OwnerPropertyPage7" });
    };

    // Cleanup previews on component unmount
    onUnmounted(() => {
      files.value.forEach((file) => URL.revokeObjectURL(file.preview));
    });

    return {
      store,
      files,
      dragActive,
      errorMessage,
      handleFileChange,
      handleDrop,
      removeFile,
      handleContinue,
      handleBack,
    };
  },
});
</script>

<style scoped>
input[type="file"] {
  display: none;
}
</style>