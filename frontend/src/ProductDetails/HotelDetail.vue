<template>
    <div class="hotel-booking-app">
      <!-- Hotel Header Section -->
      <div class="hotel-header">
        <div class="hotel-title-section">
          <h1 class="hotel-name">KHUN HOTEL</h1>
          <div class="hotel-location">
            <p>Location: No 14-16, Street 258, Sangkat Chak Tomuk, Daun Penh, Phnom Penh, Cambodia</p>
          </div>
        </div>
        <div class="message-box">
          <h2>MESSAGE</h2>
        </div>
      </div>
  
      <!-- Main Content -->
      <div class="main-content">
        <!-- Hotel Information -->
        <div class="hotel-info">
          <div class="hotel-description">
            <p>Located in Phnom Penh, Khun Hotel offers accommodation 5 minutes away from Central Market. It features a rooftop terrace with views of the city. Free WiFi is available in all rooms.</p>
            <p>It is conveniently 1476 feet to Chaktomouk Conference Hall, while Tuol Sleh is 1.7 mi away. Wat Phnom is 0.6 mi from the property. The nearest airport, Phnom Penh International Airport is 7.2 mi away. Airport transfers and shuttle services are available at additional charges.</p>
            <p>Air-conditioned rooms are fitted with flat-screen/LED television that open up to views and allow plenty of natural light. Rooms are equipped with a fan, seating area and private bathroom with shower facilities and free toiletries. Some rooms have a balcony that approaches the 24-hour front desk for currency exchange, tour arrangements, ticketing and concierge services.</p>
            <p>A business center is also available. The in-house restaurant serves up a variety of Khmer and Western dishes daily. In-room dining options are possible.</p>
            <p>Couples in particular like the location – they rated it 9.2 for a two-person trip.</p>
          </div>
          
          <button class="show-map-btn">Show Map</button>
          
          <!-- Facilities Section -->
          <div class="facilities-section">
            <h3>Most popular facilities</h3>
            <div class="facilities-grid">
              <div class="facility"><span class="icon">🚿</span> Hot water</div>
              <div class="facility"><span class="icon">❄️</span> Air Condition</div>
              <div class="facility"><span class="icon">📶</span> Wi-fi</div>
              <div class="facility"><span class="icon">🅿️</span> Free Parking</div>
              <div class="facility"><span class="icon">📞</span> Phone Call</div>
              <div class="facility"><span class="icon">🚿</span> Hot water</div>
              <div class="facility"><span class="icon">❄️</span> Air Condition</div>
              <div class="facility"><span class="icon">📶</span> Wi-fi</div>
              <div class="facility"><span class="icon">🅿️</span> Free Parking</div>
              <div class="facility"><span class="icon">📞</span> Phone Call</div>
            </div>
          </div>
          
          <!-- Available Property -->
          <div class="available-property">
            <h3>AVAILABLE PROPERTY</h3>
            <button class="view-more-btn">View More Listing</button>
            
            <!-- Room Listings -->
            <div class="room-listings">
              <div v-for="(room, index) in rooms" :key="index" class="room-card">
                <div class="room-image">
                  <img :src="room.image" :alt="room.title">
                </div>
                <div class="room-details">
                  <h4>${{ room.price }} night</h4>
                  <p>{{ room.bedType }}</p>
                  <p>{{ room.location }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Booking Section -->
        <div class="booking-section">
          <div class="hotel-thumbnail">
            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-AqPp8DnkDdr9dVfJNYO62Tbm73ttSy.png" alt="Khun Hotel" class="thumbnail-img">
            <h3>KHUN HOTEL</h3>
            <p>Phnom Penh</p>
          </div>
          
          <div class="booking-form">
            <h3>Where you'll sleep</h3>
            
            <div class="date-selection">
              <div class="check-in">
                <label>check in</label>
                <input type="date" v-model="checkInDate">
              </div>
              <div class="check-out">
                <label>check out</label>
                <input type="date" v-model="checkOutDate">
              </div>
            </div>
            
            <div class="price-details">
              <div class="price-per-night">
                <span>${{ pricePerNight }}</span>
                <span>x {{ numberOfNights }} nights</span>
                <span>${{ totalRoomPrice }}</span>
              </div>
              <div class="booking-fee">
                <span>Booking Fee</span>
                <span>${{ bookingFee }}</span>
              </div>
              <div class="total-price">
                <span>Total</span>
                <span>${{ totalPrice }}</span>
              </div>
            </div>
            
            <button class="reserve-btn" @click="reserveRoom">Reserve</button>
            <p class="tax-note">Tax rates to changed yet</p>
          </div>
          
          <!-- Map Section -->
          <div class="map-section">
            <div class="map-container">
              <img src="https://plus.unsplash.com/premium_photo-1682310071124-33632135b2ee?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTN8fG1hcHxlbnwwfHwwfHx8MA%3D%3D" alt="Map" class="map-image">
              <div class="map-marker">📍</div>
            </div>
            <div class="direction-location">
              <h3>Direction Location</h3>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
<script setup>
import { ref, computed } from 'vue';

const hotelName = ref('KHUN HOTEL');
const location = ref('No 14-16, Street 258, Sangkat Chak Tomuk, Daun Penh, Phnom Penh, Cambodia');
const pricePerNight = ref(42);
const checkInDate = ref(new Date().toISOString().substr(0, 10));
const checkOutDate = ref(new Date(Date.now() + 5 * 24 * 60 * 60 * 1000).toISOString().substr(0, 10));
const bookingFee = ref(33);
const rooms = ref([
    {
        price: 42,
        bedType: 'Beds King',
        location: 'KHUN, Phnom Penh',
        image: '/placeholder.svg?height=150&width=200'
    },
    {
        price: 42,
        bedType: 'Beds King',
        location: 'KHUN, Phnom Penh',
        image: '/placeholder.svg?height=150&width=200'
    },
    {
        price: 42,
        bedType: 'Beds King',
        location: 'KHUN, Phnom Penh',
        image: '/placeholder.svg?height=150&width=200'
    },
    {
        price: 42,
        bedType: 'Beds King',
        location: 'KHUN, Phnom Penh',
        image: '/placeholder.svg?height=150&width=200'
    },
    {
        price: 42,
        bedType: 'Beds King',
        location: 'KHUN, Phnom Penh',
        image: '/placeholder.svg?height=150&width=200'
    },
    {
        price: 42,
        bedType: 'Beds King',
        location: 'KHUN, Phnom Penh',
        image: '/placeholder.svg?height=150&width=200'
    }
]);

const numberOfNights = computed(() => {
    const checkIn = new Date(checkInDate.value);
    const checkOut = new Date(checkOutDate.value);
    const diffTime = Math.abs(checkOut - checkIn);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays || 1;
});

const totalRoomPrice = computed(() => {
    return pricePerNight.value * numberOfNights.value;
});

const totalPrice = computed(() => {
    return totalRoomPrice.value + bookingFee.value;
});

function reserveRoom() {
    alert(`Room reserved for ${numberOfNights.value} nights. Total: $${totalPrice.value}`);
}
</script>
  
  <style>
  .hotel-booking-app {
    font-family: Arial, sans-serif;
    max-width: 1200px;
    margin: 0 auto;
    color: #333;
  }
  
  .hotel-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
  }
  
  .hotel-name {
    font-size: 1.5rem;
    margin: 0;
    color: #333;
  }
  
  .hotel-location {
    font-size: 0.8rem;
    margin-top: 5px;
  }
  
  .message-box {
    background-color: #8B4513;
    color: white;
    padding: 10px 30px;
    border-radius: 5px;
  }
  
  .message-box h2 {
    margin: 0;
    font-size: 1rem;
  }
  
  .main-content {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
  }
  
  .hotel-info {
    flex: 2;
    min-width: 300px;
  }
  
  .hotel-description {
    font-size: 0.9rem;
    line-height: 1.5;
  }
  
  .show-map-btn {
    background-color: white;
    border: 1px solid #ccc;
    padding: 5px 15px;
    margin: 10px 0;
    cursor: pointer;
  }
  
  .facilities-section {
    margin: 20px 0;
  }
  
  .facilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 10px;
  }
  
  .facility {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
  }
  
  .icon {
    margin-right: 5px;
  }
  
  .available-property {
    margin: 20px 0;
  }
  
  .view-more-btn {
    background-color: white;
    border: 1px solid #ccc;
    padding: 5px 15px;
    margin: 10px 0;
    cursor: pointer;
  }
  
  .room-listings {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
  }
  
  .room-card {
    border: 1px solid #ddd;
    border-radius: 5px;
    overflow: hidden;
  }
  
  .room-image img {
    width: 100%;
    height: 150px;
    object-fit: cover;
  }
  
  .room-details {
    padding: 10px;
  }
  
  .room-details h4 {
    margin: 0;
    color: #333;
  }
  
  .booking-section {
    flex: 1;
    min-width: 300px;
  }
  
  .hotel-thumbnail {
    margin-bottom: 15px;
  }
  
  .thumbnail-img {
    width: 100%;
    max-height: 100px;
    object-fit: cover;
    border-radius: 5px;
  }
  
  .booking-form {
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 20px;
  }
  
  .date-selection {
    display: flex;
    justify-content: space-between;
    margin: 15px 0;
  }
  
  .check-in, .check-out {
    flex: 1;
  }
  
  .check-in label, .check-out label {
    display: block;
    font-size: 0.8rem;
    margin-bottom: 5px;
  }
  
  .check-in input, .check-out input {
    width: 90%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 3px;
  }
  
  .price-details {
    margin: 20px 0;
  }
  
  .price-per-night, .booking-fee, .total-price {
    display: flex;
    justify-content: space-between;
    margin: 10px 0;
  }
  
  .total-price {
    font-weight: bold;
    border-top: 1px solid #ddd;
    padding-top: 10px;
  }
  
  .reserve-btn {
    width: 100%;
    background-color: #8B4513;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
  }
  
  .tax-note {
    text-align: right;
    font-size: 0.8rem;
    color: #777;
    margin-top: 5px;
  }
  
  .map-section {
    margin-top: 20px;
  }
  
  .map-container {
    position: relative;
    height: 200px;
    border-radius: 5px;
    overflow: hidden;
  }
  
  .map-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .map-marker {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 2rem;
  }
  
  .direction-location {
    background-color: #8B4513;
    color: white;
    padding: 10px;
    text-align: center;
    border-radius: 0 0 5px 5px;
  }
  
  .direction-location h3 {
    margin: 0;
    font-size: 1rem;
  }
  
  @media (max-width: 768px) {
    .hotel-header {
      flex-direction: column;
    }
    
    .message-box {
      width: 100%;
      margin-top: 10px;
    }
    
    .date-selection {
      flex-direction: column;
    }
    
    .check-in, .check-out {
      margin-bottom: 10px;
    }
    
    .room-listings {
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
  }
  </style>