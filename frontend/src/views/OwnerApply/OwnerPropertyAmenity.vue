<template>
  <div class="max-w-screen-xl mx-auto px-4 py-10 font-sans">
    <!-- Heading -->
    <div class="text-center mb-10">
      <h2 class="text-3xl font-extrabold text-neutral-800">
        What can guests use at your hotel?
      </h2>
    </div>

    <!-- Form Section -->
    <div class="flex flex-col md:flex-row gap-10">
      <!-- Left Column -->
      <div class="flex-1 space-y-6">
        <!-- Form Box -->
        <div class="bg-white p-6 rounded-lg shadow-md space-y-8">
          <!-- Checkboxes -->
          <div class="flex flex-col gap-1">
            <div
              v-for="amenity in amenityOptions"
              :key="amenity.id"
              class="flex gap-4"
            >
              <input
                type="checkbox"
                :id="'amenity-' + amenity.id"
                :checked="store.property.amenities.includes(amenity.id)"
                @change="toggleAmenity(amenity.id)"
              />
              <label :for="'amenity-' + amenity.id">{{ amenity.label }}</label>
            </div>
          </div>
        </div>

        <!-- Navigation Buttons (still in left column but below the white box) -->
        <div class="flex justify-between">
          <button
            class="px-6 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400"
            @click="goBack"
          >
            Back
          </button>
          <button
            class="px-6 py-2 text-white bg-black rounded-md shadow-md hover:bg-gray-300 hover:text-black focus:outline-none focus:ring-2 focus:ring-amber-400"
            @click="goToNextPage"
          >
            Continue
          </button>
        </div>
      </div>

      <!-- Right Column Placeholder -->
      <div class="flex-1">
        <!-- Optional preview/image content -->
      </div>
    </div>
  </div>
</template>

<script>
import { computed, defineComponent } from "vue";
import { useValidationStore } from "@/stores/validationStore";
import { useRouter } from "vue-router";

export default defineComponent({
  setup() {
    const store = useValidationStore();
    const router = useRouter();

    const amenityOptions = [
      { id: 1, label: "Restaurant" },
      { id: 2, label: "Room Service" },
      { id: 3, label: "Bar" },
      { id: 4, label: "Fitness Center" },
      { id: 5, label: "24-Hour Front Desk" },
      { id: 6, label: "Sauna" },
      { id: 7, label: "Terrace" },
      { id: 8, label: "Non-smoking Rooms" },
      { id: 9, label: "Family Room" },
      { id: 10, label: "Spa" },
      { id: 11, label: "Free Wi-Fi" },
      { id: 12, label: "Swimming Pool" },
      { id: 13, label: "Air Conditioning" },
      { id: 14, label: "Water Park" },
      { id: 15, label: "Beach" },
    ];

    const toggleAmenity = (key) => {
      const index = store.property.amenities.indexOf(key);
      if (index === -1) {
        store.property.amenities.push(key);
      } else {
        store.property.amenities.splice(index, 1);
      }
    };

    const goToNextPage = () => {
      router.push({ name: "OwnerPropertyPage5" });
      console.log(
        "Current property data:",
        JSON.stringify(store.property, null, 2)
      );
    };

    const goBack = () => {
      router.push({ name: "OwnerPropertyPage3" });
    };
    const hotelName = computed(() => store.property.name);
    const starRating = computed(() => store.property.starRating);
    const isManaged = computed(() => store.property.isManaged ?? null);
    return {
      store,
      amenityOptions,
      toggleAmenity,
      goToNextPage,
      goBack,
      hotelName,
      starRating,
      isManaged,
    };
  },
});
</script>
<style scoped></style>
