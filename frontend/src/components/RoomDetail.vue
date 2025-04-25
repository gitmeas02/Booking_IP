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
            <a href="#" class="map-link">Show On Map</a>
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
    components:{
        Icon
    },
    data() {
        return {
            amenities: [
                { icon: 'material-symbols:shower-outline-rounded', name: 'Hot water' },
                { icon: 'material-symbols:air', name: 'Air Condition' },
                { icon: 'material-symbols:wifi', name: 'Wi-fi' },
                { icon: 'material-symbols:wifi', name: 'TV' },
                { icon: 'material-symbols:fastfood-outline-rounded', name: 'Free Food' },
                { icon: 'material-symbols:deskphone-outline', name: 'Phone Call'  }
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

<style scoped>
.hotel-card {
    width: 472px;
    height: fit-content;
    outline: 1px solid #dddede;
    border-radius: 4px;
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
}

.star-icon {
    display: inline-block;
    font-size: 14px;
    color: #facc15;
    line-height: 1;
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
    display: flex;
    height: fit-content;
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
    text-decoration: none;
}

.map-link:hover {
    text-decoration: underline;
}

.distance-text {
    color: #3b82f6;
    font-size: 14px;
    display: flex;
    align-items: center;
}

.price-container {
    padding: 7px 20px;
}

.price-text {
    display: flex;
    height: fit-content;
    font-size: 12px;
    margin: 0;
}

.room-info {
    padding: 3px 12px;
}

.room-list {
    list-style-type: disc;
    padding-left: 32px;
}

.room-list-item {
    font-size: 12px;
}

.amenities-container {
    padding: 0;
}

.amenities-list {
    display: flex;
    flex-wrap: wrap;
    gap: 12px 7px;
    padding: 0 21px;
}

.amenity-item {
    display: flex;
    align-items: center;
    height: fit-content;
    gap: 8px;
}

.amenity-icon {
    font-size: 20px;
}

.amenity-text {
    display: flex;
    font-size: 10px;
    color: #1f2937;
}

.action-container {
    padding: 4px 16px;
    display: flex;
    justify-content: flex-end;
}

.view-button {
    background-color: #2563eb;
    color: white;
    font-size: 13px;
    width: 88px;
    height: 23px;
    border: none;
    border-radius: 2px;
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