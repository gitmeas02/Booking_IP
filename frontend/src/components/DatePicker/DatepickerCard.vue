<script setup>
import { computed, ref } from 'vue';
import DateRangePicker from './DateRangePicker.vue';
import SelectRoom from './selectRoom.vue';

const emit = defineEmits(['search']);

const street = ref('');
const startDate = ref(null);
const endDate = ref(null);
const rooms = ref(1);
const adults = ref(2);
const children = ref(0);
const pets = ref(false);
const isSearching = ref(false);

// ✅ Format date in YYYY-MM-DD based on local time (not UTC)
const formatDateOnly = (date) => {
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    return `${year}-${month}-${day}`;
};

const handleDateChange = (start, end) => {
    startDate.value = start;
    endDate.value = end;
};

const handleRoomChange = (payload) => {
    rooms.value = payload.rooms;
    adults.value = payload.adults;
    children.value = payload.children;
};

// Debounced search function
let searchTimeout;
const handleSearch = () => {
    if (isSearching.value) return;
    
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        isSearching.value = true;
        
        try {
            emit('search', {
                street: street.value,
                startDate: startDate.value ? formatDateOnly(startDate.value) : '',
                endDate: endDate.value ? formatDateOnly(endDate.value) : '',
                rooms: rooms.value,
                adults: adults.value,
                children: children.value,
                capacity: adults.value + children.value,
                pets: pets.value,
            });
        } finally {
            setTimeout(() => {
                isSearching.value = false;
            }, 1000);
        }
    }, 300);
};

// Computed property for button text
const buttonText = computed(() => {
    return isSearching.value ? 'Searching...' : 'Search';
});
</script>

<template>
    <div class="wrapper">
        <div id="title" style="text-align: center;">
            <p>A PLACE TO CALL HOME ON YOUR NEXT ADVENTURE</p>
        </div>
        <div class="content">
            <div id="inputSearch">
                <div class="input-container">
                    <input v-model="street" placeholder="Enter Your Destination or Property" />
                    <i id="search" class="pi pi-search"></i>
                </div>
                <button @click="handleSearch" :disabled="isSearching" class="search-btn">
                    {{ buttonText }}
                </button>
            </div>

            <div class="datePickerContainer">
                <DateRangePicker @change="handleDateChange" />
            </div>

            <div class="selectRoom">
                <div style="margin-top: 20px;">
                    <SelectRoom :rooms="rooms" :adults="adults" :children="children" @change="handleRoomChange" />
                </div>
                <div style="margin-top: 40px; margin-left: 10px; color: white;">
                    Traveling with pet?
                    <el-switch v-model="pets" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
p {
    font-size: 3vh;
    color: white;
}

.wrapper {
    padding: 40px 45px;
    border-radius: 36px;
    background: #0A2647;
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
    color: #0A2647;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

#inputSearch button:hover:not(:disabled) {
    background-color: hsl(206, 100%, 88%);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
}

#inputSearch button:disabled {
    background-color: #e0e0e0;
    color: #999;
    cursor: not-allowed;
    box-shadow: none;
}

.selectRoom {
    display: flex;
    color: white;
}
</style>
