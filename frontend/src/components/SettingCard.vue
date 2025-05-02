<template>
  <div class="card">
    <h3 class="card-title">{{ title }}</h3>
    <ul>
      <li v-for="(item, index) in items" :key="index">
        <Icon :icon="item.icon" class="card-icon" />
        {{ item.label }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { Icon } from '@iconify/vue'

defineProps({
  title: String,
  items: Array
})
</script>

<style scoped>
.card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.card-title {
  font-size: 18px;
  margin-bottom: 16px;
  font-weight: 600;
}

ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

li {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 6px 0;
  font-size: 14px;
}

.card-icon {
  font-size: 18px;
  color: #000000;
}
</style>
