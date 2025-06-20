<template>
    <div class="date-range-picker">
        <div class="date-inputs">
            <div class="date-input-container">
                <label class="date-label">FROM</label>
                <div class="date-input" @click="openCalendarForStart">
                    <span>{{ formatDate(startDate) || "Select date" }}</span>
                    <i class="pi pi-calendar calendar-icon"></i>
                </div>
            </div>
            <div class="date-input-container">
                <label class="date-label">TO</label>
                <div class="date-input" @click="openCalendarForEnd">
                    <span>{{ formatDate(endDate) || "Select date" }}</span>
                    <i class="pi pi-calendar calendar-icon"></i>
                </div>
            </div>
        </div>

        <div v-if="isCalendarOpen" ref="calendarRef" class="calendar-popup">
            <div class="calendar-navigation">
                <button @click="prevMonth" class="nav-button" aria-label="Previous month">
                    <i class="pi pi-arrow-left nav-icon"></i>
                </button>
                <div class="month-year-selectors">
                    <div class="select-container">
                        <select v-model="currentMonthIndex" class="month-select">
                            <option v-for="(month, index) in monthNames" :key="index" :value="index">
                                {{ month }}
                            </option>
                        </select>
                        <i class="pi pi-angle-down select-icon"></i>
                    </div>
                    <div class="select-container">
                        <select v-model="currentYearValue" class="year-select">
                            <option v-for="year in availableYears" :key="year" :value="year">
                                {{ year }}
                            </option>
                        </select>
                        <i class="pi pi-angle-down select-icon"></i>
                    </div>
                </div>
                <button @click="nextMonth" class="nav-button" aria-label="Next month">
                    <i class="pi pi-arrow-right nav-icon"></i>
                </button>
            </div>

            <div class="calendar-grid">
                <div v-for="day in ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su']" :key="day" class="day-header">
                    {{ day }}
                </div>
                <button v-for="(date, index) in calendarDays" :key="index" @click="handleDateSelect(date)" :class="[
                    'calendar-day',
                    isCurrentMonth(date) ? 'current-month' : 'other-month',
                    isInRange(date) ? 'in-range' : '',
                    isStartOrEndDate(date) ? 'selected-day' : ''
                ]">
                    {{ date.getDate() }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useSearchStore } from '@/stores/useSearchStore';

const emit = defineEmits(['change']);
const searchStore = useSearchStore();

const startDate = ref(searchStore.startDate ? new Date(searchStore.startDate) : null);
const endDate = ref(searchStore.endDate ? new Date(searchStore.endDate) : null);
const currentMonth = ref(startDate.value || new Date());
const isCalendarOpen = ref(false);
const selectingStartDate = ref(true);
const calendarRef = ref(null);

const monthNames = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

const currentMonthIndex = computed({
    get: () => currentMonth.value.getMonth(),
    set: (value) => {
        currentMonth.value = new Date(currentMonth.value.getFullYear(), value, 1);
    }
});

const currentYearValue = computed({
    get: () => currentMonth.value.getFullYear(),
    set: (value) => {
        currentMonth.value = new Date(value, currentMonth.value.getMonth(), 1);
    }
});

const availableYears = computed(() => {
    const range = 10;
    const currentYear = new Date().getFullYear();
    return Array.from({ length: 2 * range + 1 }, (_, i) => currentYear - range + i);
});

const formatDate = (date) => {
    if (!date) return '';
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

const handleDateSelect = (date) => {
    if (selectingStartDate.value) {
        startDate.value = date;
        endDate.value = null;
        selectingStartDate.value = false;
    } else {
        if (startDate.value && date < startDate.value) {
            endDate.value = startDate.value;
            startDate.value = date;
        } else {
            endDate.value = date;
        }
        isCalendarOpen.value = false;
        selectingStartDate.value = true;
    }
};

const prevMonth = () => {
    currentMonth.value = new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth() - 1, 1);
};

const nextMonth = () => {
    currentMonth.value = new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth() + 1, 1);
};

const openCalendarForStart = () => {
    isCalendarOpen.value = true;
    selectingStartDate.value = true;
    if (startDate.value) currentMonth.value = new Date(startDate.value);
};

const openCalendarForEnd = () => {
    isCalendarOpen.value = true;
    selectingStartDate.value = false;
    currentMonth.value = endDate.value ? new Date(endDate.value) : (startDate.value ? new Date(startDate.value) : new Date());
};

