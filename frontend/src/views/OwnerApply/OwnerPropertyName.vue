<template>
  <div class="max-w-screen-xl mx-auto px-4 py-10 font-sans">
    <!-- Heading -->
    <div class="text-center mb-10">
      <h2 class="text-3xl font-extrabold text-neutral-800">Tell us about your hotel</h2>
    </div>

    <!-- Form Section -->
    <div class="flex flex-col md:flex-row gap-10">
      <!-- Left Column -->
      <div class="flex-1 space-y-6">
        <!-- Form Card -->
        <div class="bg-white p-6 rounded-lg shadow-md space-y-8">
          <!-- Property Name -->
          <div>
            <h4 class="text-xl font-semibold mb-2">What's the name of your hotel?</h4>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="hotel-name">Property Name</label>
            <input
              id="hotel-name"
              type="text"
              placeholder="Name"
              class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
              v-model="store.property.property_name"
            />
            <p class="text-sm text-gray-600 mt-1">
              Guests will see this name when searching for a place to stay.
            </p>
          </div>

          <hr />

          <!-- Star Rating -->
          <div>
            <h4 class="text-xl font-semibold mb-2">What is the star rating of your hotel?</h4>
            <div class="space-y-3">
              <label class="flex items-center gap-3">
                <input type="radio" name="star_rating" :value="0" v-model="store.property.stars" />
                N/A
              </label>

              <label v-for="n in 5" :key="n" class="block">
                <div class="flex items-center gap-3">
                  <input type="radio" name="star_rating" :value="n" v-model="store.property.stars" />
                  <span class="flex items-center gap-2">
                    <span>{{ n }} Star<span v-if="n > 1">s</span></span>
                    <span class="flex gap-0.5">
                      <Icon v-for="i in n" :key="i" icon="mdi:star" class="text-yellow-500 text-lg" />
                    </span>
                  </span>
                </div>
              </label>
            </div>
          </div>

          <hr />
        </div>

        <!-- Buttons OUTSIDE white box -->
        <div class="flex justify-between">
          <button
            class="px-6 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400"
            @click="handleBack"
          >
            Back
          </button>
          <button
            class="px-6 py-2 text-white bg-black rounded-md shadow-md hover:bg-gray-300 hover:text-black focus:outline-none focus:ring-2 focus:ring-amber-400"
            @click="goToNextPage"
            :disabled="!store.property.property_name || store.property.stars === null"
          >
            Continue
          </button>
        </div>
      </div>

      <!-- Right Column (optional) -->
      <div class="flex-1">
        <!-- You can add optional content here -->
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";
import { useValidationStore } from "@/stores/validationStore";
import { useRouter } from "vue-router";
import { Icon } from "@iconify/vue";

export default defineComponent({
  components: { Icon },
  setup() {
    const store = useValidationStore();
    const router = useRouter();

const goToNextPage = () => {
  if (!store.property.property_name || store.property.stars === null) {
    store.errorMessage = "Please provide property name and star rating.";
    return;
  }
  store.errorMessage = "";
  router.push({ name: "OwnerPropertyPage4" });
  console.log("Current property data:", JSON.stringify(store.property, null, 2));
};


    const handleBack = () => {
      router.push({ name: "OwnerPropertyPage2" });
    };

    return { store, goToNextPage, handleBack };
  },
});
</script>

<style scoped>
/* Optional custom styles */
</style>
