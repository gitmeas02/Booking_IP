//Component A passes data to B
<template>
  <ComponentB :dataFromA="message" />
</template>
<script setup>
import ComponentB from './ComponentB.vue'
const message = 'Hello from A'
</script>


// Component C receives prop
<template>
  <ComponentC :dataFromA="dataFromA" />
</template>
<!-- ComponentB.vue -->
<script setup>
import ComponentC from './ComponentC.vue'
defineProps(['dataFromA']) // receives prop from A
</script>


//  Component B receives prop and passes it to C 
<template>
  <div>{{ dataFromA }}</div>
</template>
<!-- ComponentC.vue -->
<script setup>
defineProps(['dataFromA']) // receives final prop from B
</script>