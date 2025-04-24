<template>
    <div class="booking-container">
        <div class="booking-header">
            <h2>List Booking <Icon icon="ep:arrow-right-bold"> </Icon></h2>
            <div class="tab-selector">
                <button class="tab-button active">Current <Icon icon="mingcute:arrow-right-fill"> </Icon> Future</button>
                <button class="tab-button history">History</button>
            </div>
        </div>

        <div class="booking-list">
            <div v-for="(booking, index) in bookings" :key="index" class="booking-item">
                <div class="hotel-details">
                    <img :src="booking.image" alt="Hotel Room" class="hotel-image">
                    <div class="hotel-info">
                        <h3>{{ booking.hotelName }}</h3>
                        <p>{{ booking.roomType }}</p>
                        <div class="rating">
                            <span>★ {{ booking.rating }}</span>
                            <span class="reviews">({{ booking.reviews }} Reviews)</span>
                        </div>
                    </div>
                </div>

                <div class="booking-details">
                    <div class="booking-header-right">
                        <div class="hotel-booking-name">At Kampot | <span class="booking-id">Booking ID: {{
                                booking.bookingId }}</span></div>
                        <div class="booking-date">
                            <span class="label">Booked at </span>
                            <span class="value">{{ booking.bookedDate }} {{ booking.bookedTime }}</span>
                        </div>
                    </div>

                    <div class="booking-dates">
                        <div class="check-date">
                            <span class="label">Check-in</span>
                            <span class="value">{{ booking.checkIn }}</span>
                            <span class="time">{{ booking.checkInTime }}</span>
                        </div>
                        <div class="check-date">
                            <span class="label">Check-out</span>
                            <span class="value">{{ booking.checkOut }}</span>
                            <span class="time">{{ booking.checkOutTime }}</span>
                        </div>
                    </div>

                    <div class="payment-info" :class="booking.paymentStatus">
                        {{ booking.paymentMethod }}
                        <span class="price">{{ booking.price }}</span>
                        <button v-if="booking.paymentStatus === 'cancel'" class="cancel-btn">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import roomImage from '@/assets/Bed/room.png';
import { Icon } from '@iconify/vue';

export default {
    components: {
        Icon
    },
    name: 'BookingList',
    data() {
        return {
            bookings: [
                {
                    hotelName: 'KHUN HOTEL',
                    roomType: 'King Bed',
                    rating: '4.0',
                    reviews: '52',
                    image: roomImage,
                    bookingId: 'KHN1234',
                    bookedDate: 'Fri, Mar 6, 2025',
                    bookedTime: '1:24 PM',
                    checkIn: 'Sat, Mar 8, 2025',
                    checkInTime: '2:00 PM - 12:00 AM',
                    checkOut: 'Sun, Mar 12, 2025',
                    checkOutTime: '5:00 AM - 12:30 PM',
                    paymentMethod: 'credit card',
                    price: '$123.35',
                    paymentStatus: 'paid'
                },
                {
                    hotelName: 'KHUN HOTEL',
                    roomType: 'King Bed',
                    rating: '4.0',
                    reviews: '52',
                    image: roomImage,
                    bookingId: 'KHN1234',
                    bookedDate: 'Fri, Mar 6, 2025',
                    bookedTime: '1:24 PM',
                    checkIn: 'Sat, Mar 8, 2025',
                    checkInTime: '2:00 PM - 12:00 AM',
                    checkOut: 'Sun, Mar 12, 2025',
                    checkOutTime: '5:00 AM - 12:30 PM',
                    paymentMethod: 'Paid at property',
                    price: '$129.35',
                    paymentStatus: 'paid'
                },
                {
                    hotelName: 'KHUN HOTEL',
                    roomType: 'King Bed',
                    rating: '4.0',
                    reviews: '52',
                    image: roomImage,
                    bookingId: 'KHN1234',
                    bookedDate: 'Fri, Mar 6, 2025',
                    bookedTime: '1:24 PM',
                    checkIn: 'Sat, Mar 8, 2025',
                    checkInTime: '2:00 PM - 12:00 AM',
                    checkOut: 'Sun, Mar 12, 2025',
                    checkOutTime: '5:00 AM - 12:30 PM',
                    paymentMethod: 'Cancel',
                    price: '$129.35',
                    paymentStatus: 'cancel'
                }
            ]
        }
    }
}
</script>

<style scoped>
.booking-container {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
}

.booking-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-direction: column;
    gap: 25px;
    margin-bottom: 25px;
}

.booking-header h2 {
    display: flex;
    align-items: center;
    font-size: 24px;
}

.tab-selector {
    display: flex;
    gap: 25px;
}

.tab-button {
    border-radius: 40px;
    border: 1px solid #ddd;
    background-color: #fff;
    cursor: pointer;
    font-size: 24px;
}

.tab-button.active {
    display: flex;
    align-items: center;
    padding: 8px 20px;
    background-color: #fff;
    border: 1px solid #333;
}

.tab-button.history {
    width: 172px;
    padding: 7px 0px;
    background-color: #aaa;
}

.booking-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.booking-item {
    display: flex;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    background-color: #fff;
}

.hotel-details {
    width: 35%;
    padding: 15px;
    border-right: 1px solid #eee;
    display: flex;
    flex-direction: column;
}

.hotel-image {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 4px;
}

.hotel-info {
    padding: 10px 0;
}

.hotel-info h3 {
    margin: 0;
    color: #333;
    font-size: 16px;
}

.hotel-info p {
    margin: 5px 0;
    color: #666;
}

.rating {
    color: #f8b400;
    font-size: 14px;
}

.reviews {
    color: #999;
    font-size: 12px;
}

.booking-details {
    flex: 1;
    padding: 15px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.booking-header-right {
    margin-bottom: 10px;
}

.hotel-booking-name {
    font-weight: bold;
    font-size: 14px;
}

.booking-id {
    color: #999;
    font-weight: normal;
}

.booking-date {
    margin-top: 5px;
    font-size: 13px;
}

.booking-dates {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 10px;
}

.check-date {
    display: flex;
    flex-direction: column;
}

.label {
    color: #999;
    font-size: 12px;
}

.value {
    font-weight: bold;
    font-size: 14px;
}

.time {
    color: #666;
    font-size: 13px;
}

.payment-info {
    align-self: flex-end;
    font-size: 14px;
}

.payment-info.paid {
    color: #28a745;
}

.payment-info.cancel {
    color: #dc3545;
}

.price {
    font-weight: bold;
    margin-left: 5px;
}

.cancel-btn {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
    padding: 0;
    font-size: 14px;
    font-weight: bold;
}
</style>