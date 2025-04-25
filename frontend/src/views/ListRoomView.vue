<template>
  <!-- <ListRoom/> -->
  <!-- <ChatBox/> -->
  <ListBooking/>
  
</template>

<script>

import ListRoom from '../components/ListRoom.vue';
import ChatBox from '../components/ChatBox.vue';
import ListBooking from '@/components/ListBooking.vue';
import { RouterView } from 'vue-router';


export default {
  components: {
    ListRoom,
    ChatBox,
    ListBooking,
  },
};

</script>

<style scoped>

</style>