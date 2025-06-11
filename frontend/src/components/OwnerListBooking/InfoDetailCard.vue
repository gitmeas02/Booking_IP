<template>
    <div class="border px-8 py-5 w-147 rounded-xl">
        <div class="text-base">
            <div class="font-bold">Info details</div>
            <div>Name: <span class="font-bold">{{ booking.name }}</span></div>
            <div>Booking id <span class="font-bold">{{ booking.bookingId }}</span></div>
            <div>Booking at <span class="font-bold">{{ booking.bookingAt }}</span></div>
        </div>

        <div class="flex flex-col border-t border-b mt-4 py-2 gap-1">
            <div class="font-semibold">Price Details</div>

            <div class="flex justify-between">
                <span>Original Price ({{ booking.priceDetails.original.nights }} night)</span>
                <span class="font-bold">${{ booking.priceDetails.original.amount }}</span>
            </div>

            <div class="flex justify-between">
                <span>Stay Price ({{ booking.priceDetails.stay.nights }} night)</span>
                <span class="font-bold">${{ booking.priceDetails.stay.amount }}</span>
            </div>

            <div class="flex justify-between">
                <span>Booking Fee</span>
                <span class="font-bold">{{ booking.priceDetails.bookingFeePercent }}%</span>
            </div>
        </div>


        <div class="flex flex-col text-3xl font-bold mt-4">
            <div class="flex justify-between">Total <span>${{ booking.total }}</span></div>
            <div class="flex justify-between text-[#00AA00]">Paid <span>${{ booking.paid }}</span></div>
        </div>

        <div class="flex flex-col gap-5 mt-4">
            <button class="bg-[#703434] rounded-4xl text-white h-8">MESSAGE</button>
            <div class="flex justify-between">
                <button class="border w-57">MARK AS NO SHOW</button>
                <button class="border w-57">MARK AS NO SHOW CHARGE</button>
            </div>
        </div>
    </div>
</template>


<script setup>
import { reactive } from 'vue'

defineProps({ booking: Object })
// const bookingDetails = reactive({
//     name: 'Reaksmey Sean',
//     bookingId: 'e2021BookKH-0121233',
//     bookingAt: '2025-Feb-15 12:00 am',
//     priceDetails: {
//         original: {
//             nights: 1,
//             amount: 43
//         },
//         stay: {
//             nights: 3,
//             amount: 129
//         },
//         bookingFeePercent: 10
//     },
//     total: 129.35,
//     paid: 129.35
// })
</script>
