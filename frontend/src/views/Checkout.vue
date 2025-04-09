<template>
  <!-- Hotel Information -->
  <div class="grid responsive-grid mt-8 mb-4 mx-4 md:mx-16 items-start grid-cols-1 md:grid-cols-2
    gap-8 border-gray-200 border-1 rounded-[12px] p-8">
    <!-- Hotel Details -->
    <div>
      <h2 class="text-2xl font-bold mb-4">Hotel Details</h2>
      <div class="relative h-48 mb-4 rounded-lg overflow-hidden">
        <img
          src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
          alt="Khun Hoth Hotel"
          class="h-full w-full object-cover"
        />
      </div>
      <div class="flex items-start gap-3 ">
        <MapPin class="h-5 w-5 text-primary mt-0.5" />
        <div>
          <h3 class="font-medium">Location</h3>
          <p class="text-muted-foreground">
            10 minutes from Angkor Wat temple complex, Siem Reap, Cambodia
          </p>
        </div>
      </div>

      <div class="flex items-start gap-3">
        <Shield class="h-5 w-5 text-primary mt-0.5" />
        <div>
          <h3 class="font-medium">Safety Measures</h3>
          <p class="text-muted-foreground">
            Enhanced cleaning protocols, 24/7 security, in-room safety deposit
            box
          </p>
        </div>
      </div>
    </div>
    <!-- Room Details -->
    <div>
      <h2 class="text-2xl font-bold mb-4">Room Details</h2>
      <div class="relative h-48 mb-4 rounded-lg overflow-hidden">
        <img
          src="https://images.unsplash.com/photo-1483058712412-4245e9b90334?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"
          alt="Deluxe Double Room"
          class="h-full w-full object-cover"
        />
      </div>
      <div class="flex items-start gap-3 mt-2">
        <Bed class="h-5 w-5 text-primary mt-0.5" />
        <div>
          <h3 class="font-medium">Deluxe Double Room</h3>
          <p class="text-muted-foreground">
            King-sized bed, 30m² room with balcony and garden view
          </p>
        </div>
      </div>
      <div class="mt-4">
        <ul class="ml-8 list-disc space-y-1">
          <li class="text-muted-foreground">
            Private bathroom with rainfall shower
          </li>
          <li class="text-muted-foreground">
            Complimentary toiletries and hairdryer
          </li>
          <li class="text-muted-foreground">
            40" Smart TV with international channels
          </li>
          <li class="text-muted-foreground">
            Tea and coffee making facilities
          </li>
          <li class="text-muted-foreground">
            Mini-refrigerator and room service available
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div
    class="grid responsive-grid mt-8 mb-4 mx-4 md:mx-16 items-start grid-cols-1 md:grid-cols-3 gap-8"
  >
    <div class="min-w-[300px] w-full">
      <BookingSummary />
    </div>
    <div class="md:col-span-2">
      <FormInfo />
    </div>
  </div>
</template>
<script>
import FormInfo from "@/components/FormInfo.vue";
import BookingSummary from "@/components/BookingSummary.vue";
import { MapPin, Shield, Bed } from "lucide-vue-next";
export default {
  name: "Checkout",
  components: {
    BookingSummary,
    FormInfo,
    Bed,
    MapPin,
    Shield,
  },
};
</script>
<style scoped></style>
