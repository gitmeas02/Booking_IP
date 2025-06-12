import { defineStore } from 'pinia';
// import roomImg from '@/assets/Bed/bed.png'; // Adjust path if needed
export const usePhotoStore = defineStore('photo', {
  state: () => ({
    photos: [], // Store File objects
    previews: [], // Store { url: string } objects
    fileInput: null,
    updatePhotoInput: null,
    photoIndexToUpdate: null,
    modalVisible: false,
    currentPhotoIndex: 0,
    maxPhotos: 10,
    maxFileSize: 5 * 1024 * 1024, // 5MB
  }),
  getters: {
    displayedPhotos: (state) => state.previews.slice(0, 5),
    remainingPhotoCount: (state) => Math.max(0, state.previews.length - 5),
  },
  actions: {
    previewImages(files) {
      if (this.photos.length + files.length > this.maxPhotos) {
        alert(`Cannot upload more than ${this.maxPhotos} photos.`);
        return;
      }
      files.forEach((file) => {
        if (!file.type.startsWith('image/')) {
          alert('Only image files are allowed.');
          return;
        }
        if (file.size > this.maxFileSize) {
          alert('File size exceeds 5MB limit.');
          return;
        }
        this.photos.push(file);
        const reader = new FileReader();
        reader.onload = (e) => {
          this.previews.push({ url: e.target.result });
        };
        reader.readAsDataURL(file);
      });
    },
    triggerFileInput() {
      this.fileInput?.click();
    },
    handleFileUpload(event) {
      const files = Array.from(event.target.files);
      this.previewImages(files);
    },
    onDrop(event) {
      const files = Array.from(event.dataTransfer.files);
      this.previewImages(files);
    },
    updatePhoto(index) {
      this.photoIndexToUpdate = index;
      this.updatePhotoInput?.click();
    },
    handlePhotoUpdate(event) {
      const file = event.target.files[0];
      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
          if (this.photoIndexToUpdate !== null) {
            this.photos[this.photoIndexToUpdate] = file;
            this.previews[this.photoIndexToUpdate].url = e.target.result;
          }
        };
        reader.readAsDataURL(file);
      }
    },
    deletePhoto(index) {
      this.photos.splice(index, 1);
      this.previews.splice(index, 1);
    },
    openPhotoModal(index) {
      this.currentPhotoIndex = index;
      this.modalVisible = true;
    },
    closeModal() {
      this.modalVisible = false;
    },
    nextPhoto() {
      if (this.currentPhotoIndex < this.previews.length - 1) {
        this.currentPhotoIndex += 1;
      }
    },
    prevPhoto() {
      if (this.currentPhotoIndex > 0) {
        this.currentPhotoIndex -= 1;
      }
    },
    clearAllPhotos() {
      this.photos = [];
      this.previews = [];
      this.currentPhotoIndex = 0;
    },
  },
});