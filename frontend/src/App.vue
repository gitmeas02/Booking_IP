<template>
  <HeadingBar/>
  <RouterView/>
</template>
<script>

import { useRoomStore } from '@/stores/store';
import { RouterView } from 'vue-router';
import HeadingBar from './components/LandingPage/HeadingBar.vue';
export default {
  components:{
    RouterView,HeadingBar

  },
setup() {
  const roomStore = useRoomStore();
  roomStore.fetchRooms();
  roomStore.fetchHotels();

  console.log(roomStore.rooms[1])

  return {
    roomStore
  }
},


}
</script>
