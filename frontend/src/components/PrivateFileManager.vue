<template>
  <div class="private-file-manager p-6">
    <h2 class="text-2xl font-bold mb-4">Private File Manager</h2>
    
    <!-- Upload Section -->
    <div class="mb-6 p-4 border rounded-lg">
      <h3 class="text-lg font-semibold mb-3">Upload Private File</h3>
      <div class="flex items-center gap-4">
        <input 
          type="file" 
          ref="fileInput" 
          @change="handleFileSelect"
          class="flex-1"
        />
        <input 
          type="text" 
          v-model="uploadDirectory" 
          placeholder="Directory (optional)"
          class="px-3 py-2 border rounded"
        />
        <button 
          @click="uploadFile"
          :disabled="!selectedFile || uploading"
          class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:bg-gray-400"
        >
          {{ uploading ? 'Uploading...' : 'Upload' }}
        </button>
      </div>
      <div v-if="uploadMessage" class="mt-2 text-sm" :class="uploadSuccess ? 'text-green-600' : 'text-red-600'">
        {{ uploadMessage }}
      </div>
    </div>

    <!-- File List Section -->
    <div class="mb-6 p-4 border rounded-lg">
      <h3 class="text-lg font-semibold mb-3">Private Files</h3>
      <div class="flex items-center gap-4 mb-4">
        <input 
          type="text" 
          v-model="listDirectory" 
          placeholder="Directory to list (optional)"
          class="px-3 py-2 border rounded"
        />
        <button 
          @click="listFiles"
          :disabled="loading"
          class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 disabled:bg-gray-400"
        >
          {{ loading ? 'Loading...' : 'List Files' }}
        </button>
      </div>
      
      <div v-if="files.length > 0" class="space-y-2">
        <div 
          v-for="file in files" 
          :key="file"
          class="flex items-center justify-between p-3 bg-gray-50 rounded"
        >
          <span class="flex-1">{{ file }}</span>
          <div class="flex gap-2">
            <button 
              @click="viewFile(file)"
              class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600"
            >
              View
            </button>
            <button 
              @click="downloadFile(file)"
              class="px-3 py-1 bg-green-500 text-white rounded text-sm hover:bg-green-600"
            >
              Download
            </button>
            <button 
              @click="deleteFile(file)"
              class="px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
      
      <div v-else-if="!loading" class="text-gray-500">
        No files found in the specified directory.
      </div>
    </div>

    <!-- File Preview Modal -->
    <div v-if="showPreview" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-4xl max-h-[80vh] overflow-auto">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">File Preview: {{ previewFile }}</h3>
          <button 
            @click="closePreview"
            class="text-gray-500 hover:text-gray-700"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <div v-if="previewUrl" class="text-center">
          <img 
            v-if="previewType && previewType.startsWith('image/')" 
            :src="previewUrl" 
            alt="Preview" 
            class="max-w-full max-h-96 object-contain"
          />
          <video 
            v-else-if="previewType && previewType.startsWith('video/')" 
            :src="previewUrl" 
            controls 
            class="max-w-full max-h-96"
          />
          <div v-else class="text-gray-500">
            Cannot preview this file type. Use download to view the file.
          </div>
        </div>
        
        <div v-else class="text-center text-gray-500">
          Loading preview...
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import MinIOPrivateFileService from '@/services/MinIOPrivateFileService.js';
import { onMounted, onUnmounted, ref } from 'vue';

// Initialize the service
const minioService = new MinIOPrivateFileService();

// Reactive variables
const selectedFile = ref(null);
const uploadDirectory = ref('');
const uploading = ref(false);
const uploadMessage = ref('');
const uploadSuccess = ref(false);

const files = ref([]);
const listDirectory = ref('');
const loading = ref(false);

const showPreview = ref(false);
const previewFile = ref('');
const previewUrl = ref('');
const previewType = ref('');

// Set authentication token (you should get this from your auth system)
onMounted(() => {
  // Get token from localStorage, Vuex, or wherever you store it
  const token = localStorage.getItem('auth_token');
  if (token) {
    minioService.setToken(token);
  }
});

// Handle file selection
const handleFileSelect = (event) => {
  selectedFile.value = event.target.files[0];
  uploadMessage.value = '';
};

// Upload file
const uploadFile = async () => {
  if (!selectedFile.value) return;

  uploading.value = true;
  uploadMessage.value = '';

  try {
    const result = await minioService.uploadPrivateFile(
      selectedFile.value,
      uploadDirectory.value
    );

    if (result.success) {
      uploadMessage.value = 'File uploaded successfully!';
      uploadSuccess.value = true;
      selectedFile.value = null;
      uploadDirectory.value = '';
      // Reset file input
      if (fileInput.value) {
        fileInput.value.value = '';
      }
      // Refresh file list
      listFiles();
    } else {
      uploadMessage.value = result.message || 'Upload failed';
      uploadSuccess.value = false;
    }
  } catch (error) {
    uploadMessage.value = 'Upload error: ' + error.message;
    uploadSuccess.value = false;
  } finally {
    uploading.value = false;
  }
};

// List files
const listFiles = async () => {
  loading.value = true;
  
  try {
    const result = await minioService.listPrivateFiles(listDirectory.value);
    
    if (result.success) {
      files.value = result.files;
    } else {
      console.error('Failed to list files:', result.message);
      files.value = [];
    }
  } catch (error) {
    console.error('Error listing files:', error);
    files.value = [];
  } finally {
    loading.value = false;
  }
};

// View file
const viewFile = async (filePath) => {
  previewFile.value = filePath;
  showPreview.value = true;
  previewUrl.value = '';
  previewType.value = '';

  try {
    const result = await minioService.createObjectUrl(filePath);
    
    if (result.success) {
      previewUrl.value = result.url;
      previewType.value = result.contentType;
    } else {
      console.error('Failed to create preview:', result.message);
    }
  } catch (error) {
    console.error('Error creating preview:', error);
  }
};

// Download file
const downloadFile = async (filePath) => {
  try {
    await minioService.downloadPrivateFile(filePath);
  } catch (error) {
    console.error('Error downloading file:', error);
  }
};

// Delete file
const deleteFile = async (filePath) => {
  if (!confirm(`Are you sure you want to delete ${filePath}?`)) {
    return;
  }

  try {
    const result = await minioService.deletePrivateFile(filePath);
    
    if (result.success) {
      // Refresh file list
      listFiles();
    } else {
      alert('Failed to delete file: ' + result.message);
    }
  } catch (error) {
    alert('Error deleting file: ' + error.message);
  }
};

// Close preview
const closePreview = () => {
  showPreview.value = false;
  if (previewUrl.value) {
    URL.revokeObjectURL(previewUrl.value);
  }
  previewUrl.value = '';
  previewType.value = '';
  previewFile.value = '';
};

// Cleanup on unmount
onUnmounted(() => {
  if (previewUrl.value) {
    URL.revokeObjectURL(previewUrl.value);
  }
});

// Load files on mount
onMounted(() => {
  listFiles();
});

// Template refs
const fileInput = ref(null);
</script>

<style scoped>
.private-file-manager {
  max-width: 800px;
  margin: 0 auto;
}
</style>
