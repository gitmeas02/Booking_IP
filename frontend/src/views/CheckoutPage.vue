<template>
  <!-- Hotel Information -->
  <div
    class="grid responsive-grid mt-8 mb-4 mx-4 md:mx-16 items-start grid-cols-1 md:grid-cols-2 gap-8 border-gray-200 border-1 rounded-[12px] p-8"
  >
    <!-- Hotel Details -->
    <div>
      <h2 class="text-2xl font-bold mb-4">Hotel Details</h2>
      <div class="relative h-48 mb-4 rounded-lg overflow-hidden">
        <img
          v-if="
            bookingDetails.hotelPhotos && bookingDetails.hotelPhotos.length > 0
          "
          :src="
            'http://localhost:9000/ownerimages/' +
            bookingDetails.hotelPhotos[0].url
          "
          alt="Khun Hoth Hotel"
          class="h-full w-full object-cover"
        />
      </div>
      <div class="flex items-start gap-3">
        <MapPin class="h-5 w-5 text-primary mt-0.5" />
        <div>
          <h3 class="font-medium">Location</h3>
          <p class="text-muted-foreground">
            {{ bookingDetails.location }}
          </p>
        </div>
      </div>

      <div class="flex items-start gap-3">
        <Shield class="h-5 w-5 text-primary mt-0.5" />
        <div>
          <h3 class="font-medium">Safety Measures</h3>
          <p class="text-muted-foreground">
            {{ bookingDetails.roomDescription }}
          </p>
        </div>
      </div>
    </div>
    <!-- Room Details -->
    <div>
      <h2 class="text-2xl font-bold mb-4">Room Details</h2>
      <div class="relative h-48 mb-4 rounded-lg overflow-hidden">
        <img
          v-if="
            bookingDetails.roomImages && bookingDetails.roomImages.length > 0
          "
          :src="
            'http://localhost:9000/' +
            bookingDetails.roomImages[0].image_url
          "
          alt="Deluxe Double Room"
          class="h-full w-full object-cover"
        />
      </div>
      <div class="flex items-start gap-3 mt-2">
        <Bed class="h-5 w-5 text-primary mt-0.5" />
        <div>
          <h3 class="font-medium">{{ bookingDetails.roomType }} Room</h3>
          <p class="text-muted-foreground">{{ bookingDetails.guest }} Guests</p>
        </div>
      </div>
      <div class="mt-4">
        <ul class="ml-8 list-disc space-y-1">
          <li
            class="text-muted-foreground"
            v-for="(amenity, index) in bookingDetails.amenities"
            :key="index"
          >
            {{ amenity }}
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
        :roomPrice="roomPrice"
        :nights="bookingDetails.nights"
        :roomRate="bookingDetails.roomRate"
        :taxes="bookingDetails.taxes"
        :quantity="quantity"
        :cancellation="bookingDetails.cancellation"
      />
    </div>
    <div class="md:col-span-2">
      <FormInfo ref="formInfoRef" />
      <div class="mr-16 ml-16 mt-8">
        <button
          type="button"
          @click="submitForm"
          class="w-full btn bg-blue-950 p-2 text-white"
        >
          Complete Booking
        </button>
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
const errorMessage = ref("");
const hotel = ref({});
// Get params from route query
const roomId = route.params.id;
const roomIds = route.query.roomIds?.split(",").map((id) => Number(id)) || [];
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
  capacity,
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
const nights =
  checkin && checkout ? dayjs(checkout).diff(dayjs(checkin), "day") : 1;
console.log("Calculated nights:", nights);
console.log("Room IDs:", roomIds);
console.log("Hotel ID:", hotelId);
console.log("Check-in date:", checkin);
console.log("Check-out date:", checkout);
console.log("Quantity:", quantity);
console.log("Room Name:", roomName);
console.log("Room Price:", roomPrice);
console.log("Capacity:", capacity);
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
  roomType: roomName || " ",
  guest: capacity * quantity,
  nights: nights,
  roomRate: roomRate,
  taxes: taxes,
  total: totalPrice,
  cancellation: dayjs().add(7, "day").toDate(),
});

// Load hotel details
onMounted(async () => {
  try {
    if (hotelId) {
      const hotelData = await roomStore.fetchHotelById(hotelId);
      console.log("Fetched hotel details:", hotelData);
      if (hotelData) {
        // Fix: Use room_types instead of rooms
        const selectedRoom = hotelData.room_types.find(
          (r) => r.id === Number([roomId])
        );
        console.log("Selected room:", selectedRoom);
        const roomImages =
  selectedRoom && selectedRoom.images
    ? selectedRoom.images.map((image) => ({
        image_url: image.image_url,
      }))
    : [];
        console.log("Room images:", roomImages);
        const roomDescription = selectedRoom
          ? selectedRoom.description
          : "No description available";
        const amenities =
          selectedRoom && selectedRoom.amenities
            ? selectedRoom.amenities.map((amenity) => amenity.amenity_name)
            : [];
        console.log("Amenities:", amenities);
        const hotelPhotos = hotelData.photos || [];
        console.log("Hotel image URL:", hotelPhotos);
        // const roomImages= selectedRoom && selectedRoom.photos ?
        //   selectedRoom.photos.map(photo => photo.url) : [];
        bookingDetails.value = {
          ...bookingDetails.value,
          hotelName: hotelData.property_name,
          location: `${hotelData.location?.city}, ${hotelData.location?.country}`,
          roomDescription,
          amenities: amenities,
          hotelPhotos: hotelPhotos,
          roomImages: roomImages,
          near: hotelData.location?.street || "City center",
        };
      }
    }
    console.log("Room Description:", bookingDetails.value.roomDescription);
  } catch (error) {
    console.error("Error loading hotel details:", error);
  }
});

// Create the booking when form is submitted
const createBooking = async (formData) => {
  try {
    isLoading.value = true;
    errorMessage.value = "";

    const bookingData = {
      user_id: 1, // Should be the logged-in user's ID
      room_ids: roomIds,
      hotel_id: hotelId,
      check_in_date: checkin,
      check_out_date: checkout,
      number_of_guests: Number(formData.guestCount || capacity),
      total_price: totalPrice,
      special_request: formData.specialRequests || "",
      payment_method: formData.selectedPayment,
    };

    console.log("Sending booking data:", bookingData);

    const response = await axios.post(
      "http://localhost:8100/api/bookings",
      bookingData
    );

    if (response.data && response.data.booking_id) {
      alert(
        "Booking successful! Your booking ID is: " + response.data.booking_id
      );
      router.push("/current-past-booked");
    } else {
      throw new Error("Invalid booking response");
    }
  } catch (error) {
    console.error("Booking creation error:", error);
    errorMessage.value = "Failed to create booking: " + error.message;
    alert("Failed to create booking. Please try again.");
  } finally {
    isLoading.value = false;
  }
};

// Handle form submission
const submitForm = () => {
  if (formInfoRef.value) {
    const formData = formInfoRef.value.getFormData();

    if (!formData.agreeToTerms) {
      alert("Please agree to the terms and conditions to continue.");
      return;
    }

    createBooking(formData);
  } else {
    alert("Form information is not available. Please try again.");
  }
};
</script>
<style scoped></style>
