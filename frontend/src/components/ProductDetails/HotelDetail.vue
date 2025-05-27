
  
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
 
  </style>