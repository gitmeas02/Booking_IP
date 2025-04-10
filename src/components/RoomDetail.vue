<template>
    <div class="hotel-card">
        <!-- Hotel Header - Name and Rating -->
        <div class="hotel-header">
            <div class="hotel-name-container">
                <h1 class="hotel-name">KHUN HOTEL</h1>
                <div class="stars-container">
                    <span class="star-icon" v-for="n in 5" :key="n">★</span>
                </div>
            </div>

            <div class="review-container">
                <div class="review-score-container">
                    <span class="review-text">Review Score</span>
                    <div class="score-badge">4.5</div>
                </div>
                <a href="#" class="comments-link">Comments (123)</a>
            </div>
        </div>

        <!-- Location info -->
        <div class="location-container">
            <a href="#" class="location-link">Siem Reap</a>
            <a href="#" class="map-link">
                <span class="map-icon">📍</span> Show On Map
            </a>
            <span class="distance-text">1.7 km from center</span>
        </div>

        <!-- Price -->
        <div class="price-container">
            <div class="price-text">US$ 100</div>
        </div>

        <!-- Room info -->
        <div class="room-info">
            <ul class="room-list">
                <li class="room-list-item">Size 40m²</li>
                <li class="room-list-item">Beds: 1 beds</li>
            </ul>
        </div>

        <!-- Amenities -->
        <div class="amenities-container">
            <div class="amenities-list">
                <div class="amenity-item" v-for="(amenity, index) in amenities" :key="index">
                    <Icon :icon="amenity.icon" class="amenity-icon" />
                    <span class="amenity-text">{{ amenity.name }}</span>
                </div>
            </div>
        </div>

        <!-- Action button -->
        <div class="action-container">
            <button class="view-button" @click="viewHotel">View Hotel</button>
        </div>
    </div>
</template>

<script>
import { Icon } from '@iconify/vue';
export default {
    name: 'HotelCard',
    data() {
        return {
            amenities: [
                { name: 'Hot water', icon: 'shower-outline-rounded' },
                { name: 'Air Condition', icon: 'material-symbols:air' },
                { name: 'Wi-fi', icon: 'material-symbols:wifi' },
                { name: 'TV', icon: 'material-symbols:wifi' },
                { name: 'Free Food', icon: 'material-symbols:fastfood-outline-rounded' },
                { name: 'Phone Call', icon: 'material-symbols:deskphone-outline' }
            ]
        }
    },
    methods: {
        viewHotel() {
            console.log('View hotel details');
            // Add navigation logic here
        }
    }
}
</script>

<style>
.hotel-card {
    width: fit-content;
    margin: 0;
    padding: 5px;
}

.hotel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-left: 17px;
    padding-right: 17px;
    padding-top: 5px;
    padding-bottom: 0px;
}

.hotel-name-container {
    display: flex;
    flex-direction: column;
}

.hotel-name {
    font-size: 16px;
    margin: 0;
}

.stars-container {
    display: flex;
    margin-top: 4px;
}

.star-icon {
    font-size: 20px;
    color: #facc15;
    margin-right: 2px;
}

.review-container {
    text-align: right;
}

.review-score-container {
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: flex-end;
}

.review-text {
    font-size: 14px;
}

.score-badge {
    background-color: #1e3a8a;
    color: white;
    font-size: 14px;
    width: 24px;
    height: 23px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 3px;
}

.comments-link {
    color: #3b82f6;
    font-size: 10px;
    text-decoration: none;
}

.comments-link:hover {
    text-decoration: underline;
}

.location-container {
    display: flex;
    gap: 27px;
    padding: 0px 21px;
}

.location-link {
    color: #3b82f6;
    font-size: 14px;
    display: flex;
    align-items: center;
    margin-right: 32px;
    text-decoration: none;
}

.location-link:hover {
    text-decoration: underline;
}

.map-link {
    color: #3b82f6;
    font-size: 14px;
    display: flex;
    align-items: center;
    margin: 0 32px;
    text-decoration: none;
}

.map-link:hover {
    text-decoration: underline;
}

.map-icon {
    display: inline;
    margin-right: 8px;
}

.distance-text {
    color: #3b82f6;
    font-size: 14px;
    display: flex;
    align-items: center;
    margin-left: 32px;
}

.price-container {
    padding: 7px 20px;
}

.price-text {
    font-size: 16px;
    margin: 0;
}

.room-info {
    padding: 16px 0;
}

.room-list {
    list-style-type: disc;
    padding-left: 32px;
}

.room-list-item {
    font-size: 16px;
}

.amenities-container {
    padding: 16px 0;
}

.amenities-list {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
}

.amenity-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.amenity-icon {
    font-size: 24px;
}

.amenity-text {
    font-size: 10px;
    color: #1f2937;
}

.action-container {
    margin-top: 24px;
    display: flex;
    justify-content: flex-end;
}

.view-button {
    background-color: #2563eb;
    color: white;
    font-size: 20px;
    padding: 24px 32px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.view-button:hover {
    background-color: #1d4ed8;
}

/* Media query for responsive design */
@media (max-width: 768px) {

    .hotel-header,
    .location-container {
        flex-direction: column;
        align-items: flex-start;
    }

    .review-container {
        margin-top: 16px;
        text-align: left;
    }

    .review-score-container {
        justify-content: flex-start;
    }

    .location-link,
    .map-link,
    .distance-text {
        margin: 8px 0;
    }

    .amenities-list {
        gap: 16px;
    }
}
</style>