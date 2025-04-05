<script setup>
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import DatePicker from 'primevue/datepicker';
import 'primeicons/primeicons.css'
import { ref } from 'vue';

const searchQuery = ref('');
const dateRange = ref('');
</script>

<template>
    <div class="search-hero-container">
        <div class="search-card">
            <div class="search-header">
                <h2 class="search-title">A PLACE TO CALL HOME ON YOUR NEXT ADVENTURE</h2>
            </div>
            <div class="search-controls">
                <div class="destination-search">
                    <IconField>
                        <InputText id="destinationInput" v-model="value1" placeholder="Enter the destination or property" />
                        <InputIcon class="pi pi-search" />
                    </IconField>
                </div>
                <div class="date-range-picker">
                    <IconField class="custom-datepicker">
                        <DatePicker
                        v-model="dateRange"
                        showIcon fluid iconDisplay="input"
                        selectionMode="range"
                        :manualInput="false"
                        showButtonBar
                        placeholder="Select Date"
                        inputClass="custom-datepicker-input"
                        />
                    </IconField>
                </div>
                <div>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.search-hero-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.search-card {
    display: flex;
    padding: 20px;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    width: 800px;
    height: 500px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.search-header {
    display:flex;
    justify-content: center;
    width: 750px;
    margin-bottom: 2rem;
}

.search-title {
    color: black;
    line-height: 1.2;
}

.search-controls {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.destination-search {
    width: 100%;
    margin-bottom: 1rem;
}

#destinationInput {
    width: 100%;
    height: 50px;
    padding: 0 1rem;
    background-color: white;
    color:black;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
}

.search-icon {
    color: #afafaf;
    padding-top:14px;
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}
.date-range-picker {
    width: 35%;
}

:deep(.custom-datepicker-input) {
    background-color: #ffffff;
    color:rgb(0, 95, 238);
    border: 1px solid #ced4da;
    text-align: center;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    width: 100%;

}
:deep(.custom-datepicker-input::placeholder) {
    text-align: center;
    color: #6c757d; /* Optional: change placeholder color */
}


</style>