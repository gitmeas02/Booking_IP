<template>
  <div class="max-w-screen-xl mx-auto px-4 py-10 font-sans">
    <!-- Heading -->
    <div class="text-center mb-10">
      <h2 class="text-3xl font-extrabold text-neutral-800">Services at your property</h2>
    </div>

    <!-- Form Section -->
    <div class="flex flex-col md:flex-row gap-10">
      <!-- Left Column -->
      <div class="flex flex-col gap-6 flex-1">
        <!-- Breakfast Block -->
        <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm space-y-4">
          <div>
            <h4 class="text-xl font-bold text-gray-800">Breakfast</h4>
            <hr class="my-2" />
          </div>
          <div>
            <p class="text-lg font-semibold text-gray-700">Do you serve guests breakfast?</p>
          </div>
          <div class="space-y-2">
            <label class="flex items-center gap-2 text-gray-800">
              <input type="radio" name="breakfast" :value="true" v-model="breakfast" class="accent-blue-600" />
              Yes
            </label>
            <label class="flex items-center gap-2 text-gray-800">
              <input type="radio" name="breakfast" :value="false" v-model="breakfast" class="accent-blue-600" />
              No
            </label>
          </div>
        </div>

        <!-- Parking Block -->
        <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm space-y-4">
          <div>
            <h4 class="text-xl font-bold text-gray-800">Parking</h4>
            <hr class="my-2" />
          </div>
          <div>
            <p class="text-lg font-semibold text-gray-700">Is parking available to guests?</p>
          </div>
          <div class="space-y-2">
            <label class="flex items-center gap-2 text-gray-800">
              <input type="radio" name="parking" :value="true" v-model="parking" class="accent-blue-600" />
              Yes
            </label>
            <label class="flex items-center gap-2 text-gray-800">
              <input type="radio" name="parking" :value="false" v-model="parking" class="accent-blue-600" />
              No
            </label>
          </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between pt-4">
          <button
            class="px-6 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400"
            @click="handleBack">
            Back
          </button>
          <button
            class="px-6 py-2 text-white bg-black rounded-md shadow-md hover:bg-gray-300 hover:text-black focus:outline-none focus:ring-2 focus:ring-amber-400"
            @click="handleContinue"
            :disabled="breakfast === null || parking === null">
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
import { defineComponent, ref, watch } from "vue";
import { useValidationStore } from "@/stores/validationStore";
import { useRouter } from "vue-router";

export default defineComponent({
  setup() {
    const store = useValidationStore();
    const router = useRouter();

    const breakfast = ref(store.property.breakfast ?? null);
    const parking = ref(store.property.parking ?? null);

    watch(breakfast, (val) => {
      store.property.breakfast = val;
    });

    watch(parking, (val) => {
      store.property.parking = val;
    });

const handleContinue = () => {
  console.log(
    "Current property data:",
    JSON.stringify(store.property, null, 2)
  );

  // Check if both breakfast and parking are present and valid booleans
  if (typeof store.property.breakfast === "boolean" && typeof store.property.parking === "boolean") {
    console.log("✅ Both breakfast and parking values are set correctly.");
  } else {
    console.warn("⚠️ Either breakfast or parking is not set properly.");
  }

  router.push({ name: "OwnerPropertyPage6" });
};


    const handleBack = () => {
      router.push({ name: "OwnerPropertyPage4" });
    };

    return { breakfast, parking, handleContinue, handleBack };
  },
});
</script>

<style>
/* Optional: Custom "true blue" for radio buttons if blue-600 isn't desired */
input[type="radio"].accent-blue-600:checked {
  accent-color: #0000FF; /* True blue hex code */
}
</style>