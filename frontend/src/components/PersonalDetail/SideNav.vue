<template>
    <div class="side-nav">
      <ul>
        <li
          v-for="(item, index) in items"
          :key="index"
          :class="{ active: index === 0 }"
        >
          <i :class="`icon ${item.icon}`"></i>
          {{ item.label }}
        </li>
      </ul>
    </div>
  </template>
  
  <script setup>
  const items = [
    { label: 'Personal details', icon: 'ri-user-line' },
    { label: 'Security settings', icon: 'ri-lock-2-line' },
    { label: 'Other travelers', icon: 'ri-user-shared-line' },
    { label: 'Customization preferences', icon: 'ri-settings-3-line' },
    { label: 'Payment methods', icon: 'ri-bank-card-line' },
    { label: 'Privacy and Policy', icon: 'ri-shield-check-line' }
  ]
  </script>
  
  <style scoped>
  .side-nav {
    background: #fff;
    border-radius: 12px;
    padding: 16px 0;
    width: 240px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  }
  
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
  </style>
  