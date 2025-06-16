<template>
  <HeadingBar />
  <RouterView />
</template>
<script>
import { RouterView } from 'vue-router';
import HeadingBar from '@/components/LandingPage/HeadingBar.vue';
export default {
  components:{
    RouterView,HeadingBar

  },
}
</script>
