<template>
    <div class="booking-dropdown-container">
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
            {{ localAdults }} {{ localAdults === 1 ? 'adult' : 'adults' }},
            {{ localChildren }} {{ localChildren === 1 ? 'child' : 'children' }}
            <div class="room-count">{{ localRooms }} {{ localRooms === 1 ? 'room' : 'rooms' }}</div>
          </div>
        </div>
        <div class="dropdown-arrow">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down"><path d="m6 9 6 6 6-6"/></svg>
        </div>
      </button>
  
      <div 
        v-if="isOpen" 
        class="dropdown-menu"
        ref="dropdownMenu"
        @keydown.esc="closeDropdown"
        tabindex="-1"
      >
        <div class="option-group">
          <div class="option-label">Room</div>
          <div class="counter-controls">
            <button 
              class="counter-btn decrement" 
              @click="updateRooms(-1)"
              :disabled="localRooms <= 1"
              aria-label="Decrease room count"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus"><path d="M5 12h14"/></svg>
            </button>
            <span class="counter-value">{{ localRooms }}</span>
            <button 
              class="counter-btn increment" 
              @click="updateRooms(1)"
              aria-label="Increase room count"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            </button>
          </div>
        </div>
  
        <div class="option-group">
          <div class="option-label">
            Adults
            <div class="age-hint">Ages 18 or above</div>
          </div>
          <div class="counter-controls">
            <button 
              class="counter-btn decrement" 
              @click="updateAdults(-1)"
              :disabled="localAdults <= 1"
              aria-label="Decrease adult count"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus"><path d="M5 12h14"/></svg>
            </button>
            <span class="counter-value">{{ localAdults }}</span>
            <button 
              class="counter-btn increment" 
              @click="updateAdults(1)"
              aria-label="Increase adult count"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            </button>
          </div>
        </div>
  
        <div class="option-group">
          <div class="option-label">
            Children
            <div class="age-hint">Ages 0-17</div>
          </div>
          <div class="counter-controls">
            <button 
              class="counter-btn decrement"
              @click="updateChildren(-1)"
              :disabled="localChildren <= 0"
              aria-label="Decrease children count"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus"><path d="M5 12h14"/></svg>
            </button>
            <span class="counter-value">{{ localChildren }}</span>
            <button 
              class="counter-btn increment"
              @click="updateChildren(1)"
              aria-label="Increase children count"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            </button>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
export default {
  name: 'RoomBookingDropdown',
  props: {
    rooms: { type: Number, default: 1 },
    adults: { type: Number, default: 2 },
    children: { type: Number, default: 0 }
  },
  emits: ['change'],
  data() {
    return {
      isOpen: false,
      localRooms: this.rooms,
      localAdults: this.adults,
      localChildren: this.children,
    }
  },
  watch: {
    rooms(val) { this.localRooms = val; },
    adults(val) { this.localAdults = val; },
    children(val) { this.localChildren = val; }
  },
  methods: {
    toggleDropdown() {
      this.isOpen = !this.isOpen
      if (this.isOpen) {
        this.$nextTick(() => {
          this.$refs.dropdownMenu && this.$refs.dropdownMenu.focus()
        })
      }
    },
    closeDropdown() {
      this.isOpen = false
    },
    handleOutsideClick(event) {
      if (this.$el && !this.$el.contains(event.target)) {
        this.closeDropdown()
      }
    },
    updateRooms(change) {
      const newValue = this.localRooms + change
      if (newValue >= 1) {
        this.localRooms = newValue
        this.emitChange()
      }
    },
    updateAdults(change) {
      const newValue = this.localAdults + change
      if (newValue >= 1) {
        this.localAdults = newValue
        this.emitChange()
      }
    },
    updateChildren(change) {
      const newValue = this.localChildren + change
      if (newValue >= 0) {
        this.localChildren = newValue
        this.emitChange()
      }
    },
    emitChange() {
      this.$emit('change', {
        rooms: this.localRooms,
        adults: this.localAdults,
        children: this.localChildren,
      });
    }
  },
  mounted() {
    document.addEventListener('click', this.handleOutsideClick)
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleOutsideClick)
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