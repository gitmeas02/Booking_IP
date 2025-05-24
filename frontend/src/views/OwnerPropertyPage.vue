<template>
    <div class="max-w-screen-xl mx-auto px-4 py-10 text-center font-sans">
        <h2 class="text-3xl font-extrabold text-neutral-800 mb-3">
            List your property and start welcoming guests in<br />
            <span class="text-neutral-800">no time!</span>
        </h2>

        <p class="text-gray-600 text-base mb-8">
            To get started, select the type of property you want to list.
        </p>

        <div class="grid gap-6 justify-items-center grid-cols-[repeat(auto-fit,minmax(260px,1fr))]">
            <Card v-for="(card, index) in cardData" :key="index" v-bind="card" @apply="handleCardClick" />
        </div>
    </div>
</template>

<script setup>
import Card from '@/components/Owner/Card.vue';
import axios from 'axios';

const cardData = [
  {
    icon: 'material-symbols:apartment',
    title: 'Apartment',
    description: 'Furnished and self-catering accommodations where guests rent the entire place.'
  },
  {
    icon: 'material-symbols:house',
    title: 'Homes',
    description: 'Properties like apartments, vacation homes, villas, etc.'
  },
  {
    icon: 'material-symbols:hotel',
    title: 'Hotel and More',
    description: 'Properties like hotels, guest houses, hostels, condo hotels, etc.'
  },
  {
    icon: 'material-symbols:camping',
    title: 'Alternative Places',
    description: 'Properties like boats, campgrounds, luxury tents, etc.'
  }
];

const handleCardClick = async (propertyType) => {
  try {
    const token = localStorage.getItem('token');

    const res = await axios.post(
      'http://localhost:8000/api/apply-owner',
      {
        property_type: propertyType,
        fit_category: 'General',
        property_name: `${propertyType} Test`,
        is_company: false,
        stars: 3,

        location: {
          street: 'Example St.',
          floor: '2nd',
          country: 'Cambodia',
          city: 'Phnom Penh',
          zip_code: '12000'
        },

        amenities: [],

        services: {
          breakfast: true,
          parking: false
        },

        rules: {
          checkin_from: '14:00',
          checkin_to: '21:00',
          checkout_from: '06:00',
          checkout_to: '12:00',
          allow_pet: false
        },

        photos: [], // skip file upload

        payment: {
          at_property: true,
          online: true,
          pteas_khmer: false
        },

        identity: {
          is_individual_owner: true,
          first_name: 'Jane',
          last_name: 'Doe',
          middle_name: '',
          first_name_id: 'Jane',
          last_name_id: 'Doe',
          middle_name_id: '',
          email: 'jane.doe@example.com',
          phone: '012345678',
          country: 'Cambodia',
          address1: '123 Main St.',
          address2: ''
        }
      },
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    );

    alert('Submitted application for ' + propertyType);
    console.log(res.data);
  } catch (error) {
    console.error(error);
    alert('Failed to apply as owner.');
  }
};
</script>