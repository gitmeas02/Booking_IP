<template>
  <div class="border-gray-300 border rounded-lg min-w-[300px]">
      <div class="bg-gradient-to-r from-blue-800 to-blue-600 text-white p-6 rounded-t-lg shadow-md">
        <h3 class="text-2xl font-extrabold tracking-wide">Booking Summary</h3>
      </div>
      <div class="p-6">
        <div class="space-y-4">
          <div>
            <h3 class="font-bold text-2xl ">Khun Hotel</h3>
            <p class="text-muted-foreground">Siem Reap, Cambodia</p>
            <p class="text-sm mt-1">Luxury boutique hotel near Angkor Wat</p>
          </div>
          
          <div class="border-t pt-4">
            <div class="flex justify-between py-1">
              <span>Check-in</span>
              <span class="font-medium">April 10, 2025</span>
            </div>
            <div class="flex justify-between py-1">
              <span>Check-out</span>
              <span class="font-medium">April 13, 2025</span>
            </div>
            <div class="flex justify-between py-1">
              <span>Nights</span>
              <span class="font-medium">3</span>
            </div>
            <div class="flex justify-between py-1">
              <span>Room Type</span>
              <span class="font-medium">Deluxe Double</span>
            </div>
            <div class="flex justify-between py-1">
              <span>Guests</span>
              <span class="font-medium">2 Adults</span>
            </div>
          </div>
          
          <div class="border-t pt-4">
            <div class="flex justify-between py-1">
              <span>Room Rate</span>
              <span>$100 × 3 nights</span>
            </div>
            <div class="flex justify-between py-1">
              <span>Taxes & Fees</span>
              <span>$45</span>
            </div>
          </div>
          
          <div class="border-t pt-4">
            <div class="flex justify-between font-bold text-lg">
              <span>Total</span>
              <span>$345</span>
            </div>
            <p class="text-sm text-muted-foreground mt-2">Includes all taxes and fees</p>
          </div>
        </div>
        <div class="text-sm space-y-2">
          <p class="font-medium">Cancellation Policy</p>
          <p>Free cancellation until April 7, 2025. After that, the first night will be charged.</p>
        </div>
      </div>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    
  setup() {
    const form = ref({
      firstName: '',
      lastName: '',
      email: '',
      phone: '',
      specialRequests: '',
      terms: false,
    });

    const errors = ref({});

    const validate = () => {
      errors.value = {};

      if (!form.value.firstName) {
        errors.value.firstName = 'First name is required';
      }
      if (!form.value.lastName) {
        errors.value.lastName = 'Last name is required';
      }
      if (!form.value.email) {
        errors.value.email = 'Email is required';
      } else if (!/\S+@\S+\.\S+/.test(form.value.email)) {
        errors.value.email = 'Invalid email';
      }
      if (!form.value.phone) {
        errors.value.phone = 'Phone number is required';
      }
      if (!form.value.terms) {
        errors.value.terms = 'You must accept the terms';
      }

      return Object.keys(errors.value).length === 0;
    };

    const onSubmit = () => {
      if (validate()) {
        console.log('Form submitted:', form.value);
      }
    };

    return {
      form,
      errors,
      onSubmit,
    };
  },
};
</script>
