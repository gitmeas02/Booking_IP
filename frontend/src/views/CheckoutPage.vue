<template>
  <!-- Hotel Information -->
  <div class="grid responsive-grid mt-8 mb-4 mx-4 md:mx-16 items-start grid-cols-1 md:grid-cols-2
    gap-8 border-gray-200 border-1 rounded-[12px] p-8">
    <!-- Hotel Details -->
    <div>
      <h2 class="text-2xl font-bold mb-4">Hotel Details</h2>
      <div class="relative h-48 mb-4 rounded-lg overflow-hidden">
        <img
          src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
          alt="Khun Hoth Hotel"
          class="h-full w-full object-cover"
        />
      </div>
      <div class="flex items-start gap-3 ">
        <MapPin class="h-5 w-5 text-primary mt-0.5" />
        <div>
          <h3 class="font-medium">Location</h3>
          <p class="text-muted-foreground">
            10 minutes from Angkor Wat temple complex, Siem Reap, Cambodia
          </p>
        </div>
      </div>

      <div class="flex items-start gap-3">
        <Shield class="h-5 w-5 text-primary mt-0.5" />
        <div>
          <h3 class="font-medium">Safety Measures</h3>
          <p class="text-muted-foreground">
            Enhanced cleaning protocols, 24/7 security, in-room safety deposit
            box
          </p>
        </div>
      </div>
    </div>
    <!-- Room Details -->
    <div>
      <h2 class="text-2xl font-bold mb-4">Room Details</h2>
      <div class="relative h-48 mb-4 rounded-lg overflow-hidden">
        <img
          src="https://images.unsplash.com/photo-1483058712412-4245e9b90334?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
          alt="Deluxe Double Room"
          class="h-full w-full object-cover"
        />
      </div>
      <div class="flex items-start gap-3 mt-2">
        <Bed class="h-5 w-5 text-primary mt-0.5" />
        <div>
          <h3 class="font-medium">Deluxe Double Room</h3>
          <p class="text-muted-foreground">
            King-sized bed, 30m² room with balcony and garden view
          </p>
        </div>
      </div>
      <div class="mt-4">
        <ul class="ml-8 list-disc space-y-1">
          <li class="text-muted-foreground">
            Private bathroom with rainfall shower
          </li>
          <li class="text-muted-foreground">
            Complimentary toiletries and hairdryer
          </li>
          <li class="text-muted-foreground">
            40" Smart TV with international channels
          </li>
          <li class="text-muted-foreground">
            Tea and coffee making facilities
          </li>
          <li class="text-muted-foreground">
            Mini-refrigerator and room service available
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div
    class="grid responsive-grid mt-8 mb-4 mx-4 md:mx-16 items-start grid-cols-1 md:grid-cols-3 gap-8"
  >
    <div class="min-w-[300px] w-full">
      <BookingSummary 
        :hotelName="bookingDetails.hotelName" 
        :location="bookingDetails.location"
        :near="bookingDetails.near"
        :checkin="bookingDetails.checkin"
        :checkout="bookingDetails.checkout"
        :roomType="bookingDetails.roomType"
        :guest="bookingDetails.guest"
        :nights="bookingDetails.nights"
        :roomRate="bookingDetails.roomRate"
        :taxes="bookingDetails.taxes"
        :cancellation="bookingDetails.cancellation"
      />
    </div>
    <div class="md:col-span-2">
      <FormInfo ref="formInfoRef" />
      <div class="mr-16 ml-16 mt-8">
        <button 
          type="button" 
          @click="submitForm"  
          class="w-full btn bg-blue-950 p-2 text-white "
        >Complete Booking</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import FormInfo from "@/components/CheckoutComponents/FormInfo.vue";
import BookingSummary from "@/components/CheckoutComponents/BookingSummary.vue";
import { MapPin, Shield, Bed } from "lucide-vue-next";
import { useRoomStore } from "@/stores/store";
import dayjs from "dayjs";

const route = useRoute();
const router = useRouter();
const roomStore = useRoomStore();
const formInfoRef = ref(null);
const isLoading = ref(false);
const errorMessage = ref('');

