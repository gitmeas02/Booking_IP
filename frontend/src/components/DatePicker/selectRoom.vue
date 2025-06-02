<template>
    <div class="booking-dropdown-container">
      <!-- Dropdown Trigger Button -->
      <button 
        class="dropdown-trigger" 
        @click="toggleDropdown"
        aria-haspopup="true"
        :aria-expanded="isOpen"
      >
        <div class="trigger-content">
          <div class="icon-guests">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <div class="summary-text">
            {{ adults }} {{ adults === 1 ? 'adult' : 'adults' }},
            {{ children }} {{ children === 1 ? 'child' : 'children' }}
            <div class="room-count">{{ rooms }} {{ rooms === 1 ? 'room' : 'rooms' }}</div>
          </div>
        </div>
        <div class="dropdown-arrow">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down"><path d="m6 9 6 6 6-6"/></svg>
        </div>
      </button>
  
      <!-- Dropdown Menu -->
      <div 
        v-if="isOpen" 
        class="dropdown-menu"
        ref="dropdownMenu"
        @keydown.esc="closeDropdown"
        @keydown.up.prevent="navigateOptions('up')"
        @keydown.down.prevent="navigateOptions('down')"
        @keydown.enter.prevent="selectFocusedOption"
        tabindex="-1"
      >
        <!-- Room Selection -->
        <div class="option-group">
          <div class="option-label">Room</div>
          <div class="counter-controls">
            <button 
              class="counter-btn decrement" 
              @click="updateRooms(-1)"
              :disabled="rooms <= 1"
              aria-label="Decrease room count"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus"><path d="M5 12h14"/></svg>
            </button>
            <span class="counter-value">{{ rooms }}</span>
            <button 
              class="counter-btn increment" 
              @click="updateRooms(1)"
              aria-label="Increase room count"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            </button>
          </div>
        </div>
  
        <!-- Adults Selection -->
        <div class="option-group">
          <div class="option-label">
            Adults
            <div class="age-hint">Ages 18 or above</div>
          </div>
          <div class="counter-controls">
            <button 
              class="counter-btn decrement" 
              @click="updateAdults(-1)"
              :disabled="adults <= 1"
              aria-label="Decrease adult count"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus"><path d="M5 12h14"/></svg>
            </button>
            <span class="counter-value">{{ adults }}</span>
            <button 
              class="counter-btn increment" 
              @click="updateAdults(1)"
              aria-label="Increase adult count"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            </button>
          </div>
        </div>
  
        <!-- Children Selection -->
        <div class="option-group">
          <div class="option-label">
            Children
            <div class="age-hint">Ages 0-17</div>
          </div>
          <div class="counter-controls">
            <button 
              class="counter-btn decrement" 
              @click="updateChildren(-1)"
              :disabled="children <= 0"
              aria-label="Decrease children count"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus"><path d="M5 12h14"/></svg>
            </button>
            <span class="counter-value">{{ children }}</span>
            <button 
              class="counter-btn increment" 
              @click="updateChildren(1)"
              aria-label="Increase children count"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            </button>
          </div>
        </div>
  
        <!-- Child Age Selectors (shown only when children > 0) -->
        <div v-if="children > 0" class="child-age-section">
          <div class="pricing-note">
            For accurate room pricing, make sure to enter your children's correct ages.
          </div>
          
          <div 
            v-for="index in children" 
            :key="index" 
            class="child-age-selector"
          >
            <div class="age-dropdown">
              <button 
                class="age-dropdown-trigger"
                @click="toggleAgeDropdown(index)"
                :aria-expanded="openAgeDropdown === index"
              >
                {{ childAges[index - 1] ? `${childAges[index - 1]} years` : `Age of Child ${index}` }}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down"><path d="m6 9 6 6 6-6"/></svg>
              </button>
              
              <div v-if="openAgeDropdown === index" class="age-options">
                <div 
                  v-for="age in 17" 
                  :key="age" 
                  class="age-option"
                  :class="{ 'selected': childAges[index - 1] === age }"
                  @click="selectChildAge(index - 1, age)"
                >
                  {{ age }} {{ age === 1 ? 'year' : 'years' }}
                </div>
                <div 
                  class="age-option"
                  :class="{ 'selected': childAges[index - 1] === 0 }"
                  @click="selectChildAge(index - 1, 0)"
                >
                  Under 1 year
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'RoomBookingDropdown',
    data() {
      return {
        isOpen: false,
        rooms: 1,
        adults: 2,
        children: 2,
        childAges: [null, null],
        openAgeDropdown: null,
        focusedOptionIndex: -1
      }
    },
    mounted() {
      document.addEventListener('click', this.handleOutsideClick)
      // Initialize with some default child ages
      this.childAges = Array(this.children).fill(null)
    },
    beforeUnmount() {
      document.removeEventListener('click', this.handleOutsideClick)
    },
    methods: {
      toggleDropdown() {
        this.isOpen = !this.isOpen
        if (this.isOpen) {
          this.$nextTick(() => {
            this.$refs.dropdownMenu.focus()
          })
        }
      },
      closeDropdown() {
        this.isOpen = false
        this.openAgeDropdown = null
      },
      handleOutsideClick(event) {
        if (this.$el && !this.$el.contains(event.target)) {
          this.closeDropdown()
        }
      },
      updateRooms(change) {
        const newValue = this.rooms + change
        if (newValue >= 1) {
          this.rooms = newValue
        }
      },
      updateAdults(change) {
        const newValue = this.adults + change
        if (newValue >= 1) {
          this.adults = newValue
        }
      },
      updateChildren(change) {
        const newValue = this.children + change
        if (newValue >= 0) {
          this.children = newValue
          // Adjust childAges array size
          if (change > 0) {
            // Add new null entries for new children
            this.childAges = [...this.childAges, ...Array(change).fill(null)]
          } else if (change < 0) {
            // Remove entries for removed children
            this.childAges = this.childAges.slice(0, this.children)
          }
        }
      },
      toggleAgeDropdown(index) {
        if (this.openAgeDropdown === index) {
          this.openAgeDropdown = null
        } else {
          this.openAgeDropdown = index
        }
      },
      selectChildAge(childIndex, age) {
        this.childAges[childIndex] = age
        this.openAgeDropdown = null
      },
      navigateOptions(direction) {
        // Implementation for keyboard navigation
        const optionElements = this.$refs.dropdownMenu.querySelectorAll('.option-group, .child-age-selector')
        const optionsCount = optionElements.length
        
        if (direction === 'up') {
          this.focusedOptionIndex = this.focusedOptionIndex <= 0 ? optionsCount - 1 : this.focusedOptionIndex - 1
        } else {
          this.focusedOptionIndex = this.focusedOptionIndex >= optionsCount - 1 ? 0 : this.focusedOptionIndex + 1
        }
        
        if (optionElements[this.focusedOptionIndex]) {
          optionElements[this.focusedOptionIndex].focus()
        }
      },
      selectFocusedOption() {
        // Implementation for selecting the focused option with Enter key
        const optionElements = this.$refs.dropdownMenu.querySelectorAll('.option-group, .child-age-selector')
        if (this.focusedOptionIndex >= 0 && optionElements[this.focusedOptionIndex]) {
          optionElements[this.focusedOptionIndex].click()
        }
      }
    }
  }
  </script>
  
  <style scoped>
  .booking-dropdown-container {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    /* width: 100%; */
    /* max-width: 500px; */
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  }
  
  .dropdown-trigger {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 4.188rem;
    width: 21em;
    padding: 12px 16px;
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    text-align: left;
    font-size: 16px;
    color: #333;
    transition: border-color 0.2s;
  }
  
  .dropdown-trigger:hover, .dropdown-trigger:focus {
    border-color: #bdbdbd;
    outline: none;
  }
  
  .trigger-content {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  
  .icon-guests {
    color: #666;
  }
  
  .summary-text {
    font-weight: 500;
  }
  
  .room-count {
    font-size: 14px;
    color: #666;
    margin-top: 2px;
  }
  
  .dropdown-arrow {
    color: #666;
  }
  
  .dropdown-menu {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    width: 100%;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 100;
    padding: 16px;
    outline: none;
  }
  
  .option-group {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
  }
  
  .option-group:last-of-type {
    border-bottom: none;
  }
  
  .option-label {
    font-weight: 500;
    color: #333;
  }
  
  .age-hint {
    font-size: 12px;
    color: #666;
    font-weight: normal;
    margin-top: 2px;
  }
  
  .counter-controls {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  
  .counter-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid #e0e0e0;
    background-color: white;
    color: #333;
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .counter-btn:hover:not(:disabled) {
    background-color: #f5f5f5;
  }
  
  .counter-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  .counter-value {
    font-weight: 500;
    color:black;
    min-width: 24px;
    text-align: center;
  }
  
  .child-age-section {
    margin-top: 16px;
    border-top: 1px solid #f0f0f0;
    padding-top: 16px;
  }
  
  .pricing-note {
    font-size: 14px;
    color: #333;
    margin-bottom: 16px;
  }
  
  .child-age-selector {
    margin-bottom: 12px;
  }
  
  .age-dropdown {
    position: relative;
  }
  
  .age-dropdown-trigger {
    width: 100%;
    padding: 12px 16px;
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    text-align: left;
    font-size: 14px;
    color: #333;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .age-dropdown-trigger:hover {
    border-color: #bdbdbd;
  }
  
  .age-options {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 101;
  }
  
  .age-option {
    padding: 10px 16px;
    cursor: pointer;
    transition: background-color 0.2s;
    background-color: white;
    color: rgb(0, 0, 0);
  }
  
  .age-option:hover, .age-option.selected {
    background-color: #000000;
    color: rgb(255, 255, 255);
    border-color: gray;
  }
  
  /* Responsive styles */
  @media (max-width: 480px) {
  .dropdown-trigger {
    width: 100%;
    height: auto;
    flex-direction: column;
    align-items: flex-start;
    padding: 10px;
    font-size: 14px;
  }

  .trigger-content {
    flex-direction: column;
    align-items: flex-start;
    gap: 4px;
  }

  .dropdown-menu {
    position: fixed;
    bottom: 0;
    top: auto;
    left: 0;
    right: 0;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    border-radius: 16px 16px 0 0;
    padding: 12px;
  }

  .option-group {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }

  .counter-controls {
    justify-content: space-between;
    width: 100%;
  }

  .age-dropdown-trigger {
    padding: 10px;
    font-size: 14px;
  }

  .age-options {
    position: fixed;
    bottom: 0;
    top: auto;
    left: 0;
    right: 0;
    max-height: 50vh;
    overflow-y: auto;
    border-radius: 16px 16px 0 0;
  }

  .child-age-selector {
    margin-bottom: 16px;
  }

  .pricing-note {
    font-size: 13px;
    line-height: 1.4;
  }
}

  </style>