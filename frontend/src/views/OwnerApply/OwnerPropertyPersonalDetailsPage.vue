<template>
  <div class="max-w-4xl mx-auto px-6 py-12 font-sans bg-gray-50 rounded-lg shadow-md">
    <!-- Heading -->
    <div class="text-center mb-12">
      <h2 class="text-4xl font-extrabold text-gray-900">Partner Verification</h2>
      <p class="mt-2 text-gray-600 max-w-xl mx-auto">
        To comply with various legal and regulatory requirements, we need to collect and verify some info about you and your property.
      </p>
    </div>

    <!-- Form -->
    <form @submit.prevent="goToNextPage" novalidate>
      <div class="bg-white p-8 rounded-lg shadow-md space-y-8">

        <!-- Ownership Info -->
        <div>
          <h3 class="text-xl font-semibold text-gray-800 mb-3">
            Is the accommodation owned by an individual or business entity?
          </h3>
          <p class="text-gray-700">
            Provide complete and accurate personal information of the accommodation owner.
          </p>
        </div>

        <!-- Full Name -->
        <div>
          <label for="fullName" class="block text-sm font-medium text-gray-700 mb-2">
            Full Name <span class="text-red-500">*</span>
          </label>
          <input
            id="fullName"
            v-model="store.property.owner.fullName"
            type="text"
            placeholder="Your full name"
            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
            autocomplete="name"
            required
          />
          <p v-if="!isFullNameValid" class="mt-1 text-sm text-red-600">
            Full name must exactly match your logged-in user name.
          </p>
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            Email <span class="text-red-500">*</span>
          </label>
          <input
            required
            id="email"
            v-model="store.property.owner.email"
            type="email"
            placeholder="Your email"
            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
            autocomplete="email"
          />
          <p v-if="!isEmailValid" class="mt-1 text-sm text-red-600">
            Email must exactly match your login email.
          </p>
        </div>

        <!-- Phone Number -->
        <div>
          <label for="phoneNumber" class="block text-sm font-medium text-gray-700 mb-2">
            Phone Number
          </label>
          <input
            required
            id="phoneNumber"
            v-model="store.property.owner.phoneNumber"
            type="tel"
            placeholder="+1234567890"
            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
            autocomplete="tel"
          />
        </div>

        <!-- Country/Region -->
        <div>
          <label for="countryRegion" class="block text-sm font-medium text-gray-700 mb-2">
            Country/Region
          </label>
          <input
            required
            id="countryRegion"
            v-model="store.property.owner.countryRegion"
            type="text"
            placeholder="Country or Region"
            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
          />
        </div>

        <!-- Address Lines -->
        <div>
          <label for="addressLine1" class="block text-sm font-medium text-gray-700 mb-2">
            Address Line 1
          </label>
          <input
            required
            id="addressLine1"
            v-model="store.property.owner.addressLine1"
            type="text"
            placeholder="Street address"
            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
          />
        </div>
        <div>
          <label for="addressLine2" class="block text-sm font-medium text-gray-700 mb-2">
            Address Line 2
          </label>
          <input
            id="addressLine2"
            v-model="store.property.owner.addressLine2"
            type="text"
            placeholder="Apartment, suite, unit, etc. (optional)"
            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
          />
        </div>

        <!-- Government ID Name -->
        <div>
          <p class="text-gray-700 mb-3">
            Enter your name exactly as it appears on your government-issued ID.
          </p>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input
              required
              v-model="store.property.owner.idFirstName"
              type="text"
              placeholder="ID First Name"
              class="p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
              autocomplete="off"
            />
            <input
              required
              v-model="store.property.owner.idMiddleName"
              type="text"
              placeholder="ID Middle Name"
              class="p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
              autocomplete="off"
            />
            <input
              required
              v-model="store.property.owner.idLastName"
              type="text"
              placeholder="ID Last Name"
              class="p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
              autocomplete="off"
            />
          </div>
        </div>

        <!-- Error message -->
        <p v-if="store.errorMessage" class="text-red-600 text-center my-4 font-semibold">
          {{ store.errorMessage }}
        </p>

        <!-- Buttons -->
        <div class="flex justify-between mt-6">
          <button
            type="button"
            @click="goBack"
            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-md shadow hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400"
          >
            Back
          </button>
          <button
            :disabled="!canContinue"
            type="submit"
            :class="canContinue
              ? 'bg-amber-500 text-white hover:bg-amber-600 cursor-pointer'
              : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
            class="px-6 py-3 rounded-md shadow focus:outline-none focus:ring-2 focus:ring-amber-400"
          >
            Continue
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useValidationStore } from "@/stores/validationStore";
import { useRouter } from "vue-router";

const store = useValidationStore();
const router = useRouter();

const loggedInEmail = ref("");
const loggedInName = ref("");

onMounted(async () => {
  const token = localStorage.getItem("token");
  if (token) {
    try {
      const res = await fetch("/api/me", {
        headers: { Authorization: `Bearer ${token}` },
      });
      if (!res.ok) throw new Error("Failed to fetch user info");
      const data = await res.json();
      console.log("Raw token from localStorage:", token);
      console.log("User info from backend:", data);

      if (data.user) {
        store.property.owner.email = data.user.email || "";
        store.property.owner.fullName = data.user.name || "";

        loggedInEmail.value = data.user.email || "";
        loggedInName.value = data.user.name || "";
      }
    } catch (error) {
      console.error("Error fetching user info:", error);
    }
  }
});

const isFullNameValid = computed(() => {
  return store.property.owner.fullName.trim() === loggedInName.value.trim();
});

const isEmailValid = computed(() => {
  return store.property.owner.email.trim() === loggedInEmail.value.trim();
});

const canContinue = computed(() => {
  return (
    store.property.owner.fullName.trim() !== "" &&
    isFullNameValid.value &&
    isEmailValid.value &&
    store.property.owner.phoneNumber.trim() !== "" &&
    store.property.owner.countryRegion.trim() !== "" &&
    store.property.owner.addressLine1.trim() !== "" &&
    store.property.owner.idFirstName.trim() !== "" &&
    store.property.owner.idLastName.trim() !== ""
  );
});

const goToNextPage = () => {
  store.errorMessage = "";
  if (!canContinue.value) {
    store.errorMessage =
      "Please complete all required fields and ensure full name and email match your login details.";
    return;
  }

  console.log("Submitting owner data:", store.property.owner); // Debug log

  router.push({ name: "OwnerPropertyPage11" });
   console.log("Submitting owner data:", store.property); 
};

const goBack = () => {
  router.push({ name: "OwnerPropertyPage9" });
};
</script>