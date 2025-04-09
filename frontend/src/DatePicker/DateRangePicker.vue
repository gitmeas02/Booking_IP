<template>
    <div class="date-range-picker">
        <!-- Input Fields -->
        <div class="date-inputs">
            <div class="date-input-container">
                <label class="date-label">FROM</label>
                <div class="date-input" @click="openCalendarForStart">
                    <span>{{ formatDate(startDate) || "Select date" }}</span>
                    <CalendarIcon class="calendar-icon" />
                </div>
            </div>
            <div class="date-input-container">
                <label class="date-label">TO</label>
                <div class="date-input" @click="openCalendarForEnd">
                    <span>{{ formatDate(endDate) || "Select date" }}</span>
                    <CalendarIcon class="calendar-icon" />
                </div>
            </div>
        </div>

        <!-- Calendar Popup -->
        <div v-if="isCalendarOpen" ref="calendarRef" class="calendar-popup">
            <div class="calendar-header">
                <button @click="prevMonth" class="nav-button" aria-label="Previous month">
                    <ChevronLeftIcon class="nav-icon" />
                </button>
                <h2 class="month-title">{{ getMonthName() }}</h2>
                <button @click="nextMonth" class="nav-button" aria-label="Next month">
                    <ChevronRightIcon class="nav-icon" />
                </button>
            </div>

            <div class="calendar-grid">
                <!-- Day Headers -->
                <div
                    v-for="day in ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su']"
                    :key="day"
                    class="day-header"
                >
                    {{ day }}
                </div>

                <!-- Calendar Days -->
                <button
                    v-for="(date, index) in calendarDays"
                    :key="index"
                    @click="handleDateSelect(date)"
                    :class="[
                        'calendar-day',
                        isCurrentMonth(date) ? 'current-month' : 'other-month',
                        isInRange(date) ? 'in-range' : '',
                        isStartOrEndDate(date) ? 'selected-day' : ''
                    ]"
                >
                    {{ date.getDate() }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    initialStartDate: {
        type: Date,
        default: null
    },
    initialEndDate: {
        type: Date,
        default: null
    }
});

const emit = defineEmits(['change']);

// State
const startDate = ref(props.initialStartDate);
const endDate = ref(props.initialEndDate);
const currentMonth = ref(props.initialStartDate || new Date());
const isCalendarOpen = ref(false);
const selectingStartDate = ref(true);
const calendarRef = ref(null);

// Format date for display
const formatDate = (date) => {
    if (!date) return "";
    const options = { year: "numeric", month: "short", day: "numeric" };
    return date.toLocaleDateString("en-US", options);
};

// Handle date selection
const handleDateSelect = (date) => {
    if (selectingStartDate.value) {
        startDate.value = date;
        endDate.value = null;
        selectingStartDate.value = false;
    } else {
        // Ensure end date is not before start date
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

// Calendar navigation
const prevMonth = () => {
    currentMonth.value = new Date(
        currentMonth.value.getFullYear(),
        currentMonth.value.getMonth() - 1,
        1
    );
};

const nextMonth = () => {
    currentMonth.value = new Date(
        currentMonth.value.getFullYear(),
        currentMonth.value.getMonth() + 1,
        1
    );
};

// Open calendar functions
const openCalendarForStart = () => {
    isCalendarOpen.value = true;
    selectingStartDate.value = true;
    if (startDate.value) {
        currentMonth.value = new Date(startDate.value);
    }
};

const openCalendarForEnd = () => {
    isCalendarOpen.value = true;
    selectingStartDate.value = false;
    if (endDate.value) {
        currentMonth.value = new Date(endDate.value);
    } else if (startDate.value) {
        currentMonth.value = new Date(startDate.value);
    }
};

// Generate calendar days
const calendarDays = computed(() => {
    const year = currentMonth.value.getFullYear();
    const month = currentMonth.value.getMonth();

    // Get first day of month and last day of month
    const firstDayOfMonth = new Date(year, month, 1);   
    const lastDayOfMonth = new Date(year, month + 1, 0);

    // Get day of week of first day (0 = Sunday, 1 = Monday, etc.)
    // Convert to Monday-based index (0 = Monday, 6 = Sunday)
    let firstDayIndex = firstDayOfMonth.getDay() - 1;
    if (firstDayIndex < 0) firstDayIndex = 6; // Sunday becomes 6

    const daysInMonth = lastDayOfMonth.getDate();

    // Calculate days from previous month to display
    const prevMonthDays = [];
    const prevMonth = new Date(year, month, 0);
    const prevMonthDaysCount = prevMonth.getDate();

    for (let i = firstDayIndex - 1; i >= 0; i--) {
        prevMonthDays.push(new Date(year, month - 1, prevMonthDaysCount - i));
    }

    // Current month days
    const currentMonthDays = [];
    for (let i = 1; i <= daysInMonth; i++) {
        currentMonthDays.push(new Date(year, month, i));
    }

    // Next month days to fill the calendar
    const nextMonthDays = [];
    const totalDaysDisplayed = 42; // 6 rows of 7 days
    const remainingDays = totalDaysDisplayed - prevMonthDays.length - currentMonthDays.length;

    for (let i = 1; i <= remainingDays; i++) {
        nextMonthDays.push(new Date(year, month + 1, i));
    }

    return [...prevMonthDays, ...currentMonthDays, ...nextMonthDays];
});

// Helper functions
const isInRange = (date) => {
    if (!startDate.value || !endDate.value) return false;
    return date > startDate.value && date < endDate.value;
};

const isStartOrEndDate = (date) => {
    if (!startDate.value && !endDate.value) return false;
    return (
        (startDate.value &&
            date.getDate() === startDate.value.getDate() &&
            date.getMonth() === startDate.value.getMonth() &&
            date.getFullYear() === startDate.value.getFullYear()) ||
        (endDate.value &&
            date.getDate() === endDate.value.getDate() &&
            date.getMonth() === endDate.value.getMonth() &&
            date.getFullYear() === endDate.value.getFullYear())
    );
};

const isCurrentMonth = (date) => {
    return date.getMonth() === currentMonth.value.getMonth();
};

const getMonthName = () => {
    const options = { month: "long", year: "numeric" };
    return currentMonth.value.toLocaleDateString("en-US", options);
};

// Close calendar when clicking outside
const handleClickOutside = (event) => {
    if (calendarRef.value && !calendarRef.value.contains(event.target)) {
        isCalendarOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener("mousedown", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("mousedown", handleClickOutside);
});

// Emit change event when dates change
watch([startDate, endDate], () => {
    emit('change', startDate.value, endDate.value);
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
    margin-bottom: 4px;
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
    height: 14px;
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

.calendar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.nav-button {
    display: flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    color: #6b7280;
}

.nav-icon {
    width: 16px;
    height: 16px;
}

.month-title {
    font-size: 16px;
    font-weight: 600;
    text-align: center;
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
@media (max-width: 640px) {
    .date-inputs {
        flex-direction: column;
    }

    .calendar-day {
        height: 28px;
        width: 28px;
        font-size: 12px;
    }
}
</style>
