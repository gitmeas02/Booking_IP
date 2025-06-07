<template>
    <div class="max-w-screen-xl mx-auto px-4 py-10 font-sans">
        <!-- Heading -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-neutral-800">
                Some important info before you list your hotel
            </h2>
        </div>

        <!-- Form Section -->
        <div class="flex flex-col md:flex-row gap-10">
            <!-- Left Column -->
            <div class="flex-1 space-y-6">
                <!-- Form Card -->
                <div class="bg-white p-6 rounded-lg shadow-md space-y-8">
                    <!-- Info Block 1 -->
                    <div class="flex flex-row gap-3 items-start">
                        <div class="w-7 h-7 flex items-center justify-center text-blue-600">
                            <Icon icon="mdi:check-circle-outline" class="w-full h-full" />
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-semibold mb-2">
                                Are bookings confirmed right away?
                            </h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Yes. They're confirmed as soon as a guest makes a booking.
                            </p>
                        </div>
                    </div>

                    <!-- Info Block 2 -->
                    <div class="flex flex-row gap-3 items-start">
                        <div class="w-7 h-7 flex items-center justify-center text-blue-600">
                            <Icon icon="mdi:people-group-outline" class="w-full h-full" />
                        </div>
                        <div class="flex-1"> 
                            <h4 class="text-xl font-semibold mb-2">
                                Can I choose who stays at my place?
                            </h4>
                            <p class="text-sm text-gray-600 mt-1">
                                No. If a date is open in your calendar, any guest using our site can book it.
                            </p>
                        </div>
                    </div>

                    <!-- Info Block 3 -->
                    <div class="flex flex-row gap-3 items-start">
                        <div class="w-7 h-7 flex items-center justify-center text-blue-600">
                            <Icon icon="mdi:calendar-blank" class="w-full h-full" />
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-semibold mb-2">
                                Can I decide when I get bookings?
                            </h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Yes. The best way to do so is by keeping your calendar up to date.
                                Close any dates you don't want bookings. If you have bookings on other
                                sites, close those dates, too.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-between">
                    <button
                        class="px-6 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400"
                        @click="handleBack">
                        Back
                    </button>
                   <button
                    class="px-6 py-2 text-white bg-black rounded-md shadow-md hover:bg-gray-300 hover:text-black focus:outline-none focus:ring-2 "
                    @click="handleContinue">
                    Continue
                    </button>
                </div>
            </div>

            <!-- Right Column -->
            <div class="flex-1">
                <!-- Optional content -->
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

    const handleBack = () => {

      router.push({ name: "OwnerPropertyPage10" });
    };

    const handleContinue = () => {
      try {
        const success =  store.submit(); // assume it returns a boolean
        console.log("Current property data:", JSON.stringify(store.property, null, 2));
        if (success) {
          router.push("/admin");
        } else {
          console.log("Submission failed:", store.property);
        }
      } catch (err) {
        console.error("An error occurred during submission:", err);
      }
    };

    return { store, handleBack, handleContinue };
  },
});
</script>


<style scoped></style>
