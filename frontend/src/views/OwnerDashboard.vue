<template>
  <div id="container" class="flex flex-col md:flex-row justify-center md:mr-50 min-h-screen">
    <div id="sidebar" class="flex items-center justify-center md:h-screen w-full md:w-auto">
      <Sidebar />
    </div>
    <div id="bookinglist" class="flex flex-col w-full md:pl-8 md:w-screen">
      <HeaderState />
      <PackageSelector :cards="packageCards" class="py-3" />
      <div class="flex flex-col lg:flex-row py-2 gap-5">
        <TopSelected />
        <RevenueChart :title="'Sales Revenue'" :subtitle="'In last 30 days revenue from rent.'"
          :monthly="{ value: '9.28K', change: 4.63 }" :weekly="{ value: '2.69K', change: -1.92 }"
          :daily="{ value: '0.94K', change: 1.92 }" :pieChartData="{
            labels: ['Daily(Avg)', 'Weekly', 'Monthly'],
            datasets: [{
              data: [1.92, -1.92, 4.63],
              backgroundColor: ['#000', '#fb923c', '#cbd5e1']
            }]
          }" />
        <CustomerChart :title="'New Customer'" :subtitle="'In last 30 days'" :monthly="{ value: '9.28K', change: 4.63 }"
          :weekly="{ value: '2.89K', change: -1.92 }" :daily="{ value: '0.90K', change: 1.92 }" :pieChartData="{
            labels: ['Daily(AVG)', 'Weekly', 'Monthly'],
            datasets: [{
              data: [1.92, -1.92, 4.63],
              backgroundColor: ['#000', '#60a5fa', '#fde68a']
            }]
          }" />
      </div>
      <RoomStatus />
      <OccupancyChart />
      <FeeBooking />
    </div>
  </div>
</template>

<script setup>
import Sidebar from '@/components/OwnerListBooking/Sidebar.vue';
import HeaderState from '@/components/OwnerDashboard/HeaderState.vue';
import PackageSelector from '@/components/OwnerDashboard/PackageSelector.vue';
import TopSelected from '@/components/OwnerDashboard/TopSelected.vue';
import RevenueChart from '@/components/OwnerDashboard/RevenueChart.vue';
import CustomerChart from '@/components/OwnerDashboard/CustomerChart.vue';
import RoomStatus from '@/components/OwnerDashboard/RoomStatus.vue';
import OccupancyChart from '@/components/OwnerDashboard/OccupancyChart.vue';
import FeeBooking from '@/components/OwnerDashboard/FeeBooking.vue';

const packageCards = [
  {
    title: 'Total Booking',
    badge: 'Total Booking',
    value: '11,230',
    percent: '24%',
    month: 12887,
    week: 12887,
    chartData: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [{
        label: 'Chart',
        data: [65, 59, 80, 81, 56, 55],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(255, 159, 64, 0.2)',
          'rgba(255, 205, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(153, 102, 255, 0.2)',
        ],
        borderColor: [
          'rgb(255, 99, 132)',
          'rgb(255, 159, 64)',
          'rgb(255, 205, 86)',
          'rgb(75, 192, 192)',
          'rgb(54, 162, 235)',
          'rgb(153, 102, 255)',
        ],
        borderWidth: 1,
      }],
    },
  },
  {
    title: 'Total Guests',
    badge: 'Total Guests',
    value: '5,432',
    valueUnit: '',
    monthLabel: 'BOOKED THIS MONTH',
    month: 3200,
    monthUnit: '',
    weekLabel: 'BOOKED THIS WEEK',
    week: 800,
    weekUnit: '',
    chartData: {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
      datasets: [{
        data: [2, 3, 4, 3, 5],
        backgroundColor: ['#d1d5db', '#d1d5db', '#d1d5db', '#d1d5db', '#2563eb'],
        borderWidth: 0,
      }],
    },
  },
  {
    title: 'Revenue',
    badge: 'Total Revenue',
    value: '21,500',
    valueUnit: 'USD',
    monthLabel: 'EARNED THIS MONTH',
    month: 15000,
    monthUnit: 'USD',
    weekLabel: 'EARNED THIS WEEK',
    week: 3500,
    weekUnit: 'USD',
    chartData: {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
      datasets: [{
        data: [3, 4, 2, 4, 6],
        backgroundColor: ['#d1d5db', '#d1d5db', '#d1d5db', '#d1d5db', '#2563eb'],
        borderWidth: 0,
      }],
    },
  }
];
</script>

<style scoped>
#container {
  width: 100%;
}

@media (max-width: 1024px) {
  #container {
    flex-direction: column;
    margin-right: 0;
  }

  #sidebar {
    width: 100%;
    height: auto;
  }

  #bookinglist {
    padding-left: 0;
    width: 100%;
  }
}

@media (max-width: 768px) {

  .flex.gap-10,
  .flex.gap-5,
  .flex.gap-25 {
    flex-direction: column !important;
    gap: 1rem !important;
  }

  .px-8,
  .pl-8,
  .px-50,
  .pl-80 {
    padding-left: 1rem !important;
    padding-right: 1rem !important;
  }
}

@media (max-width: 640px) {

  .w-screen,
  .w-full,
  .w-200,
  .w-60,
  .w-55,
  .w-50 {
    width: 100% !important;
    min-width: 0 !important;
    max-width: 100% !important;
  }

  .h-screen,
  .h-80,
  .h-55,
  .h-35 {
    height: auto !important;
    min-height: 0 !important;
    max-height: 100% !important;
  }

  .px-6,
  .px-5,
  .px-4,
  .px-12,
  .pl-12 {
    padding-left: 0.5rem !important;
    padding-right: 0.5rem !important;
  }
}
</style>