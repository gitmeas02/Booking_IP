<template>
  <div id="container" class="flex justify-center mr-50 h-screen">
    <div id="sidebar" class="flex items-center justify-center h-screen">
      <Sidebar />
    </div>
    <div id="bookinglist" class="flex flex-col w-screen pl-8 h-screen">
      <HeaderState/>
      <PackageSelector class="py-3"/>
      <div class="flex py-2 gap-5">
        <TopSelected/>
        <RevenueChart/>
        <CustomerChart/>
      </div>
      <RoomStatus/>
      <OccupancyChart/>
      <FeeBooking/>
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

</script>

<style scoped></style>