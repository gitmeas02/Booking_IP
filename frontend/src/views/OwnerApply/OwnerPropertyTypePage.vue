<template>
  <div class="max-w-screen-xl mx-auto px-4 py-10 text-center font-sans">
    <h2 class="text-3xl font-extrabold text-neutral-800 mb-3">
      List your property and start welcoming guests in<br />
      <span class="text-neutral-800">no time!</span>
    </h2>

    <p class="text-gray-600 text-base mb-8">
      To get started, select the type of property you want to list.
    </p>

    <!-- Error message -->
    <div v-if="store.errorMessage" class="text-red-500 mb-4 text-sm">
      {{ store.errorMessage }}
    </div>

    <!-- Property Cards -->
    <div
      class="grid gap-6 justify-items-center grid-cols-[repeat(auto-fit,minmax(260px,1fr))]"
    >
      <Card
        v-for="(card, index) in cardData"
        :key="index"
        v-bind="card"
        :isSelected="store.property.type === card.property_type"
        @click="selectType(card.property_type)"
      />
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";
import { useValidationStore } from "@/stores/validationStore";
import { useRouter } from "vue-router";
import Card from "@/components/Owner/Card.vue";

export default defineComponent({
  components: { Card },
  setup() {
    const store = useValidationStore();
    const router = useRouter();

    const cardData = [
      {
        icon: "material-symbols:apartment",
        property_type: "Apartment",
        description:
          "Furnished and self-catering accommodations where guests rent the entire place.",
      },
      {
        icon: "material-symbols:house",
        property_type: "Homes",
        description: "Properties like apartments, vacation homes, villas, etc.",
      },
      {
        icon: "material-symbols:hotel",
        property_type: "Hotel and More",
        description:
          "Properties like hotels, guest houses, hostels, condo hotels, etc.",
      },
      {
        icon: "material-symbols:camping",
        property_type: "Alternative Places",
        description: "Properties like boats, campgrounds, luxury tents, etc.",
      },
    ];

    const selectType = (type) => {
      store.setPropertyValue("type", type);
      store.errorMessage = "";
      router.push({ name: "OwnerPropertyPage2" });
      console.log("Selected property type:", type);    };

    return {
      store,
      cardData,
      selectType,
    };
  },
});
</script>
