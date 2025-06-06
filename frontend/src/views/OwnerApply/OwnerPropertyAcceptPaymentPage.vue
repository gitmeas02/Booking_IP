<template>
  <div class="max-w-screen-xl mx-auto px-4 py-10 font-sans">
    <!-- Heading -->
    <div class="text-center mb-10">
      <h2 class="text-3xl font-extrabold text-neutral-800">Payment</h2>
    </div>

    <!-- Form Section -->
    <div class="flex flex-col md:flex-row gap-10">
      <!-- Left Column -->
      <div class="flex flex-col gap-6 flex-1">
        <!-- Payment Options Block -->
        <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm space-y-4">
          <div>
            <h4 class="text-xl font-bold text-gray-800">How can your guests pay for their stay?</h4>
          </div>

          <div class="space-y-3">
            <div class="flex items-start gap-2">
              <input
                type="checkbox"
                id="online"
                :checked="true"
                disabled
                class="mt-1 accent-blue-600"
              />
              <label for="online" class="text-gray-800">
                Online, when they make a reservation. PteasKhmer will facilitate your guest's payment.
              </label>
            </div>

            <div class="flex items-start gap-2">
              <input
                type="checkbox"
                id="creditCard"
                v-model="creditCardOption"
                class="mt-1 accent-blue-600"
              />
              <label for="creditCard" class="text-gray-800">
                By credit card at my property.
              </label>
            </div>
          </div>

          <div v-if="errorMessage" class="text-red-500 text-sm text-center">
            {{ errorMessage }}
          </div>
        </div>

        <!-- Payment Info Block -->
        <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-sm space-y-4">
          <div>
            <h4 class="text-xl font-bold text-gray-800">How payments by PteasKhmer work</h4>
          </div>
          <div>
            <p class="text-sm text-gray-700">1. Your guest pays through PteasKhmer.com using their preferred method.</p>
            <p class="text-sm text-gray-700">2. We securely process the payment for you.</p>
            <p class="text-sm text-gray-700">3. You’ll receive a bank transfer by the 15th of each month.</p>
          </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between pt-4">
          <button
            class="px-6 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400"
            @click="handleBack"
          >
            Back
          </button>
          <button
            class="px-6 py-2 text-white bg-black rounded-md shadow-md hover:bg-gray-300 hover:text-black focus:outline-none focus:ring-2 focus:ring-amber-400"
            @click="handleContinue"
          >
            Continue
          </button>
        </div>
      </div>

      <!-- Right Column Placeholder -->
      <div class="flex-1">
        <!-- Optional preview or image here -->
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

    // Always true by default and fixed
    const onlineOption = true;

    // Editable credit card option
    const creditCardOption = ref(
      store.property.paymentOptions?.[0]?.creditCard_at_Property || false
    );

    const errorMessage = ref("");

    // Sync with store on change
    watch(creditCardOption, () => {
      store.setPropertyValue("paymentOptions", [
        {
          online: true,
          creditCard_at_Property: creditCardOption.value,
        },
      ]);
      errorMessage.value = "";
    }, { immediate: true });

    const handleContinue = () => {
      // Validation optional here since online = true always
      store.setPropertyValue("paymentOptions", [
        {
          online: true,
          creditCard_at_Property: creditCardOption.value,
        },
      ]);
      router.push({ name: "OwnerPropertyPage10" });
      console.log(store.property);
      console.log("Json", JSON.stringify(store.property, null, 2))
    };

    const handleBack = () => {
      router.push({ name: "OwnerPropertyPage8" });
    };

    return {
      creditCardOption,
      errorMessage,
      handleContinue,
      handleBack,
    };
  },
});
</script>

<style scoped>
/* Optional styling */
</style>
