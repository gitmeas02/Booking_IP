<template>

  <div class="container">
    <div class="filter-section">
      
        <div class="filter-box">
          <h2 class="filter-title">Property Type</h2>

          <div class="filter-item" v-for="(property, index) in displayedProperties" :key="property.name">
            <label class="checkbox-label">
              <input type="checkbox" :value="property.name" v-model="selected" />
              <span class="property-info">
                <span class="icon" v-if="property.icon">{{ property.icon }}</span>
                {{ property.name }}
              </span>
            </label>
            <span class="property-count">{{ property.count }}</span>
          </div>

          <button v-if="properties.length > limit" @click="showMore = !showMore" class="see-more">
            {{ showMore ? 'See Less' : 'See More' }}
            <Icon icon="iconamoon:arrow-down-2" v-if="!showMore" />
            <Icon icon="iconamoon:arrow-up-2" v-else />
          </button>

        </div>

        <div class="filter-price">
          <TwoThumbSlider/>
          
        </div>




    </div>

    <div class="room-list">
      <div class="room-detail">
        <span class="image"><img src="/src/assets/Bed/bed.png" alt="bed"></span>
        <RoomDetail/>

      </div>

      <div class="room-detail">
        <span class="image"><img src="/src/assets/Bed/bed.png" alt="bed"></span>
        <RoomDetail/>

      </div>

    </div>

  </div>

</template>

<script>
import TwoThumbSlider from './TwoThumbSlider.vue'
import { Icon } from "@iconify/vue";
import { ref, computed } from 'vue'
import RoomDetail from './RoomDetail.vue';



export default {
  components: {
    TwoThumbSlider,
    RoomDetail,
    Icon
  },

  setup() {
    const selected = ref([]);
    const showMore = ref(false);
    const limit = 3;

    const properties = [
      { name: 'Hotel', count: 123 },
      { name: 'Apartment', count: 123 },
      { name: 'Guest House', count: 123 },
      { name: 'Villa', count: 85 },
      { name: 'Resort', count: 45 },
      { name: 'Hostel', count: 12 },
    ];

    const displayedProperties = computed(() =>
      showMore.value ? properties : properties.slice(0, limit)
    );

    return {
      selected,
      showMore,
      limit,
      properties,
      displayedProperties,
    };
  },
};





</script>

<style scoped>
.container {
  display: flex;
  flex-direction: row;
  align-items: flex-start;
  justify-content: center;
  background-color: #FCFCFC;
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
  width: 100%; /* Adjust the width of the container */
  margin: 0 auto; /* Center the slider */
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
.image {
  width: 279px;
  height: 220px;
  overflow: hidden;
}
.image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.room-detail {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: start;
  width: fit-content;
}

.box-detail{
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  width: fit-content;
  
}
</style>