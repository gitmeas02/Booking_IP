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
          >
            <div class="flex flex-col items-center space-y-2">
              <!-- Iconify Upload Icon -->
              <Icon icon="mdi:image" class="text-gray-500" width="48" height="48" />

              <p class="font-semibold">Drag and drop or</p>
              <label
                class="inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-700 rounded cursor-pointer hover:bg-blue-200">
                <input type="file" class="hidden" multiple accept="image/jpeg,image/png" @change="handleFileChange">
                Upload photos
              </label>
              <p class="text-xs text-gray-400">jpg/jpeg or png, maximum 47MB each</p>
            </div>
          </div>

          <!-- Preview with Remove -->
          <div class="grid grid-cols-3 gap-4 pt-4" v-if="files.length">
            <div v-for="(file, index) in files" :key="index" class="relative group">
              <img :src="file.preview" class="w-full h-28 object-cover rounded" />
              <!-- Remove icon -->
              <button
                @click="removeFile(index)"
                class="absolute top-1 right-1 bg-white rounded-full shadow-md p-1 text-red-500 hover:text-red-700 hover:scale-110 transition"
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
            @click="handleBack">
            Back
          </button>
          <button
            class="px-6 py-2 text-white bg-black rounded-md shadow-md hover:bg-gray-300 hover:text-black focus:outline-none focus:ring-2 focus:ring-amber-400"
            @click="handleContinue"
            :disabled="files.length < 5">
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
import { Icon } from '@iconify/vue';

export default {
  components: { Icon },
  data() {
    return {
      dragActive: false,
      files: []
    };
  },
  methods: {
    handleFileChange(event) {
      const selectedFiles = Array.from(event.target.files);
      this.previewFiles(selectedFiles);
    },
    handleDrop(event) {
      this.dragActive = false;
      const droppedFiles = Array.from(event.dataTransfer.files);
      this.previewFiles(droppedFiles);
    },
    previewFiles(fileList) {
      fileList.forEach(file => {
        if (['image/jpeg', 'image/png'].includes(file.type) && file.size <= 47 * 1024 * 1024) {
          const reader = new FileReader();
          reader.onload = e => {
            this.files.push({ file, preview: e.target.result });
          };
          reader.readAsDataURL(file);
        }
      });
    },
    removeFile(index) {
      this.files.splice(index, 1);
    },
    handleBack() {
      // Navigation logic here
    },
    handleContinue() {
      // Submission logic here
      alert(`Continue clicked with ${this.files.length} files.`);
    }
  }
};
</script>

<style scoped>
input[type="file"] {
  display: none;
}
</style>
