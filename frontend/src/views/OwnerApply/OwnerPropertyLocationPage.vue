<template>
  <div class="max-w-xl mx-auto py-10 px-4">
    <h2 class="text-2xl font-bold text-neutral-800 mb-6">Where is your property located?</h2>

    <div class="space-y-4">
      <div v-if="store.errorMessage" class="text-red-500 text-sm">
        {{ store.errorMessage }}
      </div>

      <div>
        <label class="block text-sm font-medium">Search</label>
        <input
          v-model="search"
          placeholder="e.g. The Sunrise Villa"
          class="w-full px-3 py-2 border rounded"
        />
      </div>
      <div>
        <label class="block text-sm font-medium">Street</label>
        <input
          v-model="store.property.location.street"
          class="w-full px-3 py-2 border rounded"
        />
      </div>
      <div>
        <label class="block text-sm font-medium">Apartment/Floor</label>
        <input
          v-model="store.property.location.floor"
          class="w-full px-3 py-2 border rounded"
        />
      </div>
      <div>
        <label class="block text-sm font-medium">City</label>
        <input
          v-model="store.property.location.city"
          class="w-full px-3 py-2 border rounded"
        />
      </div>
      <div>
        <label class="block text-sm font-medium">Postcode</label>
        <input
          v-model="store.property.location.zip_code"
          class="w-full px-3 py-2 border rounded"
        />
      </div>
      <div>
        <label class="block text-sm font-medium">Country</label>
        <input
          v-model="store.property.location.country"
          class="w-full px-3 py-2 border rounded"
        />
      </div>

      <!-- Example to show saved Latitude and Longitude -->
      <div>
        <label class="block text-sm font-medium">Latitude</label>
        <p>{{ store.property.location.lat ?? "Not set" }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium">Longitude</label>
        <p>{{ store.property.location.lng ?? "Not set" }}</p>
      </div>

      <button
        class="w-full bg-black text-white py-2 rounded hover:bg-gray-800"
        @click="goToNextPage"
      >
        Continue
      </button>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import { useValidationStore } from "@/stores/validationStore";
import { useRouter } from "vue-router";

export default defineComponent({
  setup() {
    const store = useValidationStore();
    const router = useRouter();
    const search = ref("");

    const goToNextPage = () => {
      const { street, city } = store.property.location;
      if (!street || !city) {
        store.errorMessage = "Please fill in both the street and city fields.";
        return;
      }

      store.errorMessage = "";
      console.log("Current property data:", JSON.stringify(store.property, null, 2));

      router.push({ name: "OwnerPropertyPage3" });
    };

    return {
      store,
      search,
      goToNextPage,
    };
  },
});
</script>
