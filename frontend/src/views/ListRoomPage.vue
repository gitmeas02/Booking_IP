<template>
  <div class="flex items-center justify-center flex-col pt-14 pl-14 pr-14">
    <!-- Search Bar -->
    <div class="rounded-xl bg-[#0A2647] w-full px-14 pt-7 pb-7">
      <div class="flex items-center justify-center flex-row pt-2 gap-2 w-full">
        <input
          type="text"
          placeholder="Enter Your Destination or Property"
          class="bg-white h-[66px] w-full rounded-lg pl-10"
        />
        <button class="w-[200px] h-[66px] rounded-lg bg-white font-bold">
          Search
        </button>
      </div>

      <!-- Filters -->
      <div class="flex items-center justify-center flex-row pt-2 gap-2 w-full">
        <DateRangePicker />
        <SelectRoom />
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
            <Icon icon="iconamoon:arrow-down-2" v-if="!showMore" />
            <Icon icon="iconamoon:arrow-up-2" v-else />
          </button>
        </div>

        <div class="filter-price">
          <TwoThumbSlider />
        </div>
      </div>

      <div class="room-list">
        <RoomCard
          v-for="(room, index) in hotels"
          :key="index"
          :room="room"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import DateRangePicker from "@/components/DatePicker/DateRangePicker.vue";
import SelectRoom from "@/components/DatePicker/selectRoom.vue";
import RoomCard from "@/components/ListHotel/RoomCard.vue";
import TwoThumbSlider from "@/components/ListHotel/TwoThumbSlider.vue";
import { useRoomStore } from "@/stores/store";
import { Icon } from "@iconify/vue";

const selected = ref([]);
const showMore = ref(false);
const limit = 3;
const hotels = ref([]);
const loading = ref(true);
const error = ref(null);

const roomStore = useRoomStore();

const properties = computed(() => roomStore.properties || []);
const displayedProperties = computed(() =>
  showMore.value ? properties.value : properties.value.slice(0, limit)
);

onMounted(async () => {
  try {
    hotels.value = await roomStore.fetchHotels();
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
<!-- 
<style scoped>
.container {
  display: flex;
  flex-direction: row;
  align-items: flex-start;
  justify-content: center;
  /* background-color: #fcfcfc; */
  gap: 25px;
  padding-top: 15px;
  padding-bottom: 15px;
}

.filter-section {
  display: flex;
  justify-content: center; /* horizontal center */
  align-items: center; /* vertical top */
  flex-direction: column;
  width: 230px;
  padding: 10px;
  margin: 0;
}
.filter-box {
  width: 100%;
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
  justify-content: space-between;
  background-color: rgb(255, 255, 255);
  width: fit-content;
  gap: 12px;
  margin: 0px;
}

.box-detail {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  width: fit-content;
}
</style> -->