<template>
    <div class="occupancy-selector">
        <div>
            <div class="summary" @click="toggleDropdown">
                <span class="count">{{ rooms }} room</span>
                <span class="count">{{ adults }} adults</span>
                <span class="count">{{ children }} children</span>
                <span class="dropdown-icon">{{ isOpen ? "▲" : "▼" }}</span>
            </div>
            <div>
                Traveling with pet?
                <el-switch v-model="value1" />
            </div>
            
        </div>
        

        <div v-if="isOpen" class="dropdown">
            <div class="option">
                <div class="label-wrapper">
                    <div class="label">Room</div>
                </div>
                <div class="counter">
                    <button @click="decrement('rooms')" :disabled="rooms <= 1">-</button>
                    <span class="value">{{ rooms }}</span>
                    <button @click="increment('rooms')">+</button>
                </div>
            </div>

            <div class="option">
                <div class="label-wrapper">
                    <div class="label">Adults</div>
                    <div class="note">Ages 18 or above</div>
                </div>
                <div class="counter">
                    <button @click="decrement('adults')" :disabled="adults <= 1">-</button>
                    <span class="value">{{ adults }}</span>
                    <button @click="increment('adults')">+</button>
                </div>
            </div>

            <div class="option">
                <div class="label-wrapper">
                    <div class="label">Children</div>
                    <div class="note">Ages 0-17</div>
                </div>
                <div class="counter">
                    <button @click="decrement('children')" :disabled="children <= 0">-</button>
                    <span class="value">{{ children }}</span>
                    <button @click="increment('children')">+</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";

const isOpen = ref(false);
const rooms = ref(1);
const adults = ref(2);
const children = ref(0);
const value1 = ref(true)

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const increment = (type) => {
    if (type === "rooms") rooms.value++;
    if (type === "adults") adults.value++;
    if (type === "children") children.value++;
};

const decrement = (type) => {
    if (type === "rooms" && rooms.value > 1) rooms.value--;
    if (type === "adults" && adults.value > 1) adults.value--;
    if (type === "children" && children.value > 0) children.value--;
};

const handleClickOutside = (event) => {
    if (!event.target.closest(".occupancy-selector")) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<style scoped>
.occupancy-selector {
    position: relative;
    width: 20rem;
    font-family: Arial, sans-serif;
}

.summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: pointer;
}

.dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 4px;
    background: white;
    z-index: 10;
    margin-top: 5px;
    padding: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.option {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.option:last-child {
    margin-bottom: 0;
}

.label {
    font-weight: bold;
    color: black;
}

.note {
    font-size: 0.8em;
    color: #666;
    margin-top: 5px;
}

.counter {
    display: flex;
    align-items: center;
    flex-shrink: 0;
}

.counter button {
    width: 25px;
    height: 25px;
    background: #f0f0f0;
    border: 1px solid #ccc;
    border-radius: 3px;
    cursor: pointer;
}

.counter button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.counter .value {
    margin: 0 10px;
    min-width: 20px;
    text-align: center;
    color: black;
}

.dropdown-icon {
    margin-left: 10px;
}

.label-wrapper {
    display: flex;
    flex-direction: column;
    flex: 1;
}
</style>
