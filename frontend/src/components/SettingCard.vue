<template>
  <div class="card">
    <h3 class="card-title">{{ title }}</h3>
    <ul>
      <li
        v-for="(item, index) in items"
        :key="index"
      >
        <RouterLink :to="item.route" class="card-link">
          <Icon :icon="item.icon" class="card-icon" />
          {{ item.label }}
        </RouterLink>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { Icon } from '@iconify/vue'
import { RouterLink } from 'vue-router'

defineProps({
  title: String,
  items: Array // Each item should have: label, icon, route
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
  padding: 6px 0;
}

.card-link {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
  text-decoration: none;
  color: inherit;
  transition: color 0.2s;
}

.card-link:hover {
  color: #007bff;
}

.card-icon {
  font-size: 18px;
}
</style>
