import { defineStore } from "pinia";
import roomImg from '@/assets/Bed/bed.png';
import { computed, readonly } from "vue";
export const usePhotosStore = defineStore('photos',()=>{
    const photos = ref([]);
    const maxPhotos =10 ;
    const maxFileSize = 5* 1024*1024;

    const isEditing = ref(false);
    const modalVisible = ref(false);
    const currentPhotoIndex = ref(0);
    const updatePhotoInput = ref(null);
    const photoIndexToUpdate = ref(null);
    const fileInput = ref(null);
    
    const displayedPhotos = computed(() => photos.value.slice(0, 5));
    const remainingPhotoCount = computed(() => photos.value.length - 5);

    const previewImages = (files)=>{
        if(photos.value.length + files.length > maxPhotos){
            alert(`Cannot uplaod more than ${maxPhotos} photos.`);
            return 
        }
        files.forEach((file)=>{
        if(!file.type.startsWith('image/')|| file.size> maxFileSize){
        alert('Only image files are allowed.');
            return;
        }
        if(file.size > maxFileSize){
            alert('File size exceeds 5MB limit.');
            return;
        }
        const reader = new FileReader ();
        reader.onload=(e)=>{
            photos.value.push({url:e.target.result});
        }
        });
        isEditing.value=true
    }
    const handleFileUpload =(event)=>{
        const files = Array.from(event.dataTransfer.files);
        previewImages(files);
    }

     const updatePhoto = (index) => {
      photoIndexToUpdate.value = index;
      updatePhotoInput.value?.click();
    };
    const deletePhoto = (index) => {
      photos.value.splice(index, 1);
    };

    const openPhotoModal = (index) => {
      currentPhotoIndex.value = index;
      modalVisible.value = true;
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
    const closeModal = () => {
      modalVisible.value = false;
    };
    const triggerFileInput = () => {
      fileInput.value?.click();
    };
    return {
    photos,
    isEditing,
    modalVisible,
    currentPhotoIndex,
    updatePhotoInput,
    photoIndexToUpdate,
    fileInput,
    displayedPhotos,
    remainingPhotoCount,
    previewImages,
    triggerFileInput,
    handleFileUpload,
    updatePhoto,
    handlePhotoUpdate,
    deletePhoto,
    openPhotoModal,
    closeModal,
    nextPhoto,
    prevPhoto,
  };
});