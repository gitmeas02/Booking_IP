<template>
  <div class="flex flex-col border border-gray-500 rounded-xl px-6 py-4 gap-2">
    <div class="flex justify-between">
      <h1 id="agbalumo" class="text-xl">Fee Booking</h1>
      <div class="flex">
        <div id="agbalumo" class="flex items-center bg-[#37474F] rounded-2xl text-white px-2 py-1 text-xs">Fee Booking
        </div>
        <MessageCircleQuestion />
      </div>
    </div>

    <div>
      <div class="flex justify-between px-4 py-2">
        <div>THIS LAST MONTH</div>
        <div class="flex gap-12">
          <div>2025/January/12</div>
          <div>2025/January/12</div>
        </div>
        <div class="flex gap-12">
          <div>ID PAY FEE</div>
          <div>2021827272</div>
        </div>
      </div>
      <div class="pl-12">11,230 USD</div>
    </div>

    <div>
      <div class="flex justify-between px-4 py-2">
        <div>THIS LAST MONTH</div>
        <div class="flex gap-12">
          <div>2025/January/12</div>
          <div>2025/January/12</div>
        </div>
        <div class="flex gap-12">
          <div>ID PAY FEE</div>
          <div>2021827272</div>
        </div>
      </div>
      <div class="pl-12">11,230 USD</div>
    </div>

    <div class="border-t pt-2">
      <div class="flex justify-between">
        <div class="px-4">TOTAL AMOUNT TO PAY FEE</div>
        <div class="flex gap-6">
          <div>2025/January/12</div>
          <div>2025/January/12</div>
          <div>2 MONTHS</div>
        </div>
      </div>
      <div class="pl-12">22,460 USD</div>
    </div>
  </div>
</template>

<script setup>
import { MessageCircleQuestion } from 'lucide-vue-next';

</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Agbalumo&display=swap');

#agbalumo {
  font-family: "Agbalumo", system-ui;
  font-weight: 400;
  font-style: normal;
}
</style>