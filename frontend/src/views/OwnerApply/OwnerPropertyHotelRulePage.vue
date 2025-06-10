<template>
  <div class="max-w-screen-xl mx-auto px-4 py-10 font-sans">
    <!-- Heading -->
    <div class="text-center mb-10">
      <h2 class="text-3xl font-extrabold text-neutral-800">House Rules</h2>
    </div>

    <!-- Form Section -->
    <div class="flex flex-col md:flex-row gap-10">
      <!-- Left Column -->
      <div class="flex-1 space-y-6">
        <!-- Form Card -->
        <div class="bg-white p-6 rounded-lg shadow-md space-y-8">
          <!-- Check-in/out Time -->
          <div>
            <h4 class="text-xl font-semibold mb-4">What are your check-in and check-out times?</h4>

            <!-- Check-In -->
            <div class="mb-6">
              <h5 class="text-lg font-medium mb-2">Check-In</h5>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm text-gray-600 mb-1">From</label>
                  <input
                    type="time"
                    v-model="checkInFrom"
                    class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                  />
                </div>
                <div>
                  <label class="block text-sm text-gray-600 mb-1">Until</label>
                  <input
                    type="time"
                    v-model="checkInUntil"
                    class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                  />
                </div>
              </div>
            </div>

            <!-- Check-Out -->
            <div>
              <h5 class="text-lg font-medium mb-2">Check-Out</h5>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm text-gray-600 mb-1">From</label>
                  <input
                    type="time"
                    v-model="checkOutFrom"
                    class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                  />
                </div>
                <div>
                  <label class="block text-sm text-gray-600 mb-1">Until</label>
                  <input
                    type="time"
                    v-model="checkOutUntil"
                    class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                  />
                </div>
              </div>
            </div>
          </div>

          <hr />

          <!-- Children & Pet Policy -->
          <div class="space-y-6">
            <!-- Children -->
            <div>
              <h4 class="text-xl font-semibold mb-2">Do you allow children?</h4>
              <div class="space-y-2">
                <label class="flex items-center gap-2">
                  <input type="radio" name="children_allowed" :value="true" v-model="allowChildren" />
                  Yes
                </label>
                <label class="flex items-center gap-2">
                  <input type="radio" name="children_allowed" :value="false" v-model="allowChildren" />
                  No
                </label>
              </div>
            </div>

            <!-- Pets -->
            <div>
              <h4 class="text-xl font-semibold mb-2">Do you allow pets?</h4>
              <div class="space-y-2">
                <label class="flex items-center gap-2">
                  <input type="radio" name="pets_allowed" :value="true" v-model="petsAllowed" />
                  Yes
                </label>
                <label class="flex items-center gap-2">
                  <input type="radio" name="pets_allowed" :value="false" v-model="petsAllowed" />
                  No
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between">
          <button
            class="px-6 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400"
            @click="handleBack"
          >
            Back
          </button>
          <button
            class="px-6 py-2 text-white bg-black rounded-md shadow-md hover:bg-gray-300 hover:text-black focus:outline-none focus:ring-2 focus:ring-amber-400"
            @click="handleContinue"
            :disabled="!checkInFrom || !checkInUntil || !checkOutFrom || !checkOutUntil || typeof allowChildren !== 'boolean' || typeof petsAllowed !== 'boolean'">
            Continue
          </button>
        </div>
      </div>

      <!-- Right Column -->
      <div class="flex-1">
        <!-- Optional preview or summary could go here -->
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, computed } from "vue";
import { useValidationStore } from "@/stores/validationStore";
import { useRouter } from "vue-router";

export default defineComponent({
  setup() {
    const store = useValidationStore();
    const router = useRouter();

    // Computed bindings to the store
    const checkInFrom = computed({
      get: () => store.property.houseRules.checkin_from,
      set: (val) => (store.property.houseRules.checkin_from = val),
    });

    const checkInUntil = computed({
      get: () => store.property.houseRules.checkin_to,
      set: (val) => (store.property.houseRules.checkin_to = val),
    });

    const checkOutFrom = computed({
      get: () => store.property.houseRules.checkout_from,
      set: (val) => (store.property.houseRules.checkout_from = val),
    });

    const checkOutUntil = computed({
      get: () => store.property.houseRules.checkout_to,
      set: (val) => (store.property.houseRules.checkout_to = val),
    });

    const allowChildren = computed({
      get: () => store.property.houseRules.childrenAllowed,
      set: (val) => (store.property.houseRules.childrenAllowed = val),
    });

    const petsAllowed = computed({
      get: () => store.property.houseRules.allow_pet,
      set: (val) => (store.property.houseRules.allow_pet = val),
    });

    const handleContinue = () => {
      if (!checkInFrom.value || !checkInUntil.value || !checkOutFrom.value || !checkOutUntil.value) {
        store.errorMessage = "Please provide check-in and check-out times.";
        return;
      }
      if (typeof allowChildren.value !== "boolean" || typeof petsAllowed.value !== "boolean") {
        store.errorMessage = "Please answer the questions about children and pets.";
        return;
      }

      router.push({ name: "OwnerPropertyPage7" });
      console.log("Current Data", JSON.stringify(store.property, null, 2));
    };

    const handleBack = () => {
      router.push({ name: "OwnerPropertyPage5" });
    };

    return {
      checkInFrom,
      checkInUntil,
      checkOutFrom,
      checkOutUntil,
      allowChildren,
      petsAllowed,
      handleContinue,
      handleBack,
    };
  },
});
</script>
