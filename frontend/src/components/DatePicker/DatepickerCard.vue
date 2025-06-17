<script setup>
import { ref } from 'vue'
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

const handleDateChange = (start, end) => {
    startDate.value = start;
    endDate.value = end;
};

const handleRoomChange = (payload) => {
    rooms.value = payload.rooms;
    adults.value = payload.adults;
    children.value = payload.children;
};

const handleSearch = () => {
    emit('search', {
        street: street.value,
        startDate: startDate.value ? startDate.value.toISOString().slice(0,10) : '',
        endDate: endDate.value ? endDate.value.toISOString().slice(0,10) : '',
        rooms: rooms.value,
        adults: adults.value,
        children: children.value,
        capacity: adults.value + children.value,
        pets: pets.value,
    });
};
</script>
<template>
    <div class="wrapper">
        <div id="title" style="text-align: center;">
            <p>A PLACE TO CALL HOME ON YOUR NEXT ADVENTURE</p>
        </div>
        <div class="content">
            <div id="inputSearch">
                <div class="input-container">
                    <input v-model="street" placeholder="Enter Your Destination or Property">
                    <i id="search" class="pi pi-search"></i>
                </div>
                <button @click="handleSearch"> Search</button>
            </div>
            <div class="datePickerContainer">
                <DateRangePicker @change="handleDateChange" />
            </div>
            <div class="selectRoom">
                <div style="margin-top: 20px;">
                    <SelectRoom
                        :rooms="rooms"
                        :adults="adults"
                        :children="children"
                        @change="handleRoomChange"
                    />
                </div>
                <div style="margin-top: 40px; margin-left: 10px;">
                    Traveling with pet?
                    <el-switch v-model="pets" />
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    /* .container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100vw;
        height: 100vh;
    } */
    p {
        font-size: 3vh;
        color: white;
    }
    .wrapper {
        padding: 40px 45px;
        border-radius: 36px;
        background: #0A2647;
        box-shadow: 23px 23px 50px #a4bbda,
                    -23px -23px 50px #89acd3;
    }
    #inputSearch {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        margin-bottom: 20px;
        border-radius: 12px;
        gap: 10px; /* Add spacing between input and button */
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
        background-color: #f9f9f9; /* Light background for better contrast */
    }
    .input-container input:focus {
        border-color: #00bcd4; /* Cyan border on focus */
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
        background-color: #ffffff; /* Cyan background */
        color: white;
        border: none;
        border-radius: 12px;
        color: #0A2647;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    #inputSearch button:hover {
        background-color: hsl(206, 100%, 88%); /* Darker cyan on hover */
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
    }
    .selectRoom {
        display: flex;
        color: white;
    }
</style>