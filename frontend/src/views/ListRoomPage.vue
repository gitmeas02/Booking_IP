<template>
  <div class="flex items-center justify-center flex-col pt-14 pl-14 pr-14">
    <!-- Search Bar -->
    <div class="rounded-xl bg-[#0A2647] w-full px-14 pt-7 pb-7">
      <div class="flex items-center justify-center flex-row pt-2 gap-2 w-full">
        <input v-model="searchStore.street" type="text" placeholder="Enter Your Destination or Property"
          class="bg-white h-[66px] w-full rounded-lg pl-10" />
        <button @click="searchHotels" class="w-[200px] h-[66px] rounded-lg bg-white font-bold">
          Search
        </button>
      </div>

      <!-- Filters -->
      <div class="flex items-center justify-center flex-row pt-2 gap-2 w-full">
        <DateRangePicker v-model:startDate="searchStore.startDate" v-model:endDate="searchStore.endDate" />
        <SelectRoom v-model:rooms="searchStore.rooms" v-model:adults="searchStore.adults"
          v-model:children="searchStore.children" v-model:capacity="searchStore.capacity"
          v-model:pets="searchStore.pets" />
      </div>
    </div>

    <!-- Property Filter + Room List -->
    <div class="container">
      <div class="filter-section">
        <div class="filter-box">
          <h2 class="filter-title">Property Type</h2>

          <div class="filter-item" v-for="(property, index) in displayedProperties" :key="property.name">
            <label class="checkbox-label">
              <input type="checkbox" :value="property.name" v-model="selected" @change="filterByProperty" />
              <span class="property-info">
                <span class="icon" v-if="property.icon">{{ property.icon }}</span>
                {{ property.name }}
              </span>
            </label>
            <span class="property-count">{{ property.count }}</span>
          </div>

          <button v-if="properties.length > limit" @click="showMore = !showMore" class="see-more">
            {{ showMore ? "See Less" : "See More" }}
          </button>
        </div>

        <div class="filter-price">
          <TwoThumbSlider />
        </div>
      </div>

      <div class="room-list">
        <RoomCard v-for="(room, index) in filteredHotels" :key="room.id || index" :room="room"
          @click="viewRoomDetails(room.id)" />
        <div v-if="loading" class="text-center p-4 text-gray-600">Loading hotels...</div>
        <div v-if="error" class="text-center p-4 text-red-600">{{ error }}</div>
        <div v-if="!loading && filteredHotels.length === 0" class="text-center p-4 text-gray-600">
          No hotels found matching your criteria.
        </div>
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

const selected = ref([]);
const showMore = ref(false);
const limit = 3;

const loading = ref(false);
const error = ref(null);

const hotels = ref([]);
const filteredHotels = ref([]);

// Computed for property list from store
const properties = computed(() => roomStore.properties || []);
const displayedProperties = computed(() =>
  showMore.value ? properties.value : properties.value.slice(0, limit)
);

// Filter hotels by selected property type(s)
const filterByProperty = () => {
  if (selected.value.length === 0) {
    filteredHotels.value = [...hotels.value];
  } else {
    filteredHotels.value = hotels.value.filter((hotel) =>
      selected.value.includes(hotel.property_type)
    );
  }
};

// Load hotels from backend with filters from searchStore
const searchHotels = async () => {
  loading.value = true;
  error.value = null;
  try {
    const response = await roomStore.fetchHotels({
      street: searchStore.street,
      startDate: searchStore.startDate
        ? new Date(searchStore.startDate).toISOString().slice(0, 10)
        : "",
      endDate: searchStore.endDate
        ? new Date(searchStore.endDate).toISOString().slice(0, 10)
        : "",
      capacity: searchStore.capacity,
      pets: searchStore.pets,
    });
    hotels.value = response;
    filteredHotels.value = response;

    // Reset property filters after new search
    selected.value = [];
  } catch (err) {
    error.value = "Failed to load hotels";
  } finally {
    loading.value = false;
  }
};

// Navigate to room details page with current search query params
const viewRoomDetails = (hotelId) => {
  console.log("Navigating to hotel details for hotelId:", hotelId);
  router.push({
    name: "ProductsDetails",
    params: { id: hotelId },
    query: {
      street: searchStore.street,
      startDate: searchStore.startDate ? searchStore.startDate.toString() : null,
      endDate: searchStore.endDate ? searchStore.endDate.toString() : null,
      rooms: searchStore.rooms,
      adults: searchStore.adults,
      children: searchStore.children,
      pets: searchStore.pets ? "true" : "false",
      capacity: searchStore.capacity,
    },
  });
};

// On mount, restore search params from route query and perform initial search
onMounted(async () => {
  if (route.query.street) searchStore.street = route.query.street;
  if (route.query.startDate) searchStore.startDate = route.query.startDate;
  if (route.query.endDate) searchStore.endDate = route.query.endDate;
  if (route.query.rooms) searchStore.rooms = parseInt(route.query.rooms);
  if (route.query.adults) searchStore.adults = parseInt(route.query.adults);
  if (route.query.children) searchStore.children = parseInt(route.query.children);
  if (route.query.pets) searchStore.pets = route.query.pets === "true";

  await searchHotels();
});
</script>

<style scoped>
/* your styles unchanged */
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
