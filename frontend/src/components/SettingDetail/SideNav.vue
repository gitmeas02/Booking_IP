<template>
  <div class="side-nav">
    <ul>
      <li v-for="(item, index) in items" :key="index" :class="{ active: selectedIndex === index }"
        @click="handleClick(index)">
        <Icon :icon="item.icon" class="icon" />
        {{ item.label }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { Icon } from '@iconify/vue'

defineProps({
  selectedIndex: {
    type: Number,
    required: true
  }
})

const emit = defineEmits(['update:selectedIndex'])

const handleClick = (index) => {
  emit('update:selectedIndex', index)
}

const items = [
  { label: 'Personal details', icon: 'ic:baseline-person' },
  { label: 'Security settings', icon: 'ic:baseline-lock' },
  { label: 'Other travelers', icon: 'ic:baseline-people' },
  { label: 'Customization preferences', icon: 'ic:baseline-menu' },
  { label: 'Payment methods', icon: 'ic:baseline-payment' },
  { label: 'Privacy and Policy', icon: 'ic:baseline-privacy-tip' }
]
</script>

<style scoped>
.side-nav {
  background: #fff;
  border-radius: 12px;
  padding: 16px 0;
  width: 250px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  flex-shrink: 0;
}

/* Items */
ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

li {
  padding: 12px 20px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
}

li .icon {
  font-size: 18px;
}

li.active {
  background-color: #eaf3ff;
  color: #0071c2;
  font-weight: bold;
}

li.active .icon {
  color: #0071c2;
}

li:hover {
  background-color: #f5f5f5;
}

/* Responsive styles */
@media (max-width: 1024px) {
  .side-nav {
    width: 100%;
    border-radius: 8px;
  }

  li {
    font-size: 16px;
    padding: 14px 16px;
  }
}

@media (max-width: 640px) {
  .side-nav {
    width: 100%;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
    background: #fff;
  }

  ul {
    display: flex;
    flex-direction: column;
    /* Stack items vertically */
    gap: 0;
    padding: 0;
  }

  li {
    padding: 12px 16px;
    font-size: 15px;
    border-bottom: 1px solid #eee;
    border-radius: 0;
  }

  li:last-child {
    border-bottom: none;
  }
}
</style>
