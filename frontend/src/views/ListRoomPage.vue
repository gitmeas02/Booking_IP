<template>
  <div class="flex items-center justify-center flex-col pt-14 pl-14 pr-14">
    <!-- Search Bar -->
    <div class="rounded-xl bg-[#0A2647] w-full px-14 pt-7 pb-7">
      <div class="flex items-center justify-center flex-row pt-2 gap-2 w-full">
        <input
          v-model="searchStore.street"
          type="text"
          placeholder="Enter Your Destination or Property"
          class="bg-white h-[66px] w-full rounded-lg pl-10"
        />
        <button @click="searchHotels" class="w-[200px] h-[66px] rounded-lg bg-white font-bold">
          Search
        </button>
      </div>

      <!-- Filters -->
      <div class="flex items-center justify-center flex-row pt-2 gap-2 w-full">
        <DateRangePicker
          v-model:startDate="searchStore.startDate"
          v-model:endDate="searchStore.endDate"
        />
        <SelectRoom />
      </div>

      <!-- Show selected date range -->
      <div v-if="searchStore.startDate && searchStore.endDate" class="text-white text-sm mt-2">
        Selected: {{ formattedDateRange }}
      </div>
    </div>

    <!-- Property Filter + Room List -->
    <div class="container">
      <div class="filter-section">
        <div class="filter-box">
          <h2 class="filter-title">Property Type</h2>

          <div
            class="filter-item"
            v-for="(property, index) in displayedProperties"
            :key="property.name"
          >
            <label class="checkbox-label">
              <input
                type="checkbox"
                :value="property.name"
                v-model="selected"
              />
              <span class="property-info">
                <span class="icon" v-if="property.icon">{{ property.icon }}</span>
                {{ property.name }}
              </span>
            </label>
            <span class="property-count">{{ property.count }}</span>
          </div>

          <button
            v-if="properties.length > limit"
            @click="showMore = !showMore"
            class="see-more"
          >
            {{ showMore ? "See Less" : "See More" }}
          </button>
        </div>

        <div class="filter-price">
          <TwoThumbSlider />
        </div>
      </div>

      <div class="room-list">
        <RoomCard
          v-for="(room, index) in filteredHotels"
          :key="index"
          :room="room"
          @click="viewRoomDetails(room.id)"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useRoomStore } from "@/stores/store";
import { useSearchStore } from "@/stores/useSearchStore";

import DateRangePicker from "@/components/DatePicker/DateRangePicker.vue";
import SelectRoom from "@/components/DatePicker/selectRoom.vue";
import RoomCard from "@/components/ListHotel/RoomCard.vue";
import TwoThumbSlider from "@/components/ListHotel/TwoThumbSlider.vue";

const route = useRoute();
const router = useRouter();
const roomStore = useRoomStore();
const searchStore = useSearchStore();

// Property filters
const selected = ref([]);
const showMore = ref(false);
const limit = 3;

const hotels = ref([]);
const filteredHotels = ref([]);
const loading = ref(true);
const error = ref(null);

const properties = computed(() => roomStore.properties || []);
const displayedProperties = computed(() =>
  showMore.value ? properties.value : properties.value.slice(0, limit)
);

// Format the selected date range for display
const formattedDateRange = computed(() => {
  const start = searchStore.startDate ? new Date(searchStore.startDate).toLocaleDateString() : '';
  const end = searchStore.endDate ? new Date(searchStore.endDate).toLocaleDateString() : '';
  return `${start} - ${end}`;
});

const searchHotels = async () => {
  try {
    loading.value = true;
    const response = await roomStore.fetchHotels({
      location: searchStore.street,
      startDate: searchStore.startDate,
      endDate: searchStore.endDate,
    });
    filteredHotels.value = response;
  } catch (err) {
    error.value = "Failed to load hotels";
  } finally {
    loading.value = false;
  }
};

const viewRoomDetails = (roomId) => {
  router.push({
    name: "ProductsDetails",
    params: { id: roomId },
    query: {
      checkin: searchStore.startDate,
      checkout: searchStore.endDate,
    },
  });
};

// Restore query to store on initial mount
onMounted(async () => {
  // Restore search data from query string if available
  if (route.query.street) searchStore.street = route.query.street;
  if (route.query.startDate) searchStore.startDate = new Date(route.query.startDate);
  if (route.query.endDate) searchStore.endDate = new Date(route.query.endDate);
  if (route.query.rooms) searchStore.rooms = parseInt(route.query.rooms);
  if (route.query.adults) searchStore.adults = parseInt(route.query.adults);
  if (route.query.children) searchStore.children = parseInt(route.query.children);
  if (route.query.pets) searchStore.pets = route.query.pets === 'true';

  try {
    hotels.value = await roomStore.fetchHotels();
    filteredHotels.value = hotels.value;
  } catch (err) {
    error.value = "Failed to load hotels";
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.container {
  display: flex;
  flex-direction: row;
  align-items: flex-start;
  justify-content: center;
  gap: 25px;
  padding-top: 15px;
  padding-bottom: 15px;
}

.filter-section {
  display: flex;
  flex-direction: column;
  width: 230px;
  padding: 10px;
}

.filter-title {
  font-size: 20px;
  font-weight: bold;
  margin-bottom: 12px;
}

.filter-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 6px 0;
  border-bottom: 1px solid #eee;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 8px;
}

.checkbox-label input[type="checkbox"] {
  width: 25px;
  height: 25px;
}

.property-info {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 16px;
}

.property-count {
  font-size: 14px;
  color: #666;
}

.icon {
  font-size: 18px;
}

.see-more {
  background: none;
  border: none;
  color: #007bff;
  cursor: pointer;
  margin-top: 8px;
  font-size: 14px;
}

.see-more:hover {
  text-decoration: underline;
}

.filter-price {
  width: 100%;
  max-width: 400px;
  margin: auto;
}

.room-list {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  background-color: #fff;
}
</style>