const calendarDays = computed(() => {
    const year = currentMonth.value.getFullYear();
    const month = currentMonth.value.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    let startIndex = firstDay.getDay() - 1;
    if (startIndex < 0) startIndex = 6;

    const prevMonth = new Date(year, month, 0);
    const prevDays = Array.from({ length: startIndex }, (_, i) =>
        new Date(year, month - 1, prevMonth.getDate() - startIndex + i + 1)
    );

    const currentDays = Array.from({ length: lastDay.getDate() }, (_, i) => new Date(year, month, i + 1));

    const totalCells = 42;
    const nextDays = Array.from({ length: totalCells - prevDays.length - currentDays.length }, (_, i) =>
        new Date(year, month + 1, i + 1)
    );

    return [...prevDays, ...currentDays, ...nextDays];
});

const isInRange = (date) => {
    return startDate.value && endDate.value && date > startDate.value && date < endDate.value;
};

const isStartOrEndDate = (date) => {
    const isSameDay = (a, b) =>
        a && b &&
        a.getFullYear() === b.getFullYear() &&
        a.getMonth() === b.getMonth() &&
        a.getDate() === b.getDate();

    return isSameDay(date, startDate.value) || isSameDay(date, endDate.value);
};

const isCurrentMonth = (date) => {
    return date.getMonth() === currentMonth.value.getMonth();
};

const handleClickOutside = (event) => {
    if (calendarRef.value && !calendarRef.value.contains(event.target)) {
        isCalendarOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('mousedown', handleClickOutside);
});
onUnmounted(() => {
    document.removeEventListener('mousedown', handleClickOutside);
});

// Sync to store whenever dates change
watch([startDate, endDate], () => {
    emit('change', startDate.value, endDate.value);

    // Store ISO string (or change to Date if preferred)
    searchStore.startDate = startDate.value ? startDate.value.toISOString() : '';
    searchStore.endDate = endDate.value ? endDate.value.toISOString() : '';
});
</script>

<style scoped>
.date-range-picker {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    position: relative;
    width: 100%;
}

/* Input Fields Styling */
.date-inputs {
    display: flex;
    gap: 8px;
    /* margin-bottom: 4px; */
}

.date-input-container {
    flex: 1;
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 12px;
}

.date-label {
    display: block;
    font-size: 12px;
    font-weight: 500;
    color: #6b7280;
    margin-bottom: 4px;
}

.date-input {
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    font-size: 14px;
}

.calendar-icon {
    width: 20px;
    height: 20px;
    color: #0078d7;
}

/* Calendar Popup Styling */
.calendar-popup {
    position: absolute;
    z-index: 10;
    margin-top: 8px;
    width: 100%;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    padding: 16px;
}

/* Enhanced Month/Year Navigation */
.calendar-navigation {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.month-year-selectors {
    display: flex;
    gap: 8px;
}

.select-container {
    position: relative;
}

.month-select,
.year-select {
    appearance: none;
    background: transparent;
    border: none;
    padding: 4px 24px 4px 8px;
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
    cursor: pointer;
    border-radius: 4px;
}

.month-select:hover,
.year-select:hover {
    background-color: #f3f4f6;
}

.month-select:focus,
.year-select:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(0, 120, 215, 0.2);
}

.select-icon {
    position: absolute;
    right: 4px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    color: #6b7280;
    pointer-events: none;
}

.nav-button {
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    color: #6b7280;
    border-radius: 4px;
}

.nav-button:hover {
    background-color: #f3f4f6;
}

.nav-icon {
    width: 16px;
    height: 16px;
}

/* Calendar Grid */
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
}

.day-header {
    text-align: center;
    font-size: 12px;
    font-weight: 500;
    color: #6b7280;
    padding: 4px 0;
}

.calendar-day {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 32px;
    width: 32px;
    border: none;
    background: none;
    border-radius: 50%;
    font-size: 14px;
    cursor: pointer;
    margin: 2px auto;
}

.current-month {
    color: #1f2937;
}

.other-month {
    color: #d1d5db;
}

.in-range {
    background-color: #e6f3ff;
}

.selected-day {
    background-color: #0078d7;
    color: white;
}

/* Responsive adjustments */
/* @media (max-width: 640px) {
    .date-inputs {
        flex-direction: column;
    }
    
    .calendar-day {
        height: 28px;
        width: 28px;
        font-size: 12px;
    }
    
    .month-year-selectors {
        flex-direction: column;
        gap: 4px;
    }
    
    .month-select, .year-select {
        font-size: 14px;
        padding: 2px 20px 2px 6px;
    }
} */
</style>
