import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';
import { useAmenitiesStore } from './amenitiesStore';
import { usePhotosStore } from './usePhotosStore';

export const useRoomFormStore = defineStore('roomForm', () => {
  const roomNumber = ref(1);
  const roomType = ref('');
  const people = ref(1);
  const price = ref(0);
  const percentage = ref(0);
  const description = ref('');

  const URL = import.meta.env.VITE_API_BASE_URL;

  const submitForm = async () => {
    const photoStore = usePhotosStore();
    const amenitiesStore = useAmenitiesStore();

    const formData = {
      roomNumber: roomNumber.value,
      roomType: roomType.value,
      people: people.value,
      price: price.value,
      discountPercentage: percentage.value,
      description: description.value,
      photos: photoStore.photos.map((p) => p.url),
      amenities: amenitiesStore.amenitiesData.flatMap((group) =>
        group.amenities
          .filter((a) => a.selected)
          .map((a) => ({ id: a.id, name: a.amenity_name }))
      ),
    };

    if (!formData.roomNumber || !formData.roomType || !formData.people) {
      alert('Please fill in all required fields.');
      return;
    }

    if (!formData.amenities.length) {
      alert('Please select at least one amenity.');
      return;
    }

    try {
      const res = await axios.post(`${URL}/properties`, formData);
      alert('Property hosted successfully!');
      console.log('Property created:', res.data);
    } catch (error) {
      console.error('Failed to host property:', error);
      alert('Failed to host property. Please try again.');
    }
  };

  return {
    roomNumber,
    roomType,
    people,
    price,
    percentage,
    description,
    submitForm,
  };
});
