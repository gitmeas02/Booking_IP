<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'    // <-- import useRouter
import { useSearchStore } from '@/stores/search'
import DateRangePicker from './DateRangePicker.vue'
// import SelectRoom from './SelectRoom.vue'

const router = useRouter()                 // <-- get router instance
const store = useSearchStore()

const destinationInput = ref('')
// const value1 = ref(false) // Pet toggle switch

const handleDateChange = (start, end) => {
  store.setDates({ start, end })  // Update store with new dates
};

// Trigger search
const handleSearch = () => {
  store.setDestination(destinationInput.value)
  store.searchRooms()

  console.log("Search for Destination:", destinationInput.value)

  if (store.startDate && store.endDate) {
    console.log('Date range selected:');
    console.log('Start:', store.startDate);
    console.log('End:', store.endDate);
  } else {
    console.log('No date range selected');
  }

  const query = {
    destination: destinationInput.value
  }

  if (store.startDate && store.endDate) {
    query.start = store.startDate
    query.end = store.endDate
  }

  router.push({ path: '/listroom', query })  // Pass params as query string
}

</script>


<template>
  <div class="wrapper">
    <div id="title" style="text-align: center;">
      <p>A PLACE TO CALL HOME ON YOUR NEXT ADVENTURE</p>
    </div>

    <div class="content">
      <!-- Search Input -->
      <div id="inputSearch">
        <div class="input-container">
          <input v-model="destinationInput" type="text" placeholder="Enter Your Destination or Property" />
          <i id="search" class="pi pi-search"></i>
        </div>
        <button @click="handleSearch">Search</button>
      </div>

      <!-- Date Picker -->
      <div class="datePickerContainer">
        <!-- Listen for 'change' event and call handleDateChange -->
        <DateRangePicker @change="handleDateChange" />
      </div>

      <!-- Guests and Pet Toggle -->
      <div class="selectRoom">
        <!--
        <div style="margin-top: 20px;">
          <SelectRoom />
        </div>
        <div style="margin-top: 40px; margin-left: 10px; color: white;">
          Traveling with pet?
          <el-switch v-model="value1" />
        </div>
        -->
      </div>
    </div>
  </div>
</template>

<style scoped>
/* your styles unchanged */
p {
  font-size: 3vh;
  color: white;
}

.wrapper {
  padding: 40px 45px;
  border-radius: 36px;
  background: #0a2647;
  box-shadow: 23px 23px 50px #a4bbda, -23px -23px 50px #89acd3;
}

#inputSearch {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 20px;
  margin-bottom: 20px;
  border-radius: 12px;
  gap: 10px;
}

.input-container {
  position: relative;
  width: 100%;
}

.input-container input {
  width: 100%;
  padding: 14px;
  padding-left: 40px;
  border-radius: 12px;
  border: 1px solid #ccc;
  outline: none;
  font-size: 1rem;
  transition: all 0.3s ease;
  background-color: #f9f9f9;
}

.input-container input:focus {
  border-color: #00bcd4;
  box-shadow: 0 0 8px rgba(0, 188, 212, 0.5);
}

.input-container input::placeholder {
  color: #aaa;
  font-style: italic;
}

.input-container i {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 1.2rem;
  color: #666;
  pointer-events: none;
}

#inputSearch button {
  padding: 14px 40px;
  background-color: #ffffff;
  color: #0a2647;
  border: none;
  border-radius: 12px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

#inputSearch button:hover {
  background-color: hsl(206, 100%, 88%);
  box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
}

.selectRoom {
  display: flex;
  color: white;
}
</style>
