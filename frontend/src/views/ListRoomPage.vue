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
<!--    single room    -->

      <HotelCard v-for="( hotel, index) in hotels"  :key="index" :hotel="hotel" />
    </div>

  </div>

</template>

<script>
import TwoThumbSlider from '@/components/ListHotel/TwoThumbSlider.vue'
import { Icon } from "@iconify/vue";
import { ref, computed } from 'vue'
import HotelCard from '@/components/ListHotel/HotelCard.vue';





export default {
  components: {
    TwoThumbSlider,
    Icon,
    HotelCard
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
    const hotels=ref([
                {
                    id: 1,
                    name: "Hotel 1",
                    stars: 5,
                    reviewScore: 4.5,
                    commentsCount: 10,
                    location_image: "url",
                    location: {
                        city: "Phnom Penh",
                        distanceFromCenter: "1.7 km"
                    },
                    comments: ["Free breakfast", "Free Wi-Fi"],
                    price: 100,
                    rooms: [
                        {
                            id: 1,
                            name: "Room 1",
                            size: "40m²",
                            beds: "1 bed",
                            images: [
                                './src/assets/Bed/bed.png',
                            ],
                            price: 100,
                            rating: 4.5,
                            reviews: 10,
                            type: "King Bed",
                            details: "This is a king bed room with a beautiful view.",
                            amenities: ["Air Conditioning", "TV", "Mini Bar", "Free Breakfast", "Free Wi-Fi"],
                        }
                    ],
                    amenities: ["Air Conditioning", "TV", "Mini Bar", "Free Breakfast", "Free Wi-Fi"],
                },
                {
                    id: 2,
                    name: "Hotel 2",
                    stars: 4,
                    reviewScore: 4.2,
                    commentsCount: 25,
                    location_image: "url",
                    location: {
                        city: "Siem Reap",
                        distanceFromCenter: "2.3 km"
                    },
                    comments: ["Great location", "Friendly staff"],
                    price: 80,
                    rooms: [
                        {
                            id: 2,
                            name: "Room 2",
                            size: "35m²",
                            beds: "2 beds",
                            images: [
                                './src/assets//Bed/bed.png',
                                
                            ],
                            price: 80,
                            rating: 4.2,
                            reviews: 25,
                            type: "Double Bed",
                            details: "A cozy room with modern amenities.",
                            amenities: ["Air Conditioning", "TV", "Free Wi-Fi", "Mini Bar"],
                        }
                    ],
                    amenities: ["Air Conditioning", "TV", "Mini Bar", "Free Wi-Fi"],
                },
                {
                    id: 3,
                    name: "Hotel 3",
                    stars: 3,
                    reviewScore: 3.8,
                    commentsCount: 15,
                    location_image: "url",
                    location: {
                        city: "Battambang",
                        distanceFromCenter: "3.5 km"
                    },
                    comments: ["Affordable price", "Clean rooms"],
                    price: 60,
                    rooms: [
                        {
                            id: 3,
                            name: "Room 3",
                            size: "30m²",
                            beds: "1 bed",
                            images: [
                                './src/assets/Bed/bed.png',
                                
                            ],
                            price: 60,
                            rating: 3.8,
                            reviews: 15,
                            type: "Single Bed",
                            details: "A budget-friendly room with basic facilities.",
                            amenities: ["Air Conditioning", "Free Wi-Fi"],
                        }
                    ],
                    amenities: ["Air Conditioning", "Free Wi-Fi"],
                },
                {
                    id: 4,
                    name: "Hotel 4",
                    stars: 5,
                    reviewScore: 4.9,
                    commentsCount: 50,
                    location_image: "url",
                    location: {
                        city: "Sihanoukville",
                        distanceFromCenter: "0.5 km"
                    },
                    comments: ["Luxurious stay", "Amazing view"],
                    price: 200,
                    rooms: [
                        {
                            id: 4,
                            name: "Room 4",
                            size: "50m²",
                            beds: "1 king bed",
                            images: [
                                './src/assets/Bed/bed.png',
                               
                            ],
                            price: 200,
                            rating: 4.9,
                            reviews: 50,
                            type: "King Suite",
                            details: "A luxurious suite with a stunning ocean view.",
                            amenities: ["Air Conditioning", "TV", "Mini Bar", "Free Breakfast", "Free Wi-Fi", "Hot Water"],
                        }
                    ],
                    amenities: ["Air Conditioning", "TV", "Mini Bar", "Free Breakfast", "Free Wi-Fi", "Hot Water"],
                }
            ]);


    const displayedProperties = computed(() =>
      showMore.value ? properties : properties.slice(0, limit)
    );

    return {
      hotels,
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

.box-detail{
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  width: fit-content;
  
}
</style>