// Get params from route query
const roomId = route.params.id;
const roomIds = route.query.roomIds?.split(",").map(id => Number(id)) || [];
const hotelId = Number(route.query.hotelId);
const checkin = route.query.checkin;
const checkout = route.query.checkout;
const quantity = Number(route.query.quantity) || 1;
const roomName = route.query.roomName;
const roomPrice = Number(route.query.roomPrice);
const capacity = Number(route.query.capacity) || 2;

console.log("Checkout page received:", {
  roomId,
  roomIds,
  hotelId,
  checkin,
  checkout,
  quantity,
  roomName,
  roomPrice,
  capacity
});
const getFormData = () => {
  return {
    firstName: firstName.value,
    lastName: lastName.value,
    email: email.value,
    phoneNumber: phoneNumber.value,
    specialRequests: specialRequests.value,
    selectedPayment: selectedPayment.value,
    cardNumber: cardNumber.value,
    expiryDate: expiryDate.value,
    cvv: cvv.value,
    agreeToTerms: agreeToTerms.value,
    guestCount: guestCount.value || 1,
  };
};

defineExpose({
  getFormData,
});
// Calculate nights
const nights = checkin && checkout ? 
  dayjs(checkout).diff(dayjs(checkin), 'day') : 1;

// Calculate prices
const roomRate = roomPrice * quantity * nights;
const taxes = Math.round(roomRate * 0.1); // 10% tax
const totalPrice = roomRate + taxes;

// Setup booking details
const bookingDetails = ref({
  hotelName: "Loading...",
  location: "Loading...",
  near: "Loading...",
  checkin: checkin ? dayjs(checkin).toDate() : null,
  checkout: checkout ? dayjs(checkout).toDate() : null,
  roomType: roomName || "Standard Room",
  guest: capacity * quantity,
  nights: nights,
  roomRate: roomRate,
  taxes: taxes,
  total: totalPrice,
  cancellation: dayjs().add(7, 'day').toDate(),
});

// Load hotel details
onMounted(async () => {
  try {
    if (hotelId) {
      const hotel = await roomStore.fetchHotelById(hotelId);
      if (hotel) {
        bookingDetails.value = {
          ...bookingDetails.value,
          hotelName: hotel.property_name,
          location: hotel.location?.city + ", " + hotel.location?.country,
          near: hotel.location?.landmarks || "City center",
        };
      }
    }
  } catch (error) {
    console.error("Error loading hotel details:", error);
  }
});

// Create the booking when form is submitted
const createBooking = async (formData) => {
  try {
    isLoading.value = true;
    errorMessage.value = '';
    
    const bookingData = {
      user_id: 1, // Should be the logged-in user's ID
      room_ids: roomIds,
      hotel_id: hotelId,
      check_in_date: checkin,
      check_out_date: checkout,
      number_of_guests: Number(formData.guestCount || capacity),
      total_price: totalPrice,
      special_request: formData.specialRequests || '',
      payment_method: formData.selectedPayment,
    };

    console.log("Sending booking data:", bookingData);
    
    const response = await axios.post('http://localhost:8100/api/bookings', bookingData);
    
    if (response.data && response.data.booking_id) {
      alert("Booking successful! Your booking ID is: " + response.data.booking_id);
      router.push('/current-past-booked');
    } else {
      throw new Error('Invalid booking response');
    }
  } catch (error) {
    console.error('Booking creation error:', error);
    errorMessage.value = 'Failed to create booking: ' + error.message;
    alert('Failed to create booking. Please try again.');
  } finally {
    isLoading.value = false;
  }
};

// Handle form submission
const submitForm = () => {
  if (formInfoRef.value) {
    const formData = formInfoRef.value.getFormData();
    
    if (!formData.agreeToTerms) {
      alert('Please agree to the terms and conditions to continue.');
      return;
    }
    
    createBooking(formData);
  } else {
    alert('Form information is not available. Please try again.');
  }
};
</script>
<style scoped></style